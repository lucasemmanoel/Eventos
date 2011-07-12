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

class P22eventosViewTrilhas extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$idevento		= JRequest::getInt( 'idevento' );
		$nome			= 'trilha';
		$nome_plural	= 'trilhas';
		$eventName		= $this->get('EventName');

		// Contexto em que o usuário se encontra.
		// Utilizado para guardar os dados escolhidos, no formulário, pelo usuário.
		$context = 'com_p22evento.'. $nome .'.list.';

		// Campo utilizado para ordenar os registros - default = name.
		// pp.name é pesquisado na tabela de usuários padrão (#__user)
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order'		, 'filter_order', 'p.nome', 'cmd' );
		// Ordem de exibição dos registros ( asc/desc ) - default = asc.
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir'	, 'filter_order_Dir', '', 'word' );

		// String utilizada para filtrar registros.
		$search	 = $mainframe->getUserStateFromRequest( $context.'search', 'search', '', 'string' );

		// Número de registros a serem exibidos.
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg( 'list_limit' ), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

		// Array para construção da cláusula where.
		$where = array();

		// Cláusula where caso exista uma string filtrando registros.
		// O filtro será aplicado ao campo name.
		if ( $search )
		{
			// Conexão com a base de dados.
			$db =& JFactory::getDBO();

			$where[] = 'LOWER( p.nome ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where[]	= 'p.id_evento=' . intval( $idevento );

		// Monta a cláusula where.
		if ( count( $where ) )
		{
			$where = ' WHERE ' . implode( ' AND ', $where );
        }
		else
		{
			$where = '';
        }

		// Constrói o ORDER BY conforme filtros de ordenação.
		$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir;

		// Conecta-se com o model.
		$model	= &$this->getModel();
		$doc	= &JFactory::getDocument();

		$doc->addStyleSheet( 'templates/khepri/css/icon.css' );
		$doc->addStyleSheet( 'templates/khepri/css/rounded.css' );

		// Pega número total de registros.
		$totalRegistros = & $model->totalRegistros( $where );

		// Funcionalidades Joomla! para paginação de registros.
		jimport( 'joomla.html.pagination' );
		$pageNav = new JPagination( $totalRegistros, $limitstart, $limit );

		// Pega registros.
		$registros  = & $model->registros( $where, $orderby, $pageNav );

        // Ordenação dos registros.
		$lists[ 'order_Dir' ]	= $filter_order_Dir;
		$lists[ 'order' ]		= $filter_order;

		// String para filtrar registros.
		$lists[ 'search' ] = $search;

		$model->setRegistersDates( $registros , array( 'registrado_em' ) , true );

		$doc	=& JFactory::getDocument(  );
		$doc->addScript( 'components/com_p22evento/js/ajax.js' );

		// Prepara os dados para o template.
		$this->assignRef( 'registros', $registros );
		$this->assignRef( 'lists', $lists );
		$this->assignRef( 'pageNav', $pageNav );
		$this->assignRef( 'idevento', $idevento );
		$this->assignRef( 'nome', $nome );
		$this->assignRef( 'nome_plural', $nome_plural );
		
		// Carrega o template.
		parent::display( $tpl );
    }
}
