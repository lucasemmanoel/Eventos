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

class P22eventosModelAvalpalestra extends JModel
{
	public $id;
	public $lib;
	public $idevento;
	
	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct( $id = null )
	{
		global $mainframe;
		
		parent::__construct();

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->id = JRequest::getInt( 'palestraid' );

		$user = &JFactory::getUser();

		if( $user->guest || !$this->id )
		{
			$mainframe->close('Acesso incorreto ou negado.');
		}
	}

	function &getRegistro()
	{
		// Load the data
		if ( empty( $this->_data ) )
		{
			$user				= &JFactory::getUser();
			$id_participante	= $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );

			$query = ' SELECT p.id AS id_palestra,p.nome,p.resumo,p.aprovado,p.id_evento,'
			. 'a.id,a.confianca,a.relevancia,a.qualidade_tecnica,a.experiencia,a.recomendacao,a.comentario,a.id_avaliador'
			. ' FROM #__p22eventos_palestras AS p'
			. ' LEFT JOIN #__p22eventos_avaliacao AS a ON a.id_palestra = p.id AND a.id_avaliador = ' . intval( $id_participante )
			. ' WHERE p.id = ' . intval( $this->id );
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
			
			$this->_data->avaliar = ( $this->_data->aprovado || ( !$this->lib->isPublished( $this->_data->id_evento , 'palestras' ) && !$this->lib->isPublished( $this->_data->id_evento , 'minicursos' ) ) ) ? false : true;

			if ( $this->_data->avaliar )
			{
				$this->_data->id_participante	= $id_participante;
				$this->_data->seResumo			= ( $this->_data->resumo ) ? 1 : 0;
			}
		}
		
		return $this->_data;
	}
	
	function store( $data )
	{
		global $mainframe;
		
		if ( !$data['id'] )
		{
			$data['registrado_em']	= $this->lib->now();
		}
		
		if( !$this->lib->isPublished( $data['id_evento'] , 'palestras' ) ) $mainframe->close( 'Avaliações já estão suspensas' );

		$row =& $this->getTable( 'avalpalestra' );

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

		return true;
	}

	function getProfissoes()
	{
		$query = 'SELECT id, nome'
		. ' FROM #__p22eventos_profissoes'
		. ' WHERE published=1'
		. ' ORDER BY nome'
		;
		$this->_db->setQuery( $query );
		$profissoes = $this->_db->loadObjectList();

		return $profissoes;
	}
}