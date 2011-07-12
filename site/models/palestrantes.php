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

class P22eventosModelPalestrantes extends JModel
{
	// Array com o total de registros.
	private $_registros;
	public $id;
	public $lib;
	public $idevento;

	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		parent::__construct();

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->idevento = JRequest::getInt('idevento');

		$user = &JFactory::getUser();

		if( !$user->guest )
		{
			$this->id = $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );
		}
	}
	
	function getRegistros()
	{
		// Somente carrega os dados caso ainda não tenha sido feito antes.
		if ( empty( $this->_registros ) )
		{
			// seleciona os registros para exibição.
			$query = 'SELECT i.registrado_em, p.id AS id_palestrante, p.curriculo, p.uf, p.avatar_img, u.name AS nome, pp.nome AS profissao, c.nome AS cidade'
			. ' FROM #__p22eventos_inscritos AS i'
			. ' INNER JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
			. ' INNER JOIN #__users AS u ON u.id = p.id_user'
			. ' INNER JOIN #__p22eventos_profissoes AS pp ON pp.id = p.id_profissao'
			. ' INNER JOIN #__p22cidades AS c ON c.id = p.id_cidade'
			. ' WHERE i.id_evento=' . intval( $this->idevento ) . ' AND i.tp_reg=2 AND i.published=1'
			. ' ORDER BY nome'
			;
			$this->_registros = $this->lib->getRegistrosCustom( $query );
		}
		return $this->_registros;
	}

	function listaAno()
	{
		// Somente carrega a lista de países caso ainda não tenha sido feito antes.
		if ( empty( $this->_listaCidade ) )
		{
			// Seleciona a lista de países.
			$query = 'SELECT DISTINCT p.ano'
			. ' FROM #__p22eventos AS p'
			. ' ORDER BY ano ASC'
			;

			$this->_db->setQuery( $query );
			$this->_listaAno = $this->_db->loadObjectList();

			return $this->_listaAno;
        }
	}

	function getEventName()
	{
		$query = 'SELECT DISTINCT p.nome'
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.published=1 AND p.id=' . intval( $this->idevento )
		;
		$name = $this->lib->getRegistrosCustom( $query , 'loadResult' );

		return $name;
	}
}
