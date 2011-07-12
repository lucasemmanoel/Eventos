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

class P22eventosViewParticipante extends JView
{
	function display( $tpl = null )
	{
		// Funcionalidades globais do Joomla!
		global $mainframe;

		// Load the form validation behavior
		JHTML::_('behavior.formvalidation');

		$id_evento	= JRequest::getInt( 'idevento' );

		// Pega o registro a ser editado.
		$registro =& $this->get( 'Registro' );

		$doc	= JFactory::getDocument();
		$doc->addScript('components/com_p22evento/assets/js/ajax.js');
		$doc->addScript('administrator/components/com_p22evento/js/inscritos.js');
		$doc->addStyleSheet( 'components/com_p22evento/assets/css/com_p22eventos.css' );
		$doc->addStyleSheet( 'templates/system/css/system.css' );
		$doc->addStyleSheet( 'templates/system/css/general.css' );

		$combo					= array();
		$combo[]				= JHTML::_( 'select.option',  '', '-Informe a Profissão-' , 'id', 'nome' );
		$profissoes				= $this->get('Profissoes');
		$combo					= ( count( $profissoes ) ) ? array_merge( $combo , $profissoes ) : $combo;
		$select['profissoes']	= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'id_profissao', // name da select gerada
									'class="inputbox required"', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									$registro->id_profissao // valor default do select
								);

		$model			= &$this->getModel();
		$select['UF']	= $model->lib->getEstado( $registro->uf , 'class="inputbox required" onchange="carregaCidade( this.value , \'getCidades\' , \'td_cidades\' , \''. $registro->id_cidade .'\' )"' );

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