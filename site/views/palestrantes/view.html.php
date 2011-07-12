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

class P22eventosViewPalestrantes extends JView
{
	function display( $tpl = null )
	{		
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$model		= &$this->getModel();
		$doc		= &JFactory::getDocument();
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$idevento	= JRequest::getInt( 'idevento' );

		$eventName	= $this->get('EventName');
		
		if( !$eventName )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&Itemid=' . JRequest::getInt('Itemid') , 'Evento não encontrado.' , 'notice' );
		}
		
		if( !$model->lib->isPublished( $idevento , 'palestrantes' ) )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&task=selevento&idevento='. intval( $idevento ) .'&Itemid=' . intval( JRequest::getInt('Itemid') ) );
		}

		$title		= JText::_( 'Evento' ) . ': <small style="color: #333;font-size:16px">' . $eventName . '</small>';

		// Pega registros.
		$registros  = &$this->get('Registros');
		
		// Prepara os dados para o template.
		$this->assignRef( 'title', $title );
		$this->assignRef( 'registros', $registros );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
