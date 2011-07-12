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

class P22eventosModelGrades extends JModel
{
	public $lib;
	public $idevento;
	public $num_reg;
	
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

	function getEventName()
	{
		$query = 'SELECT DISTINCT p.nome , p.show_palestra_description , p.mesma_palestra '
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.id=' . intval( $this->idevento )
		;
		$evento = $this->lib->getRegistrosCustom( $query , 'loadObject' );

		return $evento;
	}

	function registros( $tipo , $orderby )
	{		
		$query = 'SELECT g.id AS grade_id , g.dia, g.hora, g.id_sala, g.published,'
		. ' p.nome AS palestra, t.nome AS trilha, p.nivel,'
		. ' s.nome AS sala,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = pp.id_user ) AS palestrante'
		. ' FROM #__p22eventos_grades AS g'
		. ' INNER JOIN #__p22eventos_palestras AS p ON p.id = g.id_palestra'
		. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
		. ' INNER JOIN #__p22eventos_inscritos AS i ON i.id_participante = pp.id'
		. ' INNER JOIN #__p22eventos_trilhas AS t ON t.id = p.id_trilha'
		. ' INNER JOIN #__p22eventos_salas AS s ON s.id = g.id_sala'
		. ' WHERE g.id_evento=' . intval( $this->idevento )
		. ' AND p.published=1 AND p.id_evento=' . intval( $this->idevento ) . ' AND p.aprovado=1'
		. ' AND i.published=1 AND i.tp_reg=2 AND i.id_evento=' . intval( $this->idevento )
		. ' AND t.published=1 AND t.id_evento=' . intval( $this->idevento )
		. ' AND s.published=1 AND s.id_evento=' . intval( $this->idevento )
		. ' AND p.tipo=' . intval( $tipo )
		. $orderby
		;
		$results = $this->lib->getRegistrosCustom( $query );

		$salas		= array();
		$horas		= array();
		for( $a = 0 ; $a < count( $results ) ; $a++ )
		{
			$row	= &$results[ $a ];
			$time	= strtotime( $row->hora );

			$obj							= new stdClass();
			$obj->id						= $row->id_sala;
			$obj->nome						= $row->sala;
			$salas[ 'reg' ][ $row->dia ][]	= $obj;

			$obj				= new stdClass();
			$obj->id			= intval( $row->grade_id );
			$obj->hora			= $time;
			$obj->palestra		= $row->palestra;
			$obj->palestrante	= $row->palestrante;
			$obj->trilha		= $row->trilha;
			$obj->nivel			= $row->nivel;
			$obj->published		= intval( $row->published );

			$salas['data'][ $row->id_sala ][]	= $time;
			$horas[ $row->id_sala ][ $time ]	= $obj;
			$this->num_reg++;
		}
		
		// Colocando os horários em ordem crescente
		if( !count( $salas['data'] ) ) return;

		foreach( $salas['data'] AS $id_sala => $sala )
		{
			sort( $sala );
			$salas['data'][ $id_sala ] = $sala;
		}
		
		if( !count( $salas['data'] ) ) return;

		$newSalas = array();
		foreach( $salas['data'] AS $id_sala => $stringtime )
		{
			if( count( $stringtime ) )
			{
				foreach( $stringtime AS $key2 => $time )
				{
					$newSalas[ $id_sala ][] = $horas[ $id_sala ][ $time ];
				}
			}
		}
		$salas['data'] = $newSalas;

		return $salas;
	}
}
