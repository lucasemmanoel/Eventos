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

class P22eventosViewSelevento extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;

		$idevento	= JRequest::getInt( 'idevento' );

		$eventName	= $this->get('EventName');

		if( !$eventName )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&Itemid=' . JRequest::getInt('Itemid') , 'Evento não encontrado.' , 'notice' );
		}

		$title		= JText::_( 'Evento' ) . ': <small style="color: #333;font-size:16px">' . $eventName . '</small>';

		//Load pane behavior
		jimport('joomla.html.pane');
		
		$pane		= &JPane::getInstance('sliders');
		$pathway	= &$mainframe->getPathway();
		$doc		= &JFactory::getDocument();
		$user		= &JFactory::getUser();
		$doc->addScript('includes/js/joomla.javascript.js');
//		$doc->addScript('components/com_p22evento/assets/js/pane.js');
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

//		$doc->addScriptDeclaration( $js );

		$pathway->addItem( $eventName );

		$icon = new stdClass();

		// Se inscrito:
		$model				= &$this->getModel();
		$modelInc			= &JModel::getInstance( 'inscricao' , 'P22eventosModel' );
		$inscricao			= $modelInc->getRegistro();
		$icon->inscricao	= ( $model->lib->isPublished( $idevento , 'inscricoes' ) && !$inscricao->inscricao ) ? true : false;
		$icon->colaborador	= ( $model->lib->isPublished( $idevento , 'colaboradores' ) && !$model->seColaborador() ) ? true : false;
		$icon->palestras	= ( $model->lib->isPublished( $idevento , 'palestras' ) || $model->lib->isPublished( $idevento , 'minicursos' ) ) ? true : false;
		$icon->palestrantes	= ( $model->lib->isPublished( $idevento , 'palestrantes' ) ) ? true : false;
		$icon->programacao	= ( $model->lib->isPublished( $idevento , 'programacao' ) ) ? true : false;
		$icon->avalidor		= ( $model->seAvaliador ) ? true : false;
		$icon->certificados	= ( $model->lib->isPublished( $idevento , 'certificados' ) ) ? true : false;
		
		$this->assignRef( 'pane', $pane );
		$this->assignRef( 'idevento', $idevento );
		$this->assignRef( 'title', $title );
		$this->assignRef( 'icon', $icon );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
