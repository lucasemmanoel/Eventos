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

class P22eventosModelEvento extends JModel
{
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setId( ( int )$array[ 0 ] );
	}
	
	function setId( $id )
	{
		// Define o ID e a variável dos dados.
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$query = ' SELECT * FROM #__p22eventos'.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}

		return $this->_data;
	}
	
	function store( $data )
	{	
		$row =& $this->getTable( 'p22evento' );

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

		return true;
	}
	
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function getLocais()
	{
		$query = 'SELECT id, nome'
		. ' FROM #__p22eventos_locais'
		. ' WHERE published=1'
		;
		$this->_db->setQuery( $query );
		$locais = $this->_db->loadObjectList();

		return $locais;
	}
}
