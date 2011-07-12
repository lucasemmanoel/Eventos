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

class P22eventosViewGrade extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		$id_evento	= JRequest::getInt( 'idevento' );

		// Pega o registro a ser editado.
		$registro =& $this->get( 'Registro' );

		// Determina se é adição ou edição de registro.
		$isNew = ( $registro->id < 1);
		
		// Define o texto a ser exibido.
		if ( $isNew )
		{
			$text = JText::_( 'Novo registro' );
        }
		else
		{
			$text = JText::_( 'Edição' );
        }
		
		// Trava o menu principal.
		JRequest::setVar( 'hidemainmenu', 1 );
		
		// Exibe o título da do formulário.
		JToolBarHelper::title(   JText::_( 'Grade' ).': <small><small>[ ' . $text.' ]</small></small>', 'p22Grade'  );
		
		// Botão para fechar ou cancelar a edição.
		if ( $isNew )
		{
			JToolBarHelper::cancel();
		}
		else
		{
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		// Botão para salvar o registro.
		JToolBarHelper::apply();
		JToolBarHelper::save( 'save_continue' , 'Continuar');
		JToolBarHelper::save();

		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/js/ajax.js');
		$doc->addScript('components/com_p22evento/js/grade.js');
	
		$tp					= ( $registro->id ) ? true : false;
		$tipo				= ( $registro->tipo ) ? 'M' : 'P';
		$tipo				= ( $registro->id ) ? $tipo : '';
		$combo				= array();
		$combo[]			= JHTML::_( 'select.option',  '', '-Selecione-' , 'id', 'name' );
		$combo[]			= JHTML::_( 'select.option',  'P', 'Palestra' , 'id', 'name' );
		$combo[]			= JHTML::_( 'select.option',  'M', 'Mini-curso' , 'id', 'name' );
		$combo				= ( count( $dias ) ) ? array_merge( $combo , $dias ) : $combo;
		$select['tipos']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'tipo', // name da select gerada
									'onchange="habilitaDesabilitaDia(this.value)"',
									'id', // nome do campo de valor do select
									'name', // nome do campo de texto do select
									$tipo // valor default do select
								);
		
		$combo				= array();
		$combo[]			= JHTML::_( 'select.option',  '', '-Selecione-' , 'id', 'name' );
		$dias				= $this->get('Dias');
		$combo				= ( count( $dias ) ) ? array_merge( $combo , $dias ) : $combo;
		$disabled			= ( !$tipo ) ? 'disabled="true"' : '';
		$select['dias'	]	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'dia', // name da select gerada
									$disabled . ' onchange="chkInfosGrade(\''. $tp .'\',\'dias\',document.getElementById(\'tipo\').value,\''. $registro->id_palestra .'\',\''. $registro->id_sala .'\',\''. $registro->hora .'\')"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'name', // nome do campo de texto do select
									$registro->dia // valor default do select
								);

		$select['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $registro->published );

        // Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );        
		$this->assignRef( 'idevento', $id_evento );
		$this->assignRef( 'select', $select );
		$this->assignRef( 'tipo_palestra', $tipo_palestra );
		

		// Carrega o template.
		parent::display( $tpl );
    }
}
