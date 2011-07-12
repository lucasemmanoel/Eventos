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

// Recursos Joomla! para trabalhar com controllers.
jimport( 'joomla.application.component.controller' );

class P22eventosController extends JController
{	
	// Método para exibir a view.
	function display()
	{
		$task			= JRequest::getCmd( 'task' );
		$view			= JRequest::getCmd( 'view' );
		$methodValue	= ( !$task ) ? $view : $task;
		$method			= method_exists( 'P22eventosController' , $methodValue );
		$user			= &JFactory::getUser();
		$registered		= $this->_isRegistered( $user->id );
		$pid                    = JRequest::getInt('pid'); // Profile ID

		if ( ( ( $user->guest && !$method ) || $registered === false ) && !$pid )
		{
			if ( ( !$task && $view ) && ( $view != 'eventos' && $view != 'p22eventos' ) )
			{
				$this->registerTask( $view , $view );
			}
			else
			{
				JRequest::setVar( 'view', 'eventos' );
			}
		}
		
		parent::display();
	}

	private function _isRegistered( $id )
	{
		$db = &JFactory::getDBO();

		$query = 'SELECT id FROM #__p22eventos_participantes WHERE id_user=' . intval( $id );
		$db->setQuery( $query );
		$reg = $db->loadResult();
		return ( $reg > 0 ) ? true : false;
	}

	function eventos()
	{
		JRequest::setVar( 'view', 'eventos' );
        parent::display();
	}

	function subscribe()
	{
		JRequest::setVar( 'view', 'inscricao' );		
        parent::display( $tpl );
	}

	function colaborador()
	{
		JRequest::setVar( 'view', 'colaborador' );
        parent::display( $tpl );
	}

	function palestra()
	{
		JRequest::setVar( 'view', 'palestra' );
        parent::display( $tpl );
	}

	function mykeynotes()
	{
		JRequest::setVar( 'view', 'mykeynotes' );
        parent::display( $tpl );
	}

	function palestrantes()
	{
		JRequest::setVar( 'view', 'palestrantes' );
        parent::display( $tpl );
	}

	function avaliacao()
	{
		JRequest::setVar( 'view', 'avaliacao' );
		parent::display();
	}

	function gradeprogramacao()
	{
		JRequest::setVar( 'view', 'gradeprogramacao' );
		parent::display();
	}

	function certificado()
	{
		if ( !JRequest::getCmd('tmpl') )
		{
			JRequest::setVar( 'tmpl', 'component' );
		}
		JRequest::setVar( 'view', 'certificado' );
		parent::display();
	}

	function selevento()
	{
		JRequest::setVar( 'view', 'selevento' );
        parent::display();
	}

	function certvalidate()
	{
		JRequest::setVar( 'view', 'certvalidate' );
        parent::display();
	}

	function avalpalestra()
	{
		JRequest::setVar( 'view', 'avalpalestra' );
        parent::display();
	}

	public function ajax()
	{
		//Tipo de aplicação
		$tp = JRequest::getCmd('acao');

        JRequest::setVar( 'view', 'ajax' );
        JRequest::setVar( 'layout', $tp );
        parent::display();
	}
}
