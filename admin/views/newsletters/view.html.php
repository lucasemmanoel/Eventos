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

class P22eventosViewNewsletters extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;
		
		$idevento		= JRequest::getInt( 'idevento' );
		$nome			= 'inscrito';
		$nome_plural	= 'inscritos';
		$eventName		= $this->get('EventName');
		$titulo			= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( JText::_( $titulo ) , 'massmail' );

		JToolBarHelper::custom( 'send' , 'send' , 'send_f2' , 'Enviar' , false );
		
		// Conexão com a base de dados.
		$db			= &JFactory::getDBO();
		$user		= &JFactory::getUser();
		$idevento	= JRequest::getInt('idevento');
		
		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}

		if ( !$user->authorize( 'com_massmail', 'manage' ) )
		{
			$mainframe->enqueueMessage( JText::_('Você não tem acesso ao sistema de e-mails.') , 'notice' );
		}

		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/js/ajax.js');
		$doc->addScript('components/com_p22evento/js/inscritos.js');

		$query = 'SELECT DISTINCT p.uf'
		. ' FROM #__p22eventos_participantes AS p'
		. ' INNER JOIN #__p22eventos_inscritos AS i ON i.id_participante = p.id'
		. ' WHERE i.id_evento=' . intval( $idevento ). ' AND i.published=1'
		;
		$estados	= $this->lib->getRegistrosCustom( $query , 'loadResultArray' );
		$estados	= array_filter( $estados );
		sort( $estados );

		$options		= array();
		$options[]		= JHTML::_( 'select.option',  '', '- Todos os Estados -' );

		if ( count( $estados ) )
		{
			foreach( $estados AS $uf )
			{
				$options[] = JHTML::_( 'select.option',  $uf , $uf );
			}
		}

		$select['UF']	= JHTML::_(
							'select.genericlist', // Chamada o arquivo select, método genericlist
							$options, // array de dados
							'uf',
							'style="width:130px" onchange="carregaCidade( this.value , \'getCidadesNewsletters\' , \'td_cidades\' , \'\' )"'
						);

		$this->assignRef( 'select', $select );
		$this->assignRef( 'listaCidades', $listaCidades );
		$this->assignRef( 'idevento', $idevento );

		
		// Carrega o template.
		parent::display( $tpl );
    }
}
