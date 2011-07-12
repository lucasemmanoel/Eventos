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

class P22eventosViewPalestras extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$idevento		= JRequest::getInt( 'idevento' );
		$nome			= 'palestra';
		$nome_plural	= 'palestras';
		$eventName		= $this->get('EventName');
		$titulo			= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( JText::_( $titulo ) , 'p22' . ucfirst( $nome_plural ) );

//		JToolBarHelper::publishList();
//		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList( 'Tem certeza que deseja excluir o(a) '. $nome .'?' );
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX( 'add' , 'Novo' );

		// Contexto em que o usuário se encontra.
		// Utilizado para guardar os dados escolhidos, no formulário, pelo usuário.
		$context = 'com_p22evento.'. $nome .'.list.';

		// Campo utilizado para ordenar os registros - default = name.
		// pp.name é pesquisado na tabela de usuários padrão (#__user)
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order'		, 'filter_order', 'p.nome', 'cmd' );
		// Ordem de exibição dos registros ( asc/desc ) - default = asc.
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir'	, 'filter_order_Dir', '', 'word' );

		// String utilizada para filtrar registros.
		$search				= $mainframe->getUserStateFromRequest( $context.'search', 'search', '', 'string' );

		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',			'filter_state', '',	'word' );
		$filter_nivel		= $mainframe->getUserStateFromRequest( $context.'filter_nivel',			'filter_nivel', '',	'word' );
		$filter_trilha		= $mainframe->getUserStateFromRequest( $context.'filter_trilha',		'filter_trilha', '', 'int' );
		$filter_palestrante	= $mainframe->getUserStateFromRequest( $context.'filter_palestrante',	'filter_palestrante', '',	'int' );

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

		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'p.published = 1';
			}
			elseif ( $filter_state == 'U' )
			{
				$where[] = 'p.published = 0';
			}
		}
		
		if( $filter_nivel == 'B' )
		{
			$where[] = 'p.nivel = 0';
		}
		elseif( $filter_nivel == 'I' )
		{
			$where[] = 'p.nivel = 1';
		}
		elseif( $filter_nivel == 'A' )
		{
			$where[] = 'p.nivel = 2';
		}

		if( $filter_trilha )
		{
			$where[] = 'p.id_trilha = ' . intval( $filter_trilha );
		}

		if( $filter_palestrante )
		{
			$where[] = 'p.id_palestrante = ' . intval( $filter_palestrante );
		}

		$where[]	= 'p.id_evento=' . intval( $idevento );
		$where[]	= 'p.tipo=0';

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
		$model =& $this->getModel();

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
		$lists[ 'search' ]	= $search;
		
		$lists['state']	= JHTML::_('grid.state',  $filter_state );
		
		$combo			= array();
		$combo[]		= JHTML::_( 'select.option',  '', '-Selecione Nível-' , 'id', 'nome' );
		$combo[]		= JHTML::_( 'select.option',  'B', 'Básico' , 'id', 'nome' );
		$combo[]		= JHTML::_( 'select.option',  'I', 'Intermediário' , 'id', 'nome' );
		$combo[]		= JHTML::_( 'select.option',  'A', 'Avançado' , 'id', 'nome' );
		$lists['nivel']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'filter_nivel', // name da select gerada
									'onchange="this.form.submit()"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$filter_nivel // valor default do select
								);

		$combo				= array();
		$combo[]			= JHTML::_( 'select.option',  '', '-Selecione trilha-' , 'id', 'nome' );
		$trilhas			= $this->get('Trilhas');
		$combo				= ( count( $trilhas ) ) ? array_merge( $combo , $trilhas ) : $combo;
		$lists['trilha']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'filter_trilha', // name da select gerada
									'onchange="this.form.submit()"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$filter_trilha // valor default do select
								);

		$combo					= array();
		$combo[]				= JHTML::_( 'select.option',  '', '-Selecione Palestrante-' , 'id', 'nome' );
		$palestrantes			= $this->get('Palestrantes');
		$combo					= ( count( $palestrantes ) ) ? array_merge( $combo , $palestrantes ) : $combo;
		$lists['palestrante']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'filter_palestrante', // name da select gerada
									'onchange="this.form.submit()"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$filter_palestrante // valor default do select
								);
		
		$model->setRegistersDates( $registros , array( 'registrado_em' ) , true );

		$this->lib = &$model->lib;

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