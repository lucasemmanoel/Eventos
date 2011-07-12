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

class TablePalestrantes extends JTable
{
	var $id				= null;
	var $id_evento		= null;
	var $id_user		= null;
	var $avatar_img		= null;
	var $curriculo		= null;
	var $published		= null;
	var $registrado_em	= null;
	var $cpf			= null;
	var $uf				= null;
	var $id_cidade		= null;
	var $id_profissao	= null;
	
	function __construct( & $db )
	{
		parent::__construct( '#__p22eventos_palestrantes', 'id', $db );
	}
}
