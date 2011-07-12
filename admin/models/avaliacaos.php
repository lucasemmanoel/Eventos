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

class P22eventosModelAvaliacaos extends JModel
{
	public $lib;
	public $idevento;
	public $pontuacaoFinal	= array();
	private $_pontuacao		= array();
	private $_avaliadores	= array();
	private $_palestras		= array();
	private $_graphColor	= array();
	private $_trilhas		= array();
	
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
		$query = 'SELECT DISTINCT p.nome'
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.id=' . intval( $this->idevento )
		;
		$name = $this->lib->getRegistrosCustom( $query , 'loadResult' );

		return $name;
	}

	function getAvaliadores()
	{
		$query = 'SELECT p.id,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS nome'
		. ' FROM #__p22eventos_avaliadores AS a'
		. ' LEFT JOIN #__p22eventos_participantes AS p ON p.id = a.id_participante'
		. ' WHERE a.id_evento=' . intval( $this->idevento )
		. ' ORDER BY nome'
		;
		$avaliadores = $this->lib->getRegistrosCustom( $query , 'loadObjectList' );

		for( $b = 0 ; $b < count( $avaliadores ) ; $b++ )
		{
			$row					=& $avaliadores[ $b ];
			$this->_avaliadores[]	= $row->id;
		}
		
		return $avaliadores;
	}
	
	function getColaboradores()
	{
		$where = ' WHERE i.tp_reg=1 AND i.id_evento=' . intval( $this->idevento );

		$where .= ( count( $this->_avaliadores ) ) ? ' AND p.id NOT IN ('. implode( ',' , $this->_avaliadores ) .')' : '';

		$query = 'SELECT p.id,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS nome'
		. ' FROM #__p22eventos_inscritos AS i'
		. ' LEFT JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
		. $where
		. ' ORDER BY nome'
		;
		$colaboradores = $this->lib->getRegistrosCustom( $query );

		return $colaboradores;
	}

	function getAvaliacoes( $where = null )
	{
		$query = 'SELECT a.id_palestra, a.id_avaliador, a.confianca, a.relevancia, a.qualidade_tecnica, a.experiencia, a.recomendacao, a.comentario, a.registrado_em,'
		. ' p.nome AS palestra, p.aprovado AS status_palestra, p.id_trilha, p.nivel, t.nome AS trilha, p1.id AS id_participante,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p1.id_user ) AS palestrante,'
		. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p2.id_user ) AS avaliador'
		. ' FROM #__p22eventos_avaliacao AS a'
		. ' INNER JOIN #__p22eventos_palestras AS p ON p.id = a.id_palestra'
		. ' INNER JOIN #__p22eventos_participantes AS p1 ON p1.id = p.id_palestrante'
		. ' INNER JOIN #__p22eventos_participantes AS p2 ON p2.id = a.id_avaliador'
		. ' INNER JOIN #__p22eventos_trilhas AS t ON t.id = p.id_trilha'
		. ' WHERE a.id_evento=' . intval( $this->idevento )
		. ' AND p.id_evento=' . intval( $this->idevento )
		. ' AND t.id_evento=' . intval( $this->idevento )
		. $where
		. ' ORDER BY a.registrado_em'
		;
		$result = $this->lib->getRegistrosCustom( $query );
		
		$avaliacoes		= array();
		$arrayDetails	= array();
		for( $a = 0 ; $a < count( $result ) ; $a++ )
		{
			$obj		= new stdClass();
			$objTrilha	= new stdClass();
			$row		= &$result[ $a ];

			if ( !in_array( $row->id_avaliador , $this->_avaliadores ) ) continue;

			$obj->palestra		= $row->palestra;
			$obj->palestrante	= $row->palestrante;
			$obj->nivel			= $row->nivel;
			$obj->trilha		= $row->trilha;
			$obj->published		= $row->status_palestra;
			$obj->registrado_em	= $row->registrado_em;

			$details					= new stdClass();
			$details->confianca			= $row->confianca;
			$details->relevancia		= $row->relevancia;
			$details->qualidade_tecnica	= $row->qualidade_tecnica;
			$details->experiencia		= $row->experiencia;
			$details->recomendacao		= $row->recomendacao;
			$details->avaliador			= $row->avaliador;
			$details->registrado_em		= $row->registrado_em;
			$details->comentario		= $row->comentario;
			$arrayDetails[]				= $details;
			$obj->details				= $arrayDetails;

			$avaliacoes[ $row->id_palestra ]	= $obj;
			$this->_palestras[]					= $row->id_palestra;
			
			$this->_pontuacao[ $row->id_palestra ][] = self::_avaliacaoLegenda( 'confianca'		, $row->confianca );
			$this->_pontuacao[ $row->id_palestra ][] = self::_avaliacaoLegenda( 'relevancia'		, $row->relevancia );
			$this->_pontuacao[ $row->id_palestra ][] = self::_avaliacaoLegenda( 'qualidade_tecnica', $row->qualidade_tecnica );
			$this->_pontuacao[ $row->id_palestra ][] = self::_avaliacaoLegenda( 'experiencia'		, $row->experiencia );
			$this->_pontuacao[ $row->id_palestra ][] = self::_avaliacaoLegenda( 'recomendacao'		, $row->recomendacao );

			$objTrilha->id				= $row->id_trilha;
			$objTrilha->nome			= $row->trilha;
			$this->_trilhas['obj'][]	= $objTrilha;
			$this->_trilhas['array'][]	= $row->id_trilha;
		}
		
		return $avaliacoes;
	}

	public function getTrilhas()
	{
		if ( !count( $this->_trilhas ) ) return;

		// Excluir itens repetidos
		$this->_trilhas['array'] = array_unique( $this->_trilhas['array'] );
		sort( $this->_trilhas['array'] );

		$chkLoopTrilhas = array();
		$newObj			= array();
		if( count( $this->_trilhas['array'] ) )
		{
			foreach( $this->_trilhas['array'] AS $id_trilha )
			{
				for( $a = 0 ; $a < count( $this->_trilhas['obj'] ) ; $a++ )
				{
					$row = &$this->_trilhas['obj'][ $a ];

					if( $row->id == $id_trilha && !in_array( $row->id , $chkLoopTrilhas ) )
					{
						$newObj[] = $row;
						$chkLoopTrilhas[] = intval( $row->id );
					}
				}
			}
		}
		$this->_trilhas['obj'] = $newObj;
		
		return $this->_trilhas['obj'];
	}

	private function _avaliacaoLegenda( $tp , $value )
	{
		$values['confianca'] = array(
									'G' => 5,
									'C' => 15,
									'E' => 25
								);

		$values['relevancia'] = array(
									1 => 0,
									2 => 3,
									3 => 8,
									4 => 12,
									5 => 15
								);

		$values['qualidade_tecnica'] = array(
											1 => 0,
											2 => 3,
											3 => 10,
											4 => 15,
											5 => 20
										);

		$values['experiencia'] = $values['qualidade_tecnica'];

		$values['recomendacao'] = array(
			'R' => 0,
			'r' => 10,
			'a' => 15,
			'A' => 20,
		);

		$values['resumo'] = 5;

		return $values[ $tp ][ $value ];
	}

	public function getPontuacao()
	{
		$num_avaliadores = count( $this->_avaliadores );

		if( count( $this->_palestras ) )
		{
			foreach( $this->_palestras AS $id_palestra )
			{
				if( count( $this->_pontuacao[ $id_palestra ] ) )
				{
					$this->pontuacaoFinal[ $id_palestra ] = floor( array_sum( $this->_pontuacao[ $id_palestra ] ) / $num_avaliadores );
				}
			}
		}
		
		return $this->pontuacaoFinal;
	}

	public function getGraphColor()
	{
		$colors = array(
			0 => '#E20000',
			1 => '#E20000',
			2 => '#E20000',
			3 => '#E20000',
			4 => '#E20000',
			5 => '#E20000',
			6 => '#E20000',
			7 => '#E20000',
			8 => '#E20000',
			9 => '#E20000',
			10 => '#E20000',
			11 => '#ED6E00',
			12 => '#ED6E00',
			13 => '#ED6E00',
			15 => '#ED6E00',
			16 => '#ED6E00',
			17 => '#EA9000',
			18 => '#EA9000',
			19 => '#EA9000',
			20 => '#EA9000',
			21 => '#E5A800',
			22 => '#E5A800',
			23 => '#E5A800',
			24 => '#E5A800',
			25 => '#E5A800',
			26 => '#E5A800',
			27 => '#E5A800',
			28 => '#E5A800',
			29 => '#E5A800',
			30 => '#E5A800',
			31 => '#E5C304',
			32 => '#E5C304',
			33 => '#E5C304',
			34 => '#E5C304',
			35 => '#E5C304',
			36 => '#E5C304',
			37 => '#E5C304',
			38 => '#E5C304',
			39 => '#E5C304',
			40 => '#E5C304',
			41 => '#DDDA0B',
			42 => '#DDDA0B',
			43 => '#DDDA0B',
			44 => '#DDDA0B',
			45 => '#DDDA0B',
			46 => '#DDDA0B',
			47 => '#DDDA0B',
			48 => '#DDDA0B',
			49 => '#DDDA0B',
			50 => '#DDDA0B',
			51 => '#EAE01C',
			52 => '#EAE01C',
			53 => '#EAE01C',
			54 => '#EAE01C',
			55 => '#EAE01C',
			56 => '#EAE01C',
			57 => '#EAE01C',
			58 => '#EAE01C',
			59 => '#EAE01C',
			60 => '#EAE01C',
			61 => '#DBDB04',
			62 => '#DBDB04',
			63 => '#DBDB04',
			64 => '#DBDB04',
			65 => '#DBDB04',
			66 => '#DBDB04',
			67 => '#DBDB04',
			68 => '#DBDB04',
			69 => '#DBDB04',
			70 => '#CAD10C',
			71 => '#CAD10C',
			72 => '#CAD10C',
			73 => '#CAD10C',
			74 => '#CAD10C',
			75 => '#CAD10C',
			76 => '#CAD10C',
			77 => '#CAD10C',
			78 => '#CAD10C',
			79 => '#CAD10C',
			80 => '#CAD10C',
			81 => '#A1C904',
			82 => '#A1C904',
			83 => '#A1C904',
			84 => '#A1C904',
			85 => '#A1C904',
			86 => '#A1C904',
			87 => '#A1C904',
			88 => '#A1C904',
			89 => '#A1C904',
			90 => '#A1C904',
			91 => '#008000',
			92 => '#008000',
			93 => '#008000',
			94 => '#008000',
			95 => '#008000',
			96 => '#008000',
			97 => '#008000',
			98 => '#008000',
			99 => '#008000',
			100 => '#008000',
		);

		if( count( $this->pontuacaoFinal ) )
		{
			foreach( $this->pontuacaoFinal AS $id_palestra => $pontos )
			{
				$this->_graphColor[ $id_palestra ] = $colors[ $pontos ];
			}
		}

		return $this->_graphColor;
	}

	public function getPalestraStatus()
	{
		$status = array();
		
		if( count( $this->pontuacaoFinal ) )
		{
			foreach( $this->pontuacaoFinal AS $id_palestra => $pontos )
			{
				if( $pontos == 0 && $pontos <= 20 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Reprovado</span>';
				}
				elseif( $pontos > 20 && $pontos <= 40 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Pontuação Baixa</span>';
				}
				elseif( $pontos > 40 && $pontos <= 50 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Avaliação Regular. Critério do Bom Senso</span>';
				}
				elseif( $pontos > 50 && $pontos <= 60 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Aprovação sob Critério do Bom Senso.</span>';
				}
				elseif( $pontos > 60 && $pontos <= 80 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Possível aprovação. Critério do Bom Senso.</span>';
				}
				elseif( $pontos > 80 && $pontos <= 90 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Aconselhável aprovação. Muito bom!</span>';
				}
				elseif( $pontos > 90 && $pontos <= 100 )
				{
					$status[ $id_palestra ] = '<span style="color: '. $this->_graphColor[ $id_palestra ] .'">Aprovação Totalmente Aceitável.</span>';
				}
			}
		}

		return $status;
	}

	function getGraphWidth()
	{
		$totalWidth = 500;

		$width = array();
		if( count( $this->pontuacaoFinal ) )
		{
			foreach( $this->pontuacaoFinal AS $id_palestra => $pontos )
			{
				$width[ $id_palestra ] = floor( ( $pontos * 450 ) / 100 );
			}
		}
		return $width;
	}
}
