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

class P22eventosViewInscritos extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;
		
		$idevento		= JRequest::getInt( 'idevento' );
		$nome			= 'inscrito';
		$nome_plural	= 'inscritos';
		$eventName		= $this->get('EventName');
		$titulo			= JText::_( 'Evento' ) . ': <small style="color: #333">' . $eventName . '</small>';

		JToolBarHelper::title( JText::_( $titulo ) , 'p22' . ucfirst( $nome_plural ) );
		
		JToolBarHelper::deleteList( 'Tem certeza que deseja excluir o(a) '. $nome .'?' );
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX( 'add' , 'Novo' );

		// Contexto em que o usuário se encontra.
		// Utilizado para guardar os dados escolhidos, no formulário, pelo usuário.
		$context = 'com_p22evento.'. $nome .'.list.';

		// Campo utilizado para ordenar os registros - default = name.
		// pp.name é pesquisado na tabela de usuários padrão (#__user)
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order'		, 'filter_order', 'name', 'cmd' );
		// Ordem de exibição dos registros ( asc/desc ) - default = asc.
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir'	, 'filter_order_Dir', '', 'word' );

		// String utilizada para filtrar registros.
		$search	 = $mainframe->getUserStateFromRequest( $context.'search', 'search', '', 'string' );

		$filter_uf			= $mainframe->getUserStateFromRequest( $context.'filter_uf', 'filter_uf', '',	'word' );
		$filter_inscritos	= $mainframe->getUserStateFromRequest( $context.'filter_inscritos', 'filter_inscritos', 'I',	'word' );
		$filter_profissoes	= $mainframe->getUserStateFromRequest( $context.'filter_profiessoes', 'filter_profissoes', '',	'int' );
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',			'filter_state', '',	'word' );

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
			
			$where[] = 'LOWER( u.name ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}

		$where[]	= 'i.id_evento=' . intval( $idevento );

		switch( $filter_inscritos )
		{
			case 'I':
				$where[]	= 'i.tp_reg=0';
				break;
			case 'C':
				$where[]	= 'i.tp_reg=1';
				break;
			case 'P':
				$where[]	= 'i.tp_reg=2';
				break;
			default:
				break;
		}

		if ( $filter_uf )
		{
			$db =& JFactory::getDBO();

			$where[] = 'p.uf=' . $db->Quote( $filter_uf );
		}

		if ( $filter_profissoes )
		{
			$where[] = 'p.id_profissao=' . intval( $filter_profissoes );
		}

		if ( $filter_state )
		{
			if ( $filter_state == 'P' )
			{
				$where[] = 'i.published = 1';
			}
			elseif ( $filter_state == 'U' )
			{
				$where[] = 'i.published = 0';
			}
		}
		
		// Monta a cláusula where.
		if ( count( $where ) )
		{
			$where = implode( ' AND ', $where );
        }
		else
		{
			$where = '';
        }

		// Conecta-se com o model.
		$model =& $this->getModel();

		$model->getParticipantes( $where );

		// Pega número total de registros.
		$totalRegistros = & $model->totalRegistros();
		
		// Funcionalidades Joomla! para paginação de registros.
		jimport( 'joomla.html.pagination' );
		$pageNav = new JPagination( $totalRegistros, $limitstart, $limit );
		
		// Pega registros.
		$registros  = & $model->registros( $pageNav );
		
        // Ordenação dos registros.
		$lists[ 'order_Dir' ]	= $filter_order_Dir;
		$lists[ 'order' ]		= $filter_order;

		// String para filtrar registros.
		$lists[ 'search' ] = $search;

		$lists['state']	= JHTML::_('grid.state',  $filter_state , 'Confirmado' , 'Não-confirmado' );

		$lists['UF'] = $model->lib->getEstado( $filter_uf , 'onchange = "this.form.submit()"' , 'filter_uf' , '- UF -' );

		$combo						= array();
		$combo[]					= JHTML::_( 'select.option',  '', 'Todos' , 'id', 'nome' );
		$combo[]					= JHTML::_( 'select.option',  'I', 'Inscritos' , 'id', 'nome' );
		$combo[]					= JHTML::_( 'select.option',  'C', 'Colaboradores' , 'id', 'nome' );
		$combo[]					= JHTML::_( 'select.option',  'P', 'Palestrantes' , 'id', 'nome' );
		$lists['filter_inscritos']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'filter_inscritos', // name da select gerada
									'onchange="this.form.submit()"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$filter_inscritos // valor default do select
								);

		$combo						= array();
		$combo[]					= JHTML::_( 'select.option',  '', '-Profissões-' , 'id', 'nome' );
		$profissoes					= $this->get('Profissoes');
		$combo						= ( count( $profissoes ) ) ? array_merge( $combo , $profissoes ) : $combo;
		$lists['filter_profissoes']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'filter_profissoes', // name da select gerada
									'onchange="this.form.submit()"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$filter_profissoes // valor default do select
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