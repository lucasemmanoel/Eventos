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

class P22eventosViewInscrito extends JView
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

		// Exibe o título da do formulário.
		JToolBarHelper::title(   JText::_( 'Inscrito' ).': <small><small>[ ' . $text.' ]</small></small>', 'p22Inscritos'  );
		
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
		$doc->addScript('components/com_p22evento/js/inscritos.js');

		if ( !$registro->id )
		{
			$combo				= array();
			$combo[]			= JHTML::_( 'select.option',  '', 'Informe o Usuário' , 'id', 'name' );
			$users				= $this->get('Users');
			$combo				= ( count( $users ) ) ? array_merge( $combo , $users ) : $combo;
			$select['users']	= JHTML::_(
										'select.genericlist', // Chamada o arquivo select, método genericlist
										$combo, // array de dados
										'id_user', // name da select gerada
										'onchange="loadUserData(this.value)"', // outros atributos html da tag
										'id', // nome do campo de valor do select
										'name', // nome do campo de texto do select
										'' // valor default do select
									);
		}

		$combo					= array();
		$combo[]				= JHTML::_( 'select.option',  '', '-Informe a Profissão-' , 'id', 'nome' );
		$profissoes				= $this->get('Profissoes');
		$combo					= ( count( $profissoes ) ) ? array_merge( $combo , $profissoes ) : $combo;
		$select['profissoes']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'id_profissao', // name da select gerada
									'', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$registro->id_profissao // valor default do select
								);

		$model			= &$this->getModel();
		$select['UF']	= $model->lib->getEstado( $registro->uf , 'onchange = "carregaCidade( this.value , \'getCidades\' , \'td_cidades\' , \''. $registro->id_cidade .'\' )"' );

		if( $registro->cpf )
		{
			$registro->cpf	= str_pad( $registro->cpf , 11 , "0" , STR_PAD_LEFT );
			$registro->cpf	= $model->lib->setMask( $registro->cpf , '999.999.999-99' );
		} else $registro->cpf = '';

		// Prepara os dados para o template.
		$this->assignRef( 'registro', $registro );        
		$this->assignRef( 'idevento', $id_evento );
		$this->assignRef( 'select', $select );

		// Carrega o template.
		parent::display( $tpl );
    }
}