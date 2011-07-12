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

// Requisita o controller básico.
require_once( JPATH_COMPONENT.DS.'controller.php' );

// Requisita um controller específico caso seja solicitado.
if( $controller = JRequest::getWord( 'controller' ) )
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

	if (file_exists($path))
	{
		require_once $path;
	}
	else
	{
		$controller = '';
	}
}

// Cria o controller.
$classname	= 'P22eventosController'.$controller;
$controller	= new $classname();

// Executa a tarefa solicitada.
$controller->execute( JRequest::getVar( 'task' ) );

// Redireciona o usuário (caso definido pelo controller).
$controller->redirect();
