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

class P22eventosViewGradeprogramacao extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;
		
		if ( !JRequest::getCmd('tmpl') )
		{
			JRequest::setVar( 'tmpl' , 'component' );
		}

		JHTML::_('behavior.mootools');

		$doc = &JFactory::getDocument();
		$doc->addScript( JURI::base() . 'components/com_p22evento/assets/js/palestra_resumo_pop.js');
		$doc->addScript('./components/com_p22evento/assets/js/ajax.js');

		$eventName	= $this->get('EventName');
		$trilhas	= $this->get('Trilhas');
		$diasEvento	= $this->get('DiasEvento');
		$palestras	= $this->get('Palestras');
		$salas		= $this->get('Salas');

		$nivel		= array( 0 => 'Básico' , 1 => 'Intermediário' , 2 => 'Avançado' );
		$tipo		= array( 0 => 'Palestras' , 1 => 'Mini-cursos' );
		$showDesc	= $this->get('ShowDesc');

		$this->assignRef( 'eventName' , $eventName );
		$this->assignRef( 'trilhas' , $trilhas );
		$this->assignRef( 'diasEvento' , $diasEvento );
		$this->assignRef( 'palestras' , $palestras );
		$this->assignRef( 'salas' , $salas );
		$this->assignRef( 'nivel' , $nivel );
		$this->assignRef( 'tipo' , $tipo );
		$this->assignRef( 'showDesc' , $showDesc );

		// Carrega o template.
		parent::display( $tpl );
	}
}
