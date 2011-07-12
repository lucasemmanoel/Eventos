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

defined( '_JEXEC' ) or die;

class JElementEvento extends JElement {
	function fetchElement($name, $value, &$node, $control_name)
	{
		$published	= intval( $node->attributes( 'published' ) );
		$where		= ( $published ) ? ' WHERE published=' . $published : '';
		
		$db			= &JFactory::getDBO();
		$query		= 'SELECT id , nome FROM #__p22eventos '. $where .' ORDER BY nome DESC ';
		$db->setQuery( $query );
		$eventos	= $db->loadObjectList();
		
		if( !count( $eventos ) ) return 'Não há eventos cadastradas.';

		$options	= array();
		
		$options[]	= JHTML::_('select.option' , '' , ' - Selecione - ' );

		for( $a = 0 ; $a < count( $eventos ) ; $a++ )
		{
			$row		= &$eventos[ $a ];
			$options[]	= JHTML::_('select.option' , $row->id , $row->nome );
		}

		$select			= JHTML::_(
								'select.genericlist',
								$options ,
								'urlparams['. $name .']', 'class="inputbox"', 'value', 'text' , $value
						);

		return $select;
	}
}