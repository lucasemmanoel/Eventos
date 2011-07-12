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

class P22eventosModelSelevento extends JModel
{
	private $_id;

	public $lib;

	public $seAvaliador;

	function __construct()
	{
		parent::__construct();
		$this->_id = JRequest::getInt( 'idevento' );

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );
	}

	function getEventName()
	{
		$query = 'SELECT DISTINCT p.nome'
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.published=1 AND p.id=' . intval( $this->_id )
		;
		$name = $this->lib->getRegistrosCustom( $query , 'loadResult' );
		
		return $name;
	}

	public function seColaborador()
	{
		$user = &JFactory::getUser();

		if ( empty( $this->_participantes ) )
		{
			$query = 'SELECT p.id '
			. ' FROM #__p22eventos_participantes AS p'
			. ' WHERE p.id_user=' . intval( $user->id )
			;
			$this->_participantes = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );
		}

		if ( count( $this->_participantes ) )
		{
			$where = ' WHERE i.id_participante IN (' . implode( ',' , $this->_participantes ) . ')';
		}
		else
		{
			return false;
		}
		
		$query = 'SELECT i.tp_reg '
		. ' FROM #__p22eventos_inscritos AS i'
		. $where
		;
		$tp_reg = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );

		if ( count( $tp_reg ) )
		{
			$return = ( in_array( 1 , $tp_reg ) ) ? true : false;

			$this->seAvaliador = ( in_array( 2 , $tp_reg ) ) ? true : false;
		}
		else $return = false;

		return $return;
	}
}