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

class P22eventosViewAvaliacaos extends JView
{
	function display( $tpl = null )
	{
		$eventName	= $this->get('EventName');
		$title		= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( $title , 'p22Avaliacao' );

		$select	= array();
		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/js/ajax.js');
		$doc->addScript('components/com_p22evento/js/avaliacao.js');

		$avaliadores	= $this->get('Avaliadores');
		$colaboradores	= $this->get('Colaboradores');
		
		$select['colaboradores']	= JHTML::_(
										'select.genericlist', // Chamada o arquivo select, método genericlist
										$colaboradores, // array de dados
										'colaboradores', // name da select gerada
										'size="20" multiple style="width:430px"', // outros atributos html da tag
										'id', // nome do campo de valor do select
										'nome', // nome do campo de texto do select
										'' // valor default do select
									);

		$select['avaliadores']	= JHTML::_(
										'select.genericlist', // Chamada o arquivo select, método genericlist
										$avaliadores, // array de dados
										'avaliadores[]', // name da select gerada
										'size="20" multiple style="width:430px"', // outros atributos html da tag
										'id', // nome do campo de valor do select
										'nome', // nome do campo de texto do select
										'' // valor default do select
									);
		
		$num_avaliadores = count( $avaliadores );

		global $mainframe;

		$context		= 'com_p22evento.avaliacao.list.';
		$filter_trilha	= $mainframe->getUserStateFromRequest( $context.'filter_trilha','filter_trilha', '','int' );

		if ( $filter_trilha )
		{
			$where = ' AND p.id_trilha=' . intval( $filter_trilha );
		}

		$model		= &$this->getModel();
		$avaliacoes = $model->getAvaliacoes( $where );

		$this->lib	= $model->lib;

		$pontuacao		= $this->get( 'Pontuacao' );
		$graphColor		= $this->get( 'GraphColor' );
		$graphWidth		= $this->get( 'GraphWidth' );
		$palestraStatus	= $this->get( 'PalestraStatus' );
		$trilhas		= $this->get( 'Trilhas' );

		$this->assignRef( 'avaliacoes'		, $avaliacoes );
		$this->assignRef( 'filter_trilha'	, $filter_trilha );
		$this->assignRef( 'pontuacao'		, $pontuacao );
		$this->assignRef( 'graphColor'		, $graphColor );
		$this->assignRef( 'graphWidth'		, $graphWidth );
		$this->assignRef( 'palestraStatus'	, $palestraStatus );
		$this->assignRef( 'select'			, $select );
		$this->assignRef( 'num_avaliadores'	, $num_avaliadores );
		$this->assignRef( 'trilhas'			, $trilhas );
		$this->assignRef( 'idevento'		, JRequest::getInt( 'idevento' ) );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
