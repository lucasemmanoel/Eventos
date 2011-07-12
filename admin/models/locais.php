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

class P22eventosModelLocais extends JModel
{
	// Array com o total de registros.
	var $_totalRegistros;
	var $_registros;
	
	function totalRegistros( $where )
	{		
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_totalRegistros ) )
		{
			$query = 'SELECT COUNT(id)'
			. ' FROM #__p22eventos_locais AS p'
			. $where
			;
			$this->_db->setQuery( $query );
			$this->_totalRegistros = $this->_db->loadResult();
			
			return $this->_totalRegistros;
		}
	}
	
	function registros( $where, $orderby, $pageNav )
	{	
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			// seleciona os registros para exibição.
			$query = 'SELECT p.*'
			. ' FROM #__p22eventos_locais AS p'
			. $where
			. $orderby
			;

			$this->_db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
			$this->_registros = $this->_db->loadObjectList();
			
			return $this->_registros;
		}
	}
}