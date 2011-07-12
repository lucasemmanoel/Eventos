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

// Recursos Joomla! para trabalhar com views.
jimport( 'joomla.application.component.view' );

class P22eventosViewConfirmacaoinscritos extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib	= new P22Eventos();
		$db			= &JFactory::getDBO();
		$this->lib->setDBO( $db );
		
		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}

		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/js/ajax.js');
		$doc->addScript('components/com_p22evento/js/confirmacaoinscritos.js');

		$idevento		= JRequest::getInt( 'idevento' );
		$eventName		= $this->lib->getDados( 'p22eventos' , 'p.nome' , ' WHERE p.id='.intval( $idevento ) , '' , 'loadResult' );
		$titulo			= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( JText::_( $titulo ) , 'p22Inscritos' );
	
		// Prepara os dados para o template.		
		$this->assignRef( 'idevento', $idevento );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}