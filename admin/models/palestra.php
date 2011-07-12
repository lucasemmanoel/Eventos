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

class P22eventosModelPalestra extends JModel
{
	public $id;
	public $lib;
	public $idevento;
	
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		global $mainframe;
		
		parent::__construct();

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->idevento = JRequest::getInt( 'idevento' );
		$this->lib		= new P22Eventos();
		$this->lib->setDBO( $this->_db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}

		$array = JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setId( ( int )$array[ 0 ] );
	}
	
	function setId( $id )
	{
		// Define o ID e a variável dos dados.
		$this->id		= $id;
		$this->_data	= null;
	}

	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$query = 'SELECT p.*,'
			. ' ( SELECT u.name FROM #__users AS u WHERE u.id = pp.id_user ) AS palestrante,'
			. ' ( SELECT t.nome FROM #__p22eventos_trilhas AS t WHERE t.id = p.id_trilha ) AS trilha,'
			. ' ( SELECT tt.cor FROM #__p22eventos_trilhas AS tt WHERE tt.id = p.id_trilha ) AS cor'
			. ' FROM #__p22eventos_palestras AS p'
			. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
			. '  WHERE p.id = ' . intval( $this->id );
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;
	}
	
	function store( $data )
	{
		if ( !$data['id'] )
		{
			$data['registrado_em']	= $this->lib->now();
		}

		$row =& $this->getTable( 'palestras' );

		// Bind the form fields to the hello table
		if ( !$row->bind( $data ) )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Make sure the hello record is valid
		if ( !$row->check() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Store the web link table to the database
		if ( !$row->store() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		$this->id = $row->id;

		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$grade = $this->lib->hasData( $this->idevento , 'p22eventos_grades' , 'id_palestra' , $cids );

		$row =& $this->getTable( 'palestras' );

		$return = true;
		if (count( $cids ) )
		{
			foreach( $cids AS $cid )
			{
				if ( $grade[ $cid ] )
				{
					JError::raiseNotice( 100, 'A Palestra #ID ' . $cid . ' possui horários na grade vinculados a ela. Altere a grade de palestras e depois volte para excluí-la.' );
					$return = false;
					continue;
				}

				if ( !$row->delete( $cid ) )
				{
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return $return;
	}

	function gettrilha()
	{
		$trilhas	= $this->lib->getDados( 'p22eventos_trilhas' , 'p.id, p.nome' , ' WHERE p.published=1 AND p.id_evento=' . intval( $this->idevento ) , ' ORDER BY p.nome' , 'loadObjectList' );
		return $trilhas;
	}

	function getPalestrante()
	{
		$query = 'SELECT p.id,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS nome'
		. ' FROM #__p22eventos_inscritos AS i'
		. ' LEFT JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
		. ' WHERE i.id_evento=' . intval( $this->idevento ) . ' AND i.tp_reg=2'
		. ' ORDER BY nome'
		;
		$palestrantes = $this->lib->getRegistrosCustom( $query , 'loadObjectList' );
		
		return $palestrantes;
	}
}