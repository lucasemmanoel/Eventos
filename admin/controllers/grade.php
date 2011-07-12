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

class P22eventosControllerGrade extends P22eventosController
{
	/*
	 * Constructor (registra tarefas adicionais ao método)
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'			, 'edit' );
		$this->registerTask( 'unpublish'	, 'publish');
		$this->registerTask( 'apply'		, 'save');
		$this->registerTask( 'save_continue', 'save');
	}
	
	/**
     * Exibe o formulário para adição/edição de dados.
     */
	function edit()
	{
		JRequest::setVar( 'view', 'grade' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		parent::display();
	}
	
	/**
	 * Salva um registro (e direciona para a página principal).
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model				= $this->getModel( 'grade' );
		$data				= JRequest::get( 'post' );
		$task				= $this->getTask();
		$data['id_evento']	= $data['idevento'];

		if ( $model->store( $data ) )
		{
			$msg = JText::_( 'Informações registradas' );
		}
		else
		{
			$msg = JText::_( 'Erro ao tentar registrar as informações: ' . $model->getError() );
			$msgType = 'error';
		}

		switch ( $task )
		{
			case 'apply':
				$link = 'index.php?option=com_p22evento&controller=grade&task=edit&idevento=' . intval( $data['idevento'] ) . '&idlocal='. intval( $data['id_local'] ) .'&cid[]=' . intval( $model->id );
				$this->setRedirect( $link, $msg , $msgType );
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_p22evento&task=grades&idevento=' . intval( $data['idevento'] );
				$this->setRedirect( $link, $msg , $msgType );
				break;
			case 'save_continue':
				$link = 'index.php?option=com_p22evento&controller=grade&task=add&idevento=' . intval( $data['idevento'] ) . '&idlocal='. intval( $data['id_local'] );
				$this->setRedirect( $link, $msg , $msgType );
				break;
		}
	}
	
	/**
	 * Remove registro(s).
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel( 'grade' );

		if( !$model->delete() )
		{
			$msg = JText::_( 'Erro: um ou mais registros não podem ser removidos' );
			$msgType = 'error';
		}
		else
		{
			$msg = JText::_( 'Registro(s) removido(s)' );
		}

		$idevento = JRequest::getInt( 'idevento' );

		$link = 'index.php?option=com_p22evento&task=grades&idevento=' . intval( $idevento );
		$this->setRedirect( $link, $msg , $msgType );
	}
	
	/**
	 * Cancela a edição de um registro.
	 */
	function cancel()
	{
		$msg		= JText::_( 'Operação cancelada' );
		$idevento	= JRequest::getInt( 'idevento' );
		$link		= 'index.php?option=com_p22evento&task=grades&idevento=' . intval( $idevento );
		$this->setRedirect( $link, $msg , 'notice' );
	}

	function publish( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db		= &JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		
		$publish = ( $this->getTask() == 'publish' ? 1 : 0 );
		$client  = JRequest::getWord( 'filter_client', 'site' );

		if (count( $cid ) < 1)
		{
			$action = $publish ? JText::_( 'publish' ) : JText::_( 'unpublish' );
			JError::raiseError(500, JText::_( 'Selecione um evento para '.$action ) );
		}

		$cids = implode( ',' , $cid );

		$query = 'UPDATE #__p22eventos_grades SET published = '.(int) $publish
		. ' WHERE id IN ( '.$cids.' )'
		;
		$db->setQuery( $query );

		if ( !$db->query() )
		{
			JError::raiseError(500, $db->getErrorMsg() );
		}

		$idevento = JRequest::getInt( 'idevento' );

		$link = 'index.php?option=com_p22evento&task=grades&idevento=' . intval( $idevento );
		$this->setRedirect( $link );
	}
}
