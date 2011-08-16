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

class P22eventosModelP22eventos extends JModel
{
	public $id;
	public $lib;
	public $inscritoEventos = array();
	public $seParticipacao	= false;
	
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

		$this->id = JRequest::getInt( 'pid' );

		if ( !$this->id )
		{
			$user = &JFactory::getUser();

			if( !$user->guest )
			{
				$this->id = $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );
			}
		}

		if ( !$this->id ) $mainframe->close('Participante não encontrado');
	}
	
	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$query = ' SELECT p.id, p.id_user, p.twitter, p.orkut, p.youtube, p.site, u.name AS nome, p.registrado_em, p.avatar_img, p.curriculo, p.uf, c.nome AS cidade, pp.nome AS profissao'
			. ' FROM #__users AS u'
			. ' INNER JOIN #__p22eventos_participantes AS p ON p.id_user = u.id'
			. ' INNER JOIN #__p22cidades AS c ON c.id = p.id_cidade'
			. ' INNER JOIN #__p22eventos_profissoes AS pp ON pp.id = p.id_profissao'
			. ' WHERE p.id = ' . intval( $this->id )
			;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}

		$avaliador_eventos = $this->lib->getDados( 'p22eventos_avaliadores' , 'p.id_evento' , ' WHERE p.id_participante=' . intval( $this->_data->id ) , '' , 'loadResultArray' );

		if ( count( $avaliador_eventos) )
		{
			$query = 'SELECT p.id, p.nome, p.id_evento, p.tipo, p.aprovado, e.nome AS evento,'
			. ' ( SELECT COUNT(a.id) FROM #__p22eventos_avaliacao AS a WHERE a.id_evento = p.id_evento AND p.id = a.id_palestra AND a.id_avaliador = '. intval( $this->id ) .' ) AS avaliado'
			. ' FROM #__p22eventos_palestras AS p'
			. ' INNER JOIN #__p22eventos AS e ON e.id = p.id_evento'
			. ' WHERE p.id_evento IN ('. implode(',' , $avaliador_eventos ) .') AND p.published=1 AND p.aprovado=0'
			. ' ORDER BY p.nome'
			;
			$palestras = $this->lib->getRegistrosCustom( $query );
		
			$palestras_a_avaliar = array();
			for( $j = 0 ; $j < count( $palestras ) ; $j++ )
			{
				$row = &$palestras[ $j ];

				$sePalestras	= $this->lib->isPublished( $row->id_evento , 'palestras' );
				$seMinicursos	= $this->lib->isPublished( $row->id_evento , 'minicursos' );
				$sePalestrantes	= $this->lib->isPublished( $row->id_evento , 'palestrantes' );
				
				$periodo = $this->lib->getEventDetailString( $row->id_evento , false );

				if ( !$row->aprovado && ( $sePalestras || $seMinicursos ) && !$sePalestrantes )
				{
					$palestras_a_avaliar[ $row->evento .' <span style="font-size:12px;color:#0000FF">['. $periodo .']</span>' ][] = $row;
				}
			}

			$this->_data->avaliar = count( $palestras_a_avaliar ) ? $palestras_a_avaliar : null;
		}

		return $this->_data;
	}
	
	function store( $data )
	{	
		$row =& $this->getTable( 'locais' );

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

		$row =& $this->getTable( 'locais' );

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

	public function getParticipacaoEventos()
	{
		$participante = $this->_data->id;

		$query = 'SELECT i.registrado_em,i.id_evento,i.tp_reg,i.published,'
		. ' ( SELECT e.nome FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS evento,'
		. ' ( SELECT e.ano FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS ano,'
		. ' ( SELECT e.descricao FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS descricao,'
		. ' ( SELECT e.certificados FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS certificados,'
		. ' ( SELECT e.data_inicio FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS data_inicio,'
		. ' ( SELECT e.data_fim FROM #__p22eventos AS e WHERE e.id = i.id_evento ) AS data_fim'
		. ' FROM #__p22eventos_inscritos AS i'
		. ' WHERE i.id_participante=' . intval( $participante )
		. ' ORDER BY ano DESC, evento ASC'
		;
		$result = $this->lib->getRegistrosCustom( $query );

		$eventos = array();
		for( $c = 0 ; $c < count( $result ) ; $c++ )
		{
			$row										= &$result[ $c ];
			$row->periodo								= $this->lib->getEventDetailString( $row->id_evento , false );
			$this->inscritoEventos[ $row->id_evento ][]	= $row->tp_reg;

			if ( $row->tp_reg == 2 ) continue;

			$tipo = ( $row->tp_reg ) ? 'Colaborador' : 'Participante';

			if( $row->published )	$this->seParticipacao[ $tipo ] = true;

			$eventos[ $tipo ][]	= $row;
		}
		
		return $eventos;
	}

	public function getEventos()
	{
		$regs = $this->lib->getDados( 'p22eventos' , 'p.id,p.nome AS evento,p.descricao,p.data_inicio,p.data_fim,p.inscricoes,p.palestras,p.minicursos,p.colaboradores' , ' WHERE p.published=1' , '' , 'loadObjectList' );

		$eventos = array();
		for( $c = 0 ; $c < count( $regs ) ; $c++ )
		{
			$row			= &$regs[ $c ];
			$row->periodo	= $this->lib->getEventDetailString( $row->id , false );
			$eventos[]		= $row;
		}
		return $eventos;
	}

	public function getParticipacao()
	{
		return $this->seParticipacao;
	}

	public function getInscritoEventos()
	{
		return $this->inscritoEventos;
	}

	public function getPalestras()
	{
		$query = 'SELECT p.id,p.id_evento,p.nome,e.nome AS evento,e.ano,e.certificados,'
		. ' ( SELECT t.nome FROM #__p22eventos_trilhas AS t WHERE t.id = p.id_trilha ) AS trilha'
		. ' FROM #__p22eventos_palestras AS p'
		. ' INNER JOIN #__p22eventos_participantes AS pp ON pp.id = p.id_palestrante'
		. ' INNER JOIN #__p22eventos AS e ON e.id = p.id_evento'
		. ' WHERE p.id_palestrante=' . intval( $this->id ) . ' AND p.aprovado=1'
		;
		$this->_db->setQuery( $query );
		$result = $this->_db->loadObjectList();
		
		$palestras = array();
		for( $c = 0 ; $c < count( $result ) ; $c++ )
		{
			$row			= &$result[ $c ];
			$row->periodo	= $this->lib->getEventDetailString( $row->id_evento , false );
			$palestras[]	= $row;
		}

		return $palestras;
	}
}