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

// Recursos Joomla! para trabalhar com models.
jimport( 'joomla.application.component.model' );

class P22eventosModelSelevento extends JModel
{
	private $_id;

	public $lib;

	public $idevento;

	function __construct()
	{
		parent::__construct();
		$this->_id = JRequest::getInt( 'idevento' );

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->idevento = JRequest::getInt('idevento');
	}

	function getEventName()
	{
		$query = 'SELECT DISTINCT p.nome'
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.id=' . intval( $this->_id )
		;
		$name = $this->lib->getRegistrosCustom( $query , 'loadResult' );
		
		return $name;
	}

	function &getRegistro()
	{
		$data = $this->lib->getDados( 'p22eventos' , 'p.inscricoes,p.colaboradores,p.palestras,p.minicursos,programacao,palestrantes,certificados' , ' WHERE p.id=' . intval( $this->idevento ) , '' , 'loadObject' );

		return $data;
	}
}