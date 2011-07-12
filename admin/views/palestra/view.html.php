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

class P22eventosViewPalestra extends JView
{
	function display( $tpl = null )
	{
		JHTML::_('behavior.modal');
		
		// Pega o registro a ser editado.
		$registro =& $this->get( 'Registro' );

		$id_evento	= JRequest::getInt( 'idevento' );

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
		JToolBarHelper::title(   JText::_( 'Palestra' ).': <small><small>[ ' . $text.' ]</small></small>', 'p22Palestra'  );
				
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
		$arraySelects = array( 'trilha' , 'palestrante' );

		for( $a = 0 ; $a < count( $arraySelects ) ; $a++ )
		{
			$combo							= array();
			$combo[]						= JHTML::_( 'select.option',  '', '-Selecione-' , 'id', 'nome' );
			$values							= $this->get( ucfirst( $arraySelects[ $a ] ) );
			$field							= 'id_' . $arraySelects[ $a ];
			$combo							= ( count( $values ) ) ? array_merge( $combo , $values ) : $combo;
			$select[ $arraySelects[ $a ] ]	= JHTML::_(
															'select.genericlist', // Chamada o arquivo select, método genericlist
															$combo, // array de dados
															'id_' . $arraySelects[ $a ], // name da select gerada
															'', // outros atributos html da tag
															'id', // nome do campo de valor do select
															'nome', // nome do campo de texto do select
															$registro->$field // valor default do select
														);
		}

        // Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );
		$this->assignRef( 'idevento', $id_evento );
		$this->assignRef( 'select', $select );

		// Carrega o template.
		parent::display( $tpl );
    }
}
