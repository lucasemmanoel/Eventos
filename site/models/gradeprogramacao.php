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

class P22eventosModelGradeprogramacao extends JModel
{
	public $id;
	public $lib;
	public $idevento;
	public $evento;
	private $_salas = array();
	
	
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		parent::__construct();

		global $mainframe;
		
		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->idevento = JRequest::getInt('idevento');

		if( !$this->lib->isPublished( $this->idevento , 'programacao' ) )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento&task=selevento&idevento='. intval( $this->idevento ) .'&Itemid=' . intval( JRequest::getInt('Itemid') ) );
		}
	}
	
	public function getEventName()
	{
		$this->evento = $this->lib->getDados( 'p22eventos' , 'p.*' , ' WHERE p.id=' . intval( $this->idevento ) , '' , 'loadObject' );
		return $this->evento->nome;
	}

	public function getTrilhas()
	{
		$query = 'SELECT DISTINCT p.id_trilha'
		. ' FROM #__p22eventos_grades AS g'
		. ' INNER JOIN #__p22eventos_palestras AS p ON p.id = g.id_palestra'
		. ' WHERE p.published=1 AND p.aprovado=1 AND p.id_evento=' . intval( $this->idevento )
		;
		$reg_trilhas = $this->lib->getRegistrosCustom( $query , 'loadResultArray' );

		if( !count( $reg_trilhas ) ) return;

		$trilhas = $this->lib->getDados( 'p22eventos_trilhas' , 'p.id,p.nome,p.cor' , ' WHERE p.id IN (' . implode( ',' , $reg_trilhas ) . ') AND p.id_evento=' . intval( $this->idevento ) , 'ORDER BY p.nome' , 'loadObjectList' );

		return $trilhas;
	}

	public function getDiasEvento()
	{
		$dini = strtotime( $this->evento->data_inicio );
		$dfim = strtotime( $this->evento->data_fim );

		$dias = array();

		while($dini <= $dfim)
		{
			$obj = new stdClass();

			$obj->id	= date( "Y-m-d" , $dini );
			$obj->nome	= date( "d/m/Y" , $dini ); //convertendo a data no formato dia/mes/ano
			$dias[]		= $obj;

			$dini += 86400; // adicionando mais 1 dia (em segundos) na data inicial
		}
		
		return $dias;
	}


	function listaDias( $dataInicial, $dataFim )
	{
		$expDI = explode( '-' , $dataInicial );
		$expDF = explode( '-' , $dataFim );

		$dini = mktime(0,0,0,$expDI[1],$expDI[2],$expDI[0]); // timestamp da data inicial
		$dfim = mktime(0,0,0,$expDF[1],$expDF[2],$expDF[0]); // timestamp da data final

		$this->_listaDias = array();
		while($dini <= $dfim)//enquanto uma data for inferior a outra
		{
			$dt						= date("d/m/Y",$dini); //convertendo a data no formato dia/mes/ano
			$expDTP					= explode( '/' , $dt );
			$dtParam				= mktime(0,0,0,$expDTP[1],$expDTP[0],$expDTP[2]);
			$hoje					= mktime(0,0,0,date('m') ,date('d') ,date('Y'));

			//Pegar as datas somente a partir da data atual.
			if( $hoje <= $dtParam )
			{
				$this->_listaDias[] = $dt;
			}

			$dini				   += 86400; // adicionando mais 1 dia (em segundos) na data inicial
		}
		return $this->_listaDias;
	}

	public function getPalestras()
	{
		$query = 'SELECT g.id AS id_grade, g.dia, g.hora , g.id_sala, s.nome AS sala ,'
		. ' p.id, p.nome AS palestra, p.tipo, p.nivel,'
		. ' ( SELECT u.name FROM '. $this->_db->nameQuote('#__users') .' AS u WHERE u.id = pp.id_user ) AS palestrante,'
		. ' t.id AS id_trilha, t.nome AS trilha, t.cor'
		. ' FROM ' . $this->_db->nameQuote( '#__p22eventos_grades' ) . ' AS g'
		. ' INNER JOIN ' . $this->_db->nameQuote('#__p22eventos_salas') . ' AS s ON s.id = g.id_sala'
		. ' INNER JOIN ' . $this->_db->nameQuote('#__p22eventos_palestras') . ' AS p ON p.id = g.id_palestra'
		. ' INNER JOIN ' . $this->_db->nameQuote('#__p22eventos_participantes') . ' AS pp ON pp.id = p.id_palestrante'
		. ' INNER JOIN ' . $this->_db->nameQuote('#__p22eventos_trilhas') . ' AS t ON t.id = p.id_trilha'
		. ' WHERE g.published=1 AND g.id_evento=' . intval( $this->idevento )
		. ' ORDER BY g.hora'
		;
		$grade = $this->lib->getRegistrosCustom( $query );

		$palestras = array();

		for( $j = 0 ; $j < count( $grade ) ; $j++ )
		{
			$row = $grade[ $j ];

			$obj = new stdClass();

			$obj->id			= $row->id;
			$obj->id_grade		= $row->id_grade;
			$obj->nome			= $row->palestra;
			$obj->palestrante	= $row->palestrante;
			$obj->nivel			= $row->nivel;
			$obj->id_trilha		= $row->id_trilha;
			$obj->trilha		= $row->trilha;
			$obj->cor			= $row->cor;
			$obj->tipo			= $row->tipo;

			$this->_salas[ $row->id_sala ] = $row->sala;

			$palestras[ $row->tipo ][ $row->dia ][ $row->hora ][ $row->id_sala ] = $obj;
		}

		return $palestras;
	}

	public function getSalas()
	{
		return $this->_salas;
	}

	public function getShowDesc()
	{
		$show = $this->lib->isPublished( $this->idevento , 'show_palestra_description' );
		
		return $show;
	}
}