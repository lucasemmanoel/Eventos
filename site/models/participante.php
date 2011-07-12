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

class P22eventosModelParticipante extends JModel
{
	public $id;
	public $lib;
	public $idevento;
	public $numEmailsMsg;
	
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct( $id = null )
	{
		global $mainframe;
		
		parent::__construct();

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->id = JRequest::getInt( 'pid' );

		if ( !$this->id )
		{
			$user = &JFactory::getUser();
			
			if( !$user->guest )
			{
				$this->id = $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );
			}
			else
			{
				$this->id = $id;
				if ( !$this->id )
				{
					$mainframe->close( 'Acesso incorreto ou negado.' );
				}
			}
		}
	}

	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$query = ' SELECT p.*, pp.nome AS profissao,'
			. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS nome,'
			. ' ( SELECT u.email FROM #__users AS u WHERE u.id = p.id_user ) AS email,'
			. ' ( SELECT u.username FROM #__users AS u WHERE u.id = p.id_user ) AS username'
			. ' FROM #__p22eventos_participantes AS p'
			. ' INNER JOIN #__p22eventos_profissoes AS pp ON pp.id = p.id_profissao'
			. ' WHERE p.id = ' . intval( $this->id );
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		
		return $this->_data;
	}
	
	function store( $data )
	{
		// Get required system objects
		$user 		= clone( JFactory::getUser() );

		$post['name']		= $data['nome'];
		$post['email']		= $data['email'];
		$post['username']	= $data['username'];
		$post['password']	= $data['password'];
		$post['password2']	= $data['password2'];

		// Bind the post array to the user object
		if ( !$user->bind( $post , 'usertype' ) )
		{
			$this->setError( $user->getError() );
			return false;
		}

		// Set some initial user values
		$user->set( 'id'		, $data['id_user'] );

		// If there was an error with registration, set the message and display form
		if ( !$user->save() )
		{
			$this->setError( JText::_( $user->getError() ) );
			return false;
		}

		$row =& $this->getTable( 'participantes' );

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

	function getProfissoes()
	{
		$query = 'SELECT id, nome'
		. ' FROM #__p22eventos_profissoes'
		. ' WHERE published=1'
		. ' ORDER BY nome'
		;
		$this->_db->setQuery( $query );
		$profissoes = $this->_db->loadObjectList();

		return $profissoes;
	}
}