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

class P22eventosViewCertificados extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$registro		= $this->get( 'Registro' );
		$idevento		= JRequest::getInt( 'idevento' );
		$eventName		= $this->get('EventName');
		$titulo			= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( JText::_( $titulo ) , 'p22Certificados' );

		JToolBarHelper::apply();
		
		$eventDetailString	= $this->get( 'EventDetailString' );
		$pathimagefile		= $registro->image_name;
		$pathname_evento	= $this->get( 'TreatedEventName' );
		
		if ( !is_dir(  "../images/stories/eventos/" ) )
		{
			$mainframe->enqueueMessage( 'Diretório inexistente. Você deve criar o diretório "eventos" em '. JURI::root( true ) .'/images/stories/" .' , 'notice' );
		}
		elseif ( !is_dir( "../images/stories/eventos/{$pathname_evento}/" ) )
		{
			$mainframe->enqueueMessage( 'Diretório inexistente. Você deve criar o diretório "'.$pathname_evento.'" em '. JURI::root( true ) .'/images/stories/eventos/" .' , 'notice' );
		}
		elseif ( !is_dir( "../images/stories/eventos/{$pathname_evento}/certificados/" ) )
		{
			$mainframe->enqueueMessage( 'Diretório inexistente. Você deve criar o diretório "certificados" em '. JURI::root( true ) .'/images/stories/eventos/'. $pathname_evento .'/ .' , 'notice' );
		}
		elseif ( !is_file( "../images/stories/eventos/{$pathname_evento}/certificados/{$pathimagefile}" ) )
		{
			$mainframe->enqueueMessage( 'A imagem "'. $pathimagefile .'" é inexistente. Você deve criar inserir uma imagem em '. JURI::root( true ) .'/images/stories/eventos/'. $pathname_evento. '/certificados/ .' , 'notice' );
		}
		$pathimages	= JURI::root( true ) . "/images/stories/eventos/{$pathname_evento}/certificados/";

		// Prepara os dados para o template.		
		$this->assignRef( 'idevento', $idevento );
		$this->assignRef( 'registro', $registro );
		$this->assignRef( 'eventName', $eventName );
		$this->assignRef( 'eventDetailString', $eventDetailString );
		$this->assignRef( 'pathimages', $pathimages );
		$this->assignRef( 'pathimagefile', $pathimagefile );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}