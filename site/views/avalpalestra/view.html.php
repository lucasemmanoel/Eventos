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

class P22eventosViewAvalpalestra extends JView
{
	function display( $tpl = null )
	{
		if ( !JRequest::getCmd('tmpl') )
		{
			JRequest::setVar( 'tmpl' , 'component' );
		}
		
		// Funcionalidades globais do Joomla!
		global $mainframe;

		// Load the form validation behavior
		JHTML::script('formvalidation.js', 'components/com_p22evento/assets/js/');

		$id_evento	= JRequest::getInt( 'idevento' );

		// Pega o registro a ser editado.
		$registro =& $this->get( 'Registro' );

		$doc	= JFactory::getDocument();

		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$model			= &$this->getModel();

		// Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );        
		$this->assignRef( 'idevento', $id_evento );

		// Carrega o template.
		parent::display( $tpl );
    }
}