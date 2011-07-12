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

class P22eventosViewP22eventos extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;
		
		JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');

		$user		= &JFactory::getUser();
		$doc		= &JFactory::getDocument();
		$doc->addScript( 'includes/js/joomla.javascript.js' );
		$doc->addScript('components/com_p22evento/assets/js/ajax.js');
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );

		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$registro = $this->get( 'Registro' );

		$participacao_eventos	= $this->get( 'ParticipacaoEventos' );
		$eventos				= $this->get( 'Eventos' );
		$participacao			= $this->get( 'Participacao' );
		$inscritoEventos		= $this->get( 'InscritoEventos' );
		$palestras				= $this->get( 'Palestras' );

		$this->assignRef( 'user' , $user );
		$this->assignRef( 'registro' , $registro );
		$this->assignRef( 'participacao_eventos' , $participacao_eventos );
		$this->assignRef( 'palestras' , $palestras );
		$this->assignRef( 'eventos' , $eventos );
		$this->assignRef( 'participacao' , $participacao );
		$this->assignRef( 'inscritoEventos' , $inscritoEventos );

		// Carrega o template.
		parent::display( $tpl );
	}
}
