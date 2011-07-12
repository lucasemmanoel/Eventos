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

class P22eventosModelGrade extends JModel
{
	public $id;
	public $idevento;
	
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		global $mainframe;
		
		parent::__construct();

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->idevento = JRequest::getInt( 'idevento' );
		$this->lib		= new P22Eventos();
		$this->lib->setDBO( $this->_db );

		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}

		$array = JRequest::getVar( 'cid',  0, '', 'array' );
		$this->setId( ( int )$array[ 0 ] );
	}
	
	function setId( $id )
	{
		// Define o ID e a variável dos dados.
		$this->id		= $id;
		$this->_data	= null;
	}
	
	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$query = ' SELECT p.* , pp.tipo'
			. ' FROM #__p22eventos_grades AS p '
			. ' INNER JOIN #__p22eventos_palestras AS pp ON pp.id = p.id_palestra'
			. ' WHERE p.id_evento='. intval( $this->idevento ) .' AND p.id = ' . intval( $this->id );
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		
		return $this->_data;
	}
	
	function store( $data )
	{	
		$row =& $this->getTable( 'grades' );

		// Bind the form fields to the hello table
		if ( !$row->bind( $data ) )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Make sure the hello record is valid
		if ( !$row->check() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Store the web link table to the database
		if ( !$row->store() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		$this->id = $row->id;

		return true;
	}
	
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable( 'grades' );

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function getPalestras( $tipo = 0 , $id_palestra = null )
	{
		$query = 'SELECT p.id, p.nome,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = pp.id_user ) AS palestrante'
		. ' FROM #__p22eventos_palestras AS p'
		. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
		. ' WHERE p.published=1 AND p.aprovado=1 AND p.tipo= '. intval( $tipo ) .' AND p.id_evento=' . intval( $this->idevento )
		. ' ORDER BY p.nome'
		;
		$reg_palestras	= $this->lib->getRegistrosCustom( $query );
		
		$evento			= $this->lib->getDados( 'p22eventos' , 'p.data_inicio, p.data_fim, p.mesma_palestra' , ' WHERE p.id=' . intval( $this->idevento ) , '' , 'loadObject' );
		$mesma_palestra = intval( $evento->mesma_palestra );
		
		if ( !$mesma_palestra )
		{
			$query = 'SELECT g.id_palestra , g.dia'
			. ' FROM #__p22eventos_grades AS g'
			. ' WHERE g.id_evento=' . intval( $this->idevento )
			;
			$regs = $this->lib->getRegistrosCustom( $query , 'loadObjectList' );

			$palestrasRegs = array();

			for( $e = 0 ; $e < count( $regs ) ; $e++ )
			{
				$row = &$regs[ $e ];

				$palestrasRegs[ $row->id_palestra ] = $row->dia;
			}
		}

		$palestras = array();
		for( $d = 0 ; $d < count( $reg_palestras ) ; $d++ )
		{
			$row			= &$reg_palestras[ $d ];

			if ( !$mesma_palestra && count( $palestrasRegs ) )
			{
				if ( array_key_exists( $row->id , $palestrasRegs ) && $id_palestra != $row->id ) continue;
			}

			$obj			= new stdClass();
			$obj->id		= intval( $row->id );
			$obj->name		= $row->nome . ' - ' . $row->palestrante;
			$palestras[]	= $obj;
		}
		
		return $palestras;
	}
	
	function getSalas()
	{
		$reg_salas	= $this->lib->getDados( 'p22eventos_salas' , 'p.id , p.nome' , ' WHERE p.published=1 AND p.id_evento=' . intval( $this->idevento ) , ' ORDER BY p.nome' , 'loadObjectList' );
		
		$salas = array();
		for( $d = 0 ; $d < count( $reg_salas ) ; $d++ )
		{
			$row		= &$reg_salas[ $d ];
			$obj		= new stdClass();
			$obj->id	= intval( $row->id );
			$obj->name	= $row->nome;
			$salas[]	= $obj;
		}
		return $salas;
	}

	function getDias()
	{
		$evento		= $this->lib->getDados( 'p22eventos' , 'p.data_inicio, p.data_fim' , ' WHERE p.id=' . intval( $this->idevento ) , '' , 'loadObject' );
		$arrayDias	= $this->_listaDias( $evento->data_inicio , $evento->data_fim );

		$dias		= array();

		if( count( $arrayDias ) )
		{
			foreach( $arrayDias AS $dia )
			{
				$obj		= new stdClass();
				$obj->id	= $dia;
				$obj->name	= implode( '/' , array_reverse( explode( '-' , $dia ) ) );
				$dias[]		= $obj;
			}
		}

		return $dias;
	}

	private function _listaDias( $dataInicial, $dataFim )
	{
		$expDI = explode( '-' , $dataInicial );
		$expDF = explode( '-' , $dataFim );

		$dini = mktime(0,0,0,$expDI[1],$expDI[2],$expDI[0]); // timestamp da data inicial
		$dfim = mktime(0,0,0,$expDF[1],$expDF[2],$expDF[0]); // timestamp da data final

		$listaDias = array();
		while($dini <= $dfim) //enquanto uma data for inferior a outra
		{
			$dt				= date("Y-m-d",$dini); //convertendo a data no formato dia/mes/ano
			$expDTP			= explode( '-' , $dt );
			$dtParam		= mktime(0,0,0,$expDTP[1],$expDTP[2],$expDTP[0]);
			$listaDias[]	= $dt;
			$dini			+= 86400; // adicionando mais 1 dia (em segundos) na data inicial
		}
		return $listaDias;
	}

	function getHoras( $dia = null , $sala = null , $value = null , $palestra = null , $palestrante = null )
	{
		if( $dia &&  $sala )
		{
			$salaHora = $this->_getHoraPorSala( $dia , $sala );
		}

		if( $palestra )
		{
			$palestraHora = $this->_getHoraPorPalestra( $palestra );
			
			if ( count( $salaHora ) && count( $palestraHora ) )
			{
				$salaHora = array_merge( $salaHora , $palestraHora );
			}
			elseif ( !count( $salaHora ) && count( $palestraHora ) )
			{
				$salaHora = $palestraHora;
			}
		}

		if( $palestrante )
		{
			$palestranteHora = $this->_getHoraPorPalestrante( $palestrante );

			if ( count( $salaHora ) && count( $palestranteHora ) )
			{
				$salaHora = array_merge( $salaHora , $palestranteHora );
			}
			elseif ( !count( $salaHora ) && count( $palestranteHora ) )
			{
				$salaHora = $palestranteHora;
			}
		}
		
		$horas		= array();
		$edit		= ( $value ) ? true : false;
		$expValue	= ( $value ) ? explode( ':' , $value ) : '';
		$value		= ( count( $expValue ) ) ? $expValue[ 0 ] : '';
		
		for( $y = 7 ; $y <= 23 ; $y++ )
		{
			$horaReg	= str_pad( $y , 2, "0", STR_PAD_LEFT );
			$minutes	= true;
			
			if ( count( $salaHora ) )
			{
				if ( !$edit )
				{
					if( in_array( $horaReg , $salaHora ) ) $minutes = false;
				}
				else
				{
					if( in_array( $horaReg , $salaHora ) && $horaReg != $value ) $minutes = false;
				}
			}

			if ( $minutes )
			{
				$xNum = ( $y == 23 ) ? 0 : 5;

				for( $x = 0 ; $x <= $xNum ; $x++ )
				{
					$hora	= $horaReg . ':' . $x . '0';
					$valueH	= $hora . ':00';

					if( is_array( $salaHora ) )
					{
						if( count( $salaHora ) )
						{
							if( in_array( $valueH , $salaHora ) && !$edit ) continue;
						}
					}

					$obj		= new stdClass();
					$obj->id	= $valueH;
					$obj->name	= $hora;
					$horas[]	= $obj;
				}
			}
		}
		return $horas;
	}

	private function _getHoraPorSala( $dia , $sala , $palestra = null )
	{
		$where		= array();
		$where[]	= 'p.id_evento='. intval( $this->idevento );
		$where[]	= 'p.dia="' . $dia . '"';
		$where[]	= 'p.id_sala=' . intval( $sala );

		$where		= implode( ' AND ' , $where );
		
		$arrayHoras	= $this->lib->getDados( 'p22eventos_grades' , 'p.hora' , ' WHERE ' . $where  , '' , 'loadResultArray' );

		$horas = array();
		foreach( $arrayHoras AS $hora )
		{
			$expHora = explode( ':' , $hora );
			$horas[] = $expHora[ 0 ];
		}

		return $horas;
	}

	private function _getHoraPorPalestra( $palestra )
	{
		$where		= array();
		$where[]	= 'p.id_evento='. intval( $this->idevento );
		$where[]	= 'p.id_palestra=' . intval( $palestra );
		$where		= implode( ' AND ' , $where );

		$arrayHoras	= $this->lib->getDados( 'p22eventos_grades' , 'p.hora' , ' WHERE ' . $where  , '' , 'loadResultArray' );

		$horas = array();
		foreach( $arrayHoras AS $hora )
		{
			$expHora = explode( ':' , $hora );
			$horas[] = $expHora[ 0 ];
		}

		return $horas;
	}

	private function _getHoraPorPalestrante( $palestrante )
	{
		$where		= array();
		$where[]	= 'p.id_evento='. intval( $this->idevento );
		$where[]	= 'pp.id=' . intval( $palestrante );
		$where		= implode( ' AND ' , $where );

		$query		= 'SELECT g.hora'
		. ' FROM #__p22eventos_grades AS g'
		. ' INNER JOIN #__p22eventos_palestras AS p ON p.id = g.id_palestra'
		. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
		. ' WHERE '. $where
		;

		$arrayHoras	= $this->lib->getRegistrosCustom( $query , 'loadResultArray' );

		$horas = array();
		foreach( $arrayHoras AS $hora )
		{
			$expHora = explode( ':' , $hora );
			$horas[] = $expHora[ 0 ];
		}
		
		return $horas;
	}

	public function getIDPalestrante( $palestra )
	{
		$palestrante = $this->lib->getDados( 'p22eventos_palestras' , 'p.id_palestrante' , ' WHERE p.id='.intval( $palestra ) , '' , 'loadResult' );
		return $palestrante;
	}
}