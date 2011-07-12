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

class P22eventosViewInscricao extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		// Load the form validation behavior
		JHTML::_('behavior.formvalidation');

		$id_evento	= JRequest::getInt( 'idevento' );

		$menu		=& JSite::getMenu();
		$model		= &$this->getModel();
		$item		= $menu->getActive();
		$eventName	= $model->lib->eventName();
		
		// Pega o registro a ser editado.
		$registro =& $this->get( 'Registro' );

		if( !$model->lib->isPublished( $id_evento , 'inscricoes' ) )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&task=selevento&idevento='. intval( $id_evento ) .'&Itemid=' . intval( JRequest::getInt('Itemid') ) );
		}
		
		$pathway	= &$mainframe->getPathway();
		$user		= &JFactory::getUser();
		$doc		= &JFactory::getDocument();
		$doc->addScript('./components/com_p22evento/assets/js/ajax.js');
		$doc->addScript('./administrator/components/com_p22evento/js/inscritos.js');
		$doc->addScript('./includes/js/joomla.javascript.js');
		$doc->addStyleSheet( './components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( './templates/system/css/system.css' );
		$doc->addStyleSheet( './templates/system/css/general.css' );

		$combo					= array();
		$combo[]				= JHTML::_( 'select.option',  '', '-Informe a Profissão-' , 'id', 'nome' );
		$profissoes				= $this->get('Profissoes');
		$combo					= ( count( $profissoes ) ) ? array_merge( $combo , $profissoes ) : $combo;
		$select['profissoes']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'id_profissao', // name da select gerada
									'disabled="true" class="inputbox required" style="background-color:#DDD"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$registro->id_profissao // valor default do select
								);

		$pathway->addItem( JText::_( $eventName ) , 'index.php?option=com_p22evento&task=selevento&idevento=1&Itemid=' . intval( $item->id ) );
		$pathway->addItem( 'Inscrição' );

		$select['UF']	= $model->lib->getEstado( $registro->uf , 'disabled="true" class="inputbox required" style="background-color:#DDD" onchange = "carregaCidade( this.value , \'getCidades\' , \'td_cidades\' , \''. $registro->id_cidade .'\' )"' );

		if( $registro->cpf )
		{
			$registro->cpf	= str_pad( $registro->cpf , 11 , "0" , STR_PAD_LEFT );
			$registro->cpf	= $model->lib->setMask( $registro->cpf , '999.999.999-99' );
		} else $registro->cpf = '';

		$eventName = $model->lib->eventName();

		if( !$eventName )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&Itemid=' . JRequest::getInt('Itemid') , 'Evento não encontrado.' , 'notice' );
		}

		$title		= JText::_( 'Evento' ) . ': <small style="color: #333;font-size:16px">' . $eventName . '</small>';

		$item   = $menu->getActive();
		if($item)
			$params	=& $menu->getParams($item->id);
		else
			$params	=& $menu->getParams(null);

		// Set some default page parameters if not set
		$params->def( 'show_page_title', 				1 );
		if (!$params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Login' ));
			}
		if(!$item)
		{
			$params->def( 'header_login', 			'' );
			$params->def( 'header_logout', 			'' );
		}

		$params->def( 'pageclass_sfx', 			'' );
		$params->def( 'login', 					'index.php' );
		$params->def( 'logout', 				'index.php' );
		$params->def( 'description_login', 		1 );
		$params->def( 'description_logout', 		1 );
		$params->def( 'description_login_text', 	JText::_( 'LOGIN_DESCRIPTION' ) );
		$params->def( 'description_logout_text',	JText::_( 'LOGOUT_DESCRIPTION' ) );

		// Get the return URL
		if (!$url = JRequest::getVar('return', '', 'method', 'base64')) {
			$url = base64_encode($params->get($type));
		}

		$this->assign('return', $url);

		$this->assignRef('params', $params);


		// Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );
		$this->assignRef( 'idevento', $id_evento );
		$this->assignRef( 'user', $user );
		$this->assignRef( 'select', $select );
		$this->assignRef( 'title', $title );
		$this->assignRef( 'eventName', $eventName );
		$this->assignRef( 'item', $item->id );

		// Carrega o template.
		parent::display( $tpl );
    }
}