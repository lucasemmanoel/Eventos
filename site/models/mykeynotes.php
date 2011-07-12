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

class P22eventosModelMykeynotes extends JModel
{
	// Array com o total de registros.
	public $_totalRegistros;
	public $_registros;
	public $idevento;
	public $id;
	public $lib;

	function __construct()
	{
		global $mainframe;

		parent::__construct();
		$this->idevento = JRequest::getInt( 'idevento' );

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->close( 'Evento não encontrado' );
		}

		$this->idevento = JRequest::getInt('idevento');

		$user = &JFactory::getUser();

		if( !$user->guest )
		{
			$this->id = $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );

			if ( !$this->_isPalestrante() ) $mainframe->close( 'Você não está cadastrado como Palestrante.' );
		}
		else
		{
			$mainframe->close( 'Participante não encontrado.' );
		}
	}

	private function _isPalestrante()
	{
		$palestrante = $this->lib->countDados( 'p22eventos_inscritos' , ' WHERE p.id_participante=' . intval( $this->id ). ' AND p.tp_reg=2' );

		return ( $palestrante > 0 ) ? true : false;
	}

	function totalRegistros( $where )
	{
		// Conexão com a base de dados.
		$db =& JFactory::getDBO();

		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_totalRegistros ) )
		{
			$query = 'SELECT COUNT(id)'
			. ' FROM #__p22eventos_palestras AS p'
			. $where
			;
			$db->setQuery( $query );
			$this->_totalRegistros = $db->loadResult();
		}
		return $this->_totalRegistros;
	}

	function registros( $where, $orderby, $pageNav )
	{
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			// seleciona os registros para exibição.
			$query = 'SELECT p.*,'
			. ' ( SELECT t.nome FROM #__p22eventos_trilhas AS t WHERE t.id = p.id_trilha ) AS trilha,'
			. ' ( SELECT tt.cor FROM #__p22eventos_trilhas AS tt WHERE tt.id = p.id_trilha ) AS cor'
			. ' FROM #__p22eventos_palestras AS p'
			. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
			. $where
			. $orderby
			;

			$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
			$this->_registros = $this->_db->loadObjectList();
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

	public function getTrilhas()
	{
		$query = 'SELECT DISTINCT p.id_trilha'
		. ' FROM #__p22eventos_palestras AS p'
		. ' WHERE p.tipo=0'
		;
		$reg_trilhas = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );

		if( !count( $reg_trilhas ) ) return;

		$trilhas = $this->lib->getDados( 'p22eventos_trilhas' , 'p.id,p.nome' , ' WHERE p.id IN (' . implode( ',' , $reg_trilhas ) . ') AND p.id_evento=' . intval( $this->idevento ) , 'ORDER BY p.nome' , 'loadObjectList' );

		return $trilhas;
	}

	public function getPalestrasPontuacao()
	{
		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'models' . DS . 'avaliacaos.php' );
		$avaliadores	= P22eventosModelAvaliacaos::getAvaliadores();
		$avaliacoes		= P22eventosModelAvaliacaos::getAvaliacoes();
		$pontuacao		= P22eventosModelAvaliacaos::getPontuacao();

		return $pontuacao;
	}
}