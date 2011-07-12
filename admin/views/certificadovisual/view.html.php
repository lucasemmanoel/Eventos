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

class P22eventosViewCertificadovisual extends JView
{
	function display( $tpl = null )
	{
		JToolBarHelper::title( JText::_( $titulo ) , 'p22Certificados' );

		$model		= &JModel::getInstance( 'certificados' , 'P22eventosModel' );
		$view		= JRequest::getCmd( 'viewcertificado' );

		$idevento	= JRequest::getInt( 'idevento' );
		$eventName	= $model->getEventName();
		$titulo		= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( $titulo , 'p22Certificados' );
		$registro	= $model->getRegistro();

		JToolBarHelper::custom( 'apply_certificado', 'apply', 'apply_f2', 'Apply' , false );

		jimport( 'joomla.filesystem.file' );

		$img		= JPATH::clean( JPATH_SITE . DS . 'images'. DS .'stories'. DS  .'eventos'. DS . $registro->eventalias . DS .'certificados'. DS . $registro->image_name );
		$fileExists = JFile::exists( $img );
		
		if ( $registro->eventalias && $registro->image_name && $fileExists )
		{
			$modelAvatar	= &JModel::getInstance( 'avatar' , 'P22eventosModel' );
			$width			= $modelAvatar->getWidth( $img );
			$height			= $modelAvatar->getHeight( $img );
			$filepath		= JURI::root() . "images/stories/eventos/{$registro->eventalias}/certificados/{$registro->image_name}";
			$style			= "width:{$width}px;height:{$height}px;border:1px solid #CCC;background-image: url( '". $filepath ."' )";

			$periodo		= $model->lib->getEventDetailString( $model->idevento );
			$text			= ( !$view ) ? 'texto_inscritos' : 'texto_' . $view;
			$maintext		= $registro->$text;

			switch( $view )
			{
				case 'colaboradores':
					$tipo = 'colaborador';
					break;
				case 'palestrantes':
					$tipo = 'palestrante';
					break;
				case 'inscritos':
				default:
					$tipo = 'participante';
					break;
			}
			
			$maintext		= str_replace( '[[NOME]]' , 'Fulano da Silva Júnior' , $maintext );
			$maintext		= str_replace( '[[PERIODO_EVENTO]]' , $periodo , $maintext );
			$maintext		= str_replace( '[[TIPO]]' , $tipo , $maintext );
			$maintext		= str_replace( '[[EVENTO]]' , $eventName , $maintext );
			$maintext		= str_replace( '[[PALESTRA]]' , 'Palestra Boa Demais! v10.2' , $maintext );
			$maintext		= str_replace( '[B]' , '<strong>' , $maintext );
			$maintext		= str_replace( '[/B]' , '</strong>' , $maintext );
			$maintext		= str_replace( '[I]' , '<i>' , $maintext );
			$maintext		= str_replace( '[/I]' , '</i>' , $maintext );
			$maintext		= str_replace( '[S]' , '<u>' , $maintext );
			$maintext		= str_replace( '[/S]' , '</u>' , $maintext );

			preg_match( "/[COLOR]?(([a-fA-F0-9]){3}){1,2}/" , $maintext , $matches );
			
			if ( count( $matches ) )
			{
				$color		= $matches[0];
				$maintext	= str_replace( "[COLOR={$color}]" , "<font style='color:#{$color}'>" , $maintext );
				$maintext	= str_replace( "[/COLOR]" , '</font>' , $maintext );
			}

			// Prepara os dados para o template.
			$this->assignRef( 'idevento', $idevento );
			$this->assignRef( 'registro', $registro );
			$this->assignRef( 'maintext', $maintext );
			$this->assignRef( 'view', $view );
			$this->assignRef( 'style', $style );
			$this->assignRef( 'filepath', $filepath );

			// Carrega o template.
			parent::display( $tpl );
		}
    }
}