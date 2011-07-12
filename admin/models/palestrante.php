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

class P22eventosModelPalestrante extends JModel
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
			$query = ' SELECT p.*,'
			. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS name'
			. ' FROM #__p22eventos_inscritos AS i'
			. ' INNER JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
			. ' WHERE i.tp_reg=2 AND i.id_evento='. intval( $this->idevento ) .' AND i.id_participante = ' . intval( $this->id );
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
		$data['tp_reg'] = 2;
		
		if ( $data['tp_cad'] == 0 )
		{
			// Get required system objects
			$user 		= clone( JFactory::getUser() );
			$authorize	=& JFactory::getACL();

			// If user registration is not allowed, show 403 not authorized.
			$usersConfig = &JComponentHelper::getParams( 'com_users' );

			if ( $usersConfig->get('allowUserRegistration') == '0' )
			{
				JError::raiseError( 403, JText::_( 'Access Forbidden' ) );
				return;
			}

			// Initialize new usertype setting
			$newUsertype = $usersConfig->get( 'new_usertype' );
			if ( !$newUsertype )
			{
				$newUsertype = 'Registered';
			}

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
			$data['id_user'] = ( $data['id_user'] ) ? $data['id_user'] : 0;
			$user->set( 'id'		, $data['id_user'] );
			$user->set( 'usertype'	, $newUsertype );
			$user->set( 'gid'		, $authorize->get_group_id( '' , $newUsertype , 'ARO' ) );

			$date =& JFactory::getDate();
			$user->set( 'registerDate' , $date->toMySQL() );

			// If user activation is turned on, we need to set the activation information
			$useractivation = $usersConfig->get( 'useractivation' );
			if ($useractivation == '1')
			{
				jimport('joomla.user.helper');
				$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
				$user->set('block', '1');
			}

			// If there was an error with registration, set the message and display form
			if ( !$user->save() )
			{
				$this->setError( JText::_( $user->getError() ) );
				return false;
			}
		}

		$data['id_user'] = ( $data['tp_cad'] == 0 ) ? $user->id : $data['id_user'];

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

		$this->id = $row->id;

		if( !$data['id'] && $data['tp_cad'] == 0 )
		{
			// Send registration confirmation mail
			$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW );
			$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password ); //Disallow control chars in the email

			if ( $useractivation == 1 )
			{
				$this->_sendMail( $user, $password );
			}
		}

		if ( !$this->lib->registraRelacaoEventoUser( $data['idevento'] , $row->id , $data['tp_reg'] ) )
		{
			return true;
		}

		return true;
	}

	private function _sendMail( &$user , $password )
	{
		global $mainframe;

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= &JComponentHelper::getParams( 'com_users' );
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= str_replace( 'administrator/' , '' , JURI::base() );
		$evento			= $this->lib->getDados( 'p22eventos' , 'p.nome' , ' WHERE p.id=' . intval( $this->idevento ) , '' , 'loadResult' );
		$subject		= sprintf ( JText::_( 'Detalhes de conta para' ), $name, $sitename);
		$subject		= html_entity_decode($subject, ENT_QUOTES);

		if ( $useractivation == 1 )
		{
			$SEND_MSG_ACTIVATE	= "Olá %s,\n\nVocê foi cadastrado como palestrante no evento %s em %s. Sua conta foi criada e deve ser ativada antes de ser usada.\nPara ativar a conta, clique no link ou copie-cole o mesmo em seu navegador:\n%s\n\nApós a ativação você pode efetuar o login em %s usando o Nome de Usuário e Senha:\n\nNome de Usuário - %s\nSenha - %s";
			$message			= sprintf ( JText::_( $SEND_MSG_ACTIVATE ), $name, $evento , $sitename, $siteURL."index.php?option=com_user&task=activate&activation=".$user->get('activation'), $siteURL, $username, $password);
		}
		else
		{
			$SEND_MSG	= "Olá %s,\n\nVocê foi cadastrado como palestrante no evento %s em %s.\n\nVocê agora pode efetuar o login em %s usando o Nome de Usuário e Senha:\n\nNome de Usuário - %s\nSenha - %s";
			$message	= sprintf ( JText::_( $SEND_MSG ), $name , $evento , $sitename , $siteURL , $username , $password );
		}

		$message = html_entity_decode($message, ENT_QUOTES);

		//get all super administrator
		$admins = $this->lib->getDados( 'users' , 'p.name , p.email , p.sendEmail' , ' WHERE p.gid = 25' , '' , 'loadObjectList' );
		
		// Send email to user
		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $admins[0]->name;
			$mailfrom = $admins[0]->email;
		}

		JUtility::sendMail( $mailfrom , $fromname , $email , $subject , $message );

		// Send notification to all administrators
		$subject2 = sprintf ( JText::_( 'Detalhes de conta para' ), $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		foreach ( $admins as $admin )
		{
			if ( $admin->sendEmail )
			{
				$SEND_MSG_ADMIN = "Olá %s,\n\nUm novo usuário foi registrado como palestrante do evento %s em %s.\nEste e-mail contém os detalhes:\n\nNome - %s\ne-mail - %s\nNome de Usuário - %s\n\nPor favor, não responda a esta mensagem pois foi gerada automaticamente e tem caracter informativo apenas.";
				$message2 = sprintf ( JText::_( $SEND_MSG_ADMIN ) , $admin->name , $evento , $sitename , $name , $email , $username );
				$message2 = html_entity_decode( $message2, ENT_QUOTES);
				JUtility::sendMail( $mailfrom , $fromname , $admin->email , $subject2 , $message2 );
			}
		}
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if ( !count( $cids ) ) return false;

		$palestra = $this->lib->hasData( $this->idevento , 'p22eventos_palestras' , 'id_palestrante' , $cids );

		$return = true;

		foreach( $cids AS $cid )
		{
			if ( $palestra[ $cid ] )
			{
				JError::raiseNotice( 100, 'O Palestrante #ID ' . $cid . ' está vinculado a uma ou mais Palestras. Remova sua(s) palestra(s) e tente excluí-lo novamente.' );
				$return = false;
				continue;
			}

			$query	= 'DELETE FROM #__p22eventos_inscritos WHERE id_evento='. intval( $this->idevento ) .' AND id_participante='. inval( $cid ) .' AND tp_reg=2';

			$this->_db->setQuery( $query );

			if( !$this->_db->query() )
			{
				$this->setError( JText::_( $this->_db->getErrorMsg() ) );
				return false;
			}
		}

		return $return;
	}

	function getUsers()
	{
		$query = ' SELECT p.id_user'
		. ' FROM #__p22eventos_inscritos AS i'
		. ' INNER JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
		. ' WHERE i.tp_reg=2'
		;
		$registered = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );
		$registered = array_unique( $registered );
		
		$where		= ( count( $registered ) ) ? ' WHERE id NOT IN ( '. implode( ',' , $registered ) .' )' : '';

		$users		= $this->lib->getDados( 'users' , 'p.id, p.name' , $where , ' ORDER BY p.name' , 'loadObjectList' );

		return $users;
	}
}