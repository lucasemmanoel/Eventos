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

class P22eventosControllerAvalpalestra extends P22eventosController
{
	/*
	 * Constructor (registra tarefas adicionais ao método)
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add' , 'edit' );
	}
	
	/**
     * Exibe o formulário para adição/edição de dados.
     */
	function edit()
	{
		JRequest::setVar( 'view', 'participante' );
		JRequest::setVar( 'layout', 'form' );
		parent::display();
	}

	/**
	 * Salva um registro (e direciona para a página principal).
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model	= $this->getModel( 'avalpalestra' );
		$data	= JRequest::get( 'post' );
		
		$data['comentario']		= JRequest::getString( 'comentario' , '' , 'post' , JREQUEST_ALLOWRAW );
		
		if ( $model->store( $data ) )
		{
			$msg	= JText::_( 'Informações registradas' );
			$close	= '&close=1';
		}
		else
		{
			$msg		= JText::_( 'Erro ao tentar registrar as informações: ' . $model->getError() );
			$msgType	= 'error';
		}

		$link = 'index2.php?option=com_p22evento&task=avalpalestra&idevento='. intval( $data['id_evento'] ) .'&palestraid='. intval( $data['id_palestra'] ) .'&Itemid=' . intval( $data['Itemid'] ) . $close;
		$this->setRedirect( $link, $msg , $msgType );
	}
}