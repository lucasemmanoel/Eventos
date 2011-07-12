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

class P22eventosModelSalas extends JModel
{
	public $_totalRegistros;
	public $_registros = array();
	public $idevento;

	function __construct()
	{
		global $mainframe;

		parent::__construct();
		$this->idevento = JRequest::getInt( 'idevento' );

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}
	}

	public function getEventName()
	{
		return $this->lib->eventName();
	}

	function totalRegistros( $where )
	{	
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_totalRegistros ) )
		{
			$this->_totalRegistros = $this->lib->countDados( 'p22eventos_salas', $where );
		}
		return $this->_totalRegistros;
	}
	
	function registros( $where, $orderby, $pageNav )
	{
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			$this->_registros = $this->lib->getDados( 'p22eventos_salas' , 'p.*' , $where , $orderby , 'loadObjectList' , $pageNav );
		}
		return $this->_registros;
	}

	function getLocal()
	{
		$id_local = $this->lib->getDados(
										'p22eventos' ,
										'p.id_local' ,
										' WHERE p.id=' . intval( $this->idevento ) ,
										'' ,
										'loadResult'
									);
		return $id_local;
	}
}