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

class P22eventosViewLocal extends JView
{
	function display( $tpl = null )
	{
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
		JToolBarHelper::title(   JText::_( 'Local' ).': <small><small>[ ' . $text.' ]</small></small>', 'p22Locais'  );
		
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
		JToolBarHelper::save();

		$idevento = JRequest::getInt( 'idevento' );

        // Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );        
		$this->assignRef( 'idevento', $idevento );

		// Carrega o template.
		parent::display( $tpl );
    }
}
