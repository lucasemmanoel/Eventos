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

class P22eventosControllerInscricao extends P22eventosController
{
	/*
	 * Constructor (registra tarefas adicionais ao método)
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'		, 'edit' );
	}
	
	/**
	 * Salva um registro (e direciona para a página principal).
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model				= $this->getModel( 'inscricao' );
		$data				= JRequest::get( 'post' );
		$task				= $this->getTask();
		$data['published']	= 0;
		$data['id_evento']	= $data['idevento'];
		$data['username']	= JRequest::getVar('username', '', 'post', 'username');
		$data['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['cpf']		= str_replace( '.' , '' , $data['cpf'] );
		$data['cpf']		= str_replace( '-' , '' , $data['cpf'] );
		$data['cpf']		= intval( $data['cpf'] );

		if ( $model->store( $data ) )
		{
			$msg = JText::_( 'Informações registradas' );
		}
		else
		{
			$msg = JText::_( 'Erro ao tentar registrar as informações: ' . $model->getError() );
			$msgType = 'error';
		}

		$link = 'index.php?option=com_p22evento&Itemid=' . intval( $data['Itemid'] );
		$this->setRedirect( $link, $msg , $msgType );
	}

	function login()
	{
		// Check for request forgeries
		JRequest::checkToken('request') or jexit( 'Invalid Token' );

		global $mainframe;
	
		$credentials = array();
		$credentials['username']	= JRequest::getVar('username', '', 'method', 'username');
		$credentials['password']	= JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);
		$postToken					= JRequest::getVar( 'token', '', 'post', 'string' );

		//preform the login action
		$error = $mainframe->login($credentials, $options);

		$doc	= &JFactory::getDocument();
		$js		.= "window.onload=function() {";

		if(!JError::isError($error))
		{
			$model				= $this->getModel( 'inscricao' );
			$model->idevento	= JRequest::getInt( 'idevento' );
			$registro			= $model->getRegistro();
			$token				= JUtility::getToken();

			$js .= "window.top.document.getElementById('user_box_login').style.display = 'none';";
			$js .= "window.top.document.getElementById('newUser_box').remove();";
			$js .= "window.top.document.getElementById('user_radio_box').remove();";
			$js .= "window.top.document.getElementById('logged_user_box').style.display = '';";
			
			if ( !$registro->id )
			{
				$user = &JFactory::getUser();
				$js .= "window.top.document.getElementById('logged_user_box_subscribe').style.display = '';";
				$js .= "window.top.document.getElementById('logged_user_box_form').style.display = 'none';";
				$js .= "window.top.document.getElementById('logged_user_box_registered').style.display = 'none';";
				$js .= "window.top.document.eventForm3.id_user.value='{$user->id}';";
				$js .= "window.top.document.eventForm3.token.value='{$token}';";
			}
			elseif( $registro->id && !$registro->inscricao )
			{
				$js .= "window.top.document.getElementById('logged_user_box_subscribe').style.display = 'none';";
				$js .= "window.top.document.getElementById('logged_user_box_form').style.display = '';";
				$js .= "window.top.document.getElementById('logged_user_box_registered').style.display = 'none';";
				$js .= "window.top.document.eventForm2.id.value='{$registro->id}';";
				$js .= "window.top.document.eventForm2.token.value='{$token}';";
			}
			elseif ( $registro->id && $registro->inscricao )
			{
				$js .= "window.top.document.getElementById('logged_user_box_subscribe').style.display = 'none';";
				$js .= "window.top.document.getElementById('logged_user_box_form').style.display = 'none';";
				$js .= "window.top.document.getElementById('logged_user_box_registered').style.display = '';";
			}
			$js .= "window.top.document.eventForm3.token.value='{$token}';";

			$js .= "var elems = window.top.document.getElementsByTagName('input');";
			$js .= "for(var i=0;i<elems.length; i++) {";
			$js .= "var classN = elems[i];";
			$js .= "if(classN.getAttribute('type') == 'hidden' && classN.getAttribute('name') == '{$postToken}' ) {";
			$js .= "elems[i].setAttribute('name','{$token}')";
			$js .= "}";
			$js .= "}";
		}
		else
		{
			$err		= JError::getError();
			$message	= JText::_( $err->message , true );
			$js			.= "window.top.alert( '{$message}' )";
		}
		$js		.= "}";
		
		$doc->addScriptDeclaration( $js );
	}
}