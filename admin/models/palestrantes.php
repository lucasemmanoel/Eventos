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

class P22eventosModelPalestrantes extends JModel
{
	// Array com o total de registros.
	public $_totalRegistros;
	public $_registros;
	public $idevento;
	public $lib;

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

	function totalRegistros( $where )
	{
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_totalRegistros ) )
		{
			$query = 'SELECT COUNT(id)'
			. ' FROM #__p22eventos_participantes AS p'
			. ' WHERE i.tp_reg=2'
			. $where
			;
			$this->_db->setQuery( $query );
			$this->_totalRegistros = $this->_db->loadResult();
		}
		return $this->_totalRegistros;
	}
	
	function registros( $where, $orderby, $pageNav )
	{	
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			$where = ( $where ) ? ' AND ' . $where : '';

			// seleciona os registros para exibição.
			$query = 'SELECT i.registrado_em, i.published, p.id, p.curriculo, p.avatar_img, u.name, pp.nome AS profissao'
			. ' FROM #__p22eventos_inscritos AS i'
			. ' LEFT JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
			. ' LEFT JOIN #__p22eventos_profissoes AS pp ON pp.id = p.id_profissao'
			. ' INNER JOIN #__users AS u ON u.id = p.id_user'
			. ' WHERE i.tp_reg=2 AND u.id = p.id_user '
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
}