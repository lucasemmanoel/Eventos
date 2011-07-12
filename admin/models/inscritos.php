<?php
/*
 * ------------------------------------------------------------------------
 * P22Eventos - Version 1.0
 * ------------------------------------------------------------------------
 * Copyright (C) 2011
 * Author: Porta22 - Hugo Seabra
 * E-mail: hugo@porta22.com.br
 * Websites: http://porta22.com.br
 * This file may not be redistributed in whole or significant part.
 * Created on : 05/07/2011, 23:40:08
 * @license GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * ------------------------------------------------------------------------
 */

// Não permite o acesso direto ao arquivo.
defined( '_JEXEC' ) or die( 'Restricted access' );

// Recursos Joomla! para trabalhar com models.
jimport( 'joomla.application.component.model' );

class P22eventosModelInscritos extends JModel
{
	// Array com o total de registros.
	public $_totalRegistros;
	public $_registros;
	public $idevento;
	public $lib;
	public $participantes;
	private $_profissoes;

	function __construct()
	{
		global $mainframe;

		parent::__construct();
		$this->idevento = JRequest::getInt( 'idevento' );

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}
	}

	public function getParticipantes( $where )
	{
		$query = 'SELECT DISTINCT i.id_participante'
		. ' FROM #__p22eventos_inscritos AS i'
		. ' INNER JOIN #__p22eventos_participantes AS p ON i.id_participante = p.id'
		. ' RIGHT JOIN #__users AS u ON u.id = p.id_user'
		. ' WHERE ' . $where
		;		
		$this->participantes = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );
		
	}

	function totalRegistros()
	{
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_totalRegistros ) )
		{
			$this->_totalRegistros = count( $this->participantes );
		}
		return $this->_totalRegistros;
	}
	
	function registros( $pageNav )
	{		
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			global $mainframe;

			// Contexto em que o usuário se encontra.
			// Utilizado para guardar os dados escolhidos, no formulário, pelo usuário.
			$context = 'com_p22evento.inscrito.list.';

			// Ordem de exibição dos registros ( asc/desc ) - default = asc.
			$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir'	, 'filter_order_Dir', '', 'word' );
			
			$where = ( $where ) ? ' AND ' . $where : '';

			$profissoes = $this->getProfissoes( true );

			// seleciona os registros para exibição.
			$query = 'SELECT p.*, i.* , u.name'
			. ' FROM #__p22eventos_participantes AS p, #__p22eventos_inscritos AS i, #__users AS u'
			. ' WHERE i.id_participante = p.id AND u.id = p.id_user'
			. ' AND p.id IN (' . implode( ',' , $this->participantes ) . ')'
			;
			$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
			$registros			= $this->_db->loadObjectList();
			$this->_registros	= array();
			
			for( $c = 0 ; $c < count( $registros ) ; $c++ )
			{
				$row = &$registros[ $c ];

				$row->tp_reg = intval( $row->tp_reg );
				
				switch( $row->tp_reg )
				{
					case 1:
						$tipo = 'Colaborador';
						break;
					case 2:
						$tipo = 'Palestrante';
						break;
					default:
						$tipo = 'inscrito';
						break;
				}

				if ( $tipo == 'inscrito' )
				{
					$row->link = 1;

					$row->tipo = ( $this->_registros[ $row->name . $row->id ]->tipo ) ? $this->_registros[ $row->name . $row->id ]->tipo : '';
				}
				else
				{
					$row->tipo = ( $this->_registros[ $row->name . $row->id ]->tipo ) ? $this->_registros[ $row->name . $row->id ]->tipo . ' / ' . $tipo : $tipo;
				}

				$row->profissao = $profissoes[ $row->id_profissao ];

				$this->_registros[ $row->name . $row->id ] = $row;
			}

			// Ordem Alfabética crescente
			ksort( $this->_registros );

			if ( $filter_order_Dir == 'desc' )
			{
				$this->_registros = array_reverse( $this->_registros );
			}

			// Inserindo chaves
			if( count( $this->_registros ) )
			{
				$j = 0;
				foreach( $this->_registros AS $key => $registro )
				{
					$this->_registros[ $j ] = $registro;
					unset( $this->_registros[ $key ] );
					$j++;
				}
			}

//			echo JUtility::dump( $chkReg );
//			echo JUtility::dump( $this->_registros );
//			echo JUtility::dump( $registros );
//			die;
		}
		return $this->_registros;
	}

	public function setRegistersDates( $registros , $fields , $local = false )
	{
		if( !count( $registros ) || !count( $fields ) ) return;

		foreach( $registros AS &$row )
		{
			$toArray = get_object_vars( $row );

			foreach( $fields AS $field )
			{
				if( array_key_exists( $field , $toArray ) )
				{
					if( $row->$field == '0000-00-00 00:00:00' ) continue;
					$row->$field = $this->lib->now( $local , $toArray[ $field ] );
				}
			}
		}
	}

	public function getEventName()
	{
		return $this->lib->eventName();
	}

	public function getProfissoes( $compiled_array = false )
	{
		if ( empty( $this->_profissoes ) )
		{
			$query = 'SELECT DISTINCT pp.id_profissao'
			. ' FROM #__p22eventos_participantes AS pp'
			;
			$reg_prof = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );

			if( !count( $reg_prof ) ) return;

			$this->_profissoes = $this->lib->getDados( 'p22eventos_profissoes' , 'p.id , p.nome' , ' WHERE p.id IN (' . implode( ',' , $reg_prof ) . ')' , 'ORDER BY p.nome' , 'loadObjectList' );
		}

		if ( $compiled_array )
		{
			if( count( $this->_profissoes ) )
			{
				$profs = array();
				foreach( $this->_profissoes AS $key => $row )
				{
					$profs[ $row->id ] = $row->nome;
				}
				$prof = $profs;
			}
		}
		else
		{
			$prof = $this->_profissoes;
		}

		return $prof;
	}
}