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

class P22eventosViewAvaliacao extends JView
{
	function display( $tpl = null )
	{		
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$model	=& $this->getModel();
		$db		= &JFactory::getDBO();
		$doc	= &JFactory::getDocument();
		$doc->addScript('includes/js/joomla.javascript.js');
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$eventName	= $this->get('EventName');
		$title		= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		$model		= &$this->getModel();
		$this->lib	= $model->lib;
		$avaliacao	= $model->getAvaliacoes();

		if ( !count( $avaliacao ) ) $mainframe->close();

		$avaliacao	= $avaliacao[ $model->id ];

		$pontuacao		= $this->get( 'Pontuacao' );
		$graphColor		= $this->get( 'GraphColor' );
		$graphWidth		= $this->get( 'GraphWidth' );
		$palestraStatus	= $this->get( 'PalestraStatus' );
		$trilhas		= $this->get( 'Trilhas' );
		
		$this->assignRef( 'avaliacao'		, $avaliacao );
		$this->assignRef( 'pontuacao'		, $pontuacao );
		$this->assignRef( 'graphColor'		, $graphColor );
		$this->assignRef( 'graphWidth'		, $graphWidth );
		$this->assignRef( 'palestraStatus'	, $palestraStatus );
		$this->assignRef( 'num_avaliadores'	, $num_avaliadores );
		$this->assignRef( 'trilhas'			, $trilhas );
		$this->assignRef( 'title'			, $title );
		$this->assignRef( 'idevento'		, JRequest::getInt( 'idevento' ) );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
