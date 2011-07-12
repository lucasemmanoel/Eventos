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

class P22eventosViewGrades extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;
		
		$evento	= $this->get('EventName');
		$title		= JText::_( 'Evento' ) . ': <small style="color: #333">' . $evento->nome . '</small>';

		JToolBarHelper::title( $title , 'p22Grade' );
		JToolBarHelper::customX( 'check_table', 'preview', 'preview_f2', 'Ver Grade' );
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList( 'Tem certeza que deseja excluir este horário?' );
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX( 'add' , 'Novo' );

		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/js/ajax.js');
		$doc->addScript('components/com_p22evento/js/grade.js');

		// Contexto em que o usuário se encontra.
		// Utilizado para guardar os dados escolhidos, no formulário, pelo usuário.
		$context = 'com_p22evento.grades.list.';

		// Campo utilizado para ordenar os registros - default = name.
		// pp.name é pesquisado na tabela de usuários padrão (#__user)
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order'		, 'filter_order', 's.nome', 'cmd' );
		// Ordem de exibição dos registros ( asc/desc ) - default = asc.
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir'	, 'filter_order_Dir', '', 'word' );

		$filter_tipo		= $mainframe->getUserStateFromRequest( $context.'filter_tipo',		'filter_tipo', 'P',	'word' );
		$where_tipo			= ( $filter_tipo == 'M' ) ? 1 : 0;

		// Constrói o ORDER BY conforme filtros de ordenação.
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;

		// Conecta-se com o model.
		$model =& $this->getModel();

		// Pega registros.
		$registros  = & $model->registros( $where_tipo, $orderby );

		$num_reg = $model->num_reg;

        // Ordenação dos registros.
		$lists[ 'order_Dir' ]	= $filter_order_Dir;
		$lists[ 'order' ]		= $filter_order;

		// Prepara os dados para o template.
		$this->assignRef( 'limitstart', $limitstart );
		$this->assignRef( 'registros', $registros );
		$this->assignRef( 'num_reg', $num_reg );
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'idevento'			, JRequest::getInt( 'idevento' ) );
		$this->assignRef( 'show_descriptions'	, $evento->show_palestra_description );
		$this->assignRef( 'mesma_palestra'		, $evento->mesma_palestra );
		$this->assignRef( 'filter_tipo'			, $filter_tipo );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}