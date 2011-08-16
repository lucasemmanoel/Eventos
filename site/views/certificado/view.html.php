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

class P22eventosViewCertificado extends JView
{
	function display( $tpl = null )
	{		
		// Funcionalidades globais do Joomla!
		global $mainframe;

		if ( !JRequest::getCmd('tmpl') )
		{
			JRequest::setVar( 'tmpl' , 'component' );
		}

		$model	=& $this->getModel();
		$db		= &JFactory::getDBO();
		$doc	= &JFactory::getDocument();
		$doc->addScript('includes/js/joomla.javascript.js');
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$eventName	= $this->get('EventName');
		$title		= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		$registro	= &$this->get('Registro');
		$safe		= $this->get('CertificadoGerado');
		$palestra	= $this->get('Palestra');
		
		jimport( 'joomla.filesystem.file' );

		$img		= JPATH::clean( JPATH_SITE . DS . 'images'. DS .'stories'. DS  .'eventos'. DS . $registro->eventalias . DS .'certificados'. DS . $registro->image_name );
		$fileExists = JFile::exists( $img );
		
		if ( !$safe && $registro->eventalias && $registro->image_name && $fileExists )
		{
			$img			= JPATH::clean( JPATH_SITE . DS . 'images'. DS .'stories'. DS  .'eventos'. DS . $registro->eventalias . DS .'certificados'. DS . $registro->image_name );
			$modelAvatar	= &JModel::getInstance( 'avatar' , 'P22eventosModel' );
			$width			= $modelAvatar->getWidth( $img );
			$height			= $modelAvatar->getHeight( $img );
			$filepath		= JURI::root() . "images/stories/eventos/{$registro->eventalias}/certificados/{$registro->image_name}";
			$style			= "width:{$width}px;height:{$height}px;border:1px solid #CCC;position:relative;z-index:0;";
			$style2			= "position:absolute;z-index:0;";
			$style3			= "position:absolute;z-index:0;width:{$width}px;height:{$height}px";
			
			switch( $registro->tp_reg )
			{
				case 1:
					$tipo = 'colaborador';
					$view = 'colaboradores';
					break;
				case 2:
					$tipo	= 'palestrante';
					$view	= 'palestrantes';
					break;
				default:
					$tipo = 'participante';
					$view = 'inscritos';
					break;
			}

			$periodo		= $model->lib->getEventDetailString( $registro->id_evento );
			$text			= ( !$view ) ? 'texto_inscritos' : 'texto_' . $view;
			$maintext		= $registro->$text;
			
			preg_match( "/[COLOR]?(([a-fA-F0-9]){3}){1,2}/" , $maintext , $matches );
			if ( count( $matches ) )
			{
				$color		= $matches[0];
				$maintext	= str_replace( "[COLOR={$color}]" , "<font style='color:#{$color}'>" , $maintext );
				$maintext	= str_replace( "[/COLOR]" , '</font>' , $maintext );
			}

			$maintext		= str_replace( '[[NOME]]' , $registro->nome , $maintext );
			$maintext		= str_replace( '[[PERIODO_EVENTO]]' , $periodo , $maintext );
			$maintext		= str_replace( '[[TIPO]]' , $tipo , $maintext );
			$maintext		= str_replace( '[[EVENTO]]' , $eventName , $maintext );
			$maintext		= str_replace( '[[PALESTRA]]' , $palestra , $maintext );
			$maintext		= str_replace( '[B]' , '<strong>' , $maintext );
			$maintext		= str_replace( '[/B]' , '</strong>' , $maintext );
			$maintext		= str_replace( '[I]' , '<i>' , $maintext );
			$maintext		= str_replace( '[/I]' , '</i>' , $maintext );
			$maintext		= str_replace( '[S]' , '<u>' , $maintext );
			$maintext		= str_replace( '[/S]' , '</u>' , $maintext );
		}
		
		$this->assignRef( 'title' , $title );
		$this->assignRef( 'registro' , $registro );
		$this->assignRef( 'idevento' , JRequest::getInt( 'idevento' ) );
		$this->assignRef( 'maintext', $maintext );
		$this->assignRef( 'safe' , $safe );
		$this->assignRef( 'style' , $style );
		$this->assignRef( 'style2' , $style2 );
		$this->assignRef( 'style3' , $style3 );
		$this->assignRef( 'filepath', $filepath );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
