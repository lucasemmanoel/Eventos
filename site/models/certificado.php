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

class P22eventosModelCertificado extends JModel
{
	public $lib;
	public $idevento;
	private $_registro;
	
	
	function __construct( $id )
	{
		global $mainframe;

		parent::__construct();
		$this->idevento = JRequest::getInt( 'idevento' );

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->lib = new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$this->idevento = JRequest::getInt('idevento');
		
		if( !$this->lib->checkEvento() )
		{
			$mainframe->redirect( 'index.php?option=com_p22evento' , 'Evento não encontrado.' , 'notice' );
		}

		$user = &JFactory::getUser();

		if( !$id || !$user->guest )
		{
			$this->id = $this->lib->getDados( 'p22eventos_participantes' , 'p.id', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadResult' );
		}
		else
		{
			$this->id = $id;
		}
		
		if( !$this->id || $user->guest ) $mainframe->close('Participante não encontrado');

		if ( !$this->_verificaCertificado() ) $mainframe->close('Participante não encontrado');
	}

	private function _verificaCertificado()
	{
		global $mainframe;
		
		$tpCert = JRequest::getInt('cert');
		
		$query = 'SELECT i.id_evento,i.id_participante AS id,i.tp_reg,i.published,p.cpf,'
		. 'e.nome AS evento,e.certificados,'
		. 'c.*,'
		. ' ( SELECT u.name FROM '. $this->_db->nameQuote('#__users') .' AS u WHERE u.id = p.id_user ) AS nome'
		. ' FROM '. $this->_db->nameQuote('#__p22eventos_inscritos') .' AS i'
		. ' INNER JOIN '. $this->_db->nameQuote('#__p22eventos_participantes') .' AS p ON p.id = i.id_participante'
		. ' INNER JOIN '. $this->_db->nameQuote('#__p22eventos') .' AS e ON e.id = i.id_evento'
		. ' INNER JOIN '. $this->_db->nameQuote('#__p22eventos_certificados') .' AS c ON c.id_evento = e.id'
		. ' WHERE i.id_evento=' . intval( $this->idevento ) . ' AND i.tp_reg=' . intval( $tpCert ) . ' AND i.id_participante=' . intval( $this->id )
		;
		$this->_registro	= $this->lib->getRegistrosCustom( $query , 'loadObject' );

		if ( !$this->_registro->certificados ) $mainframe->close( 'Certificados não disponíveis' );

		$seConfirmado		= intval( $this->_registro->published );
		
		$this->_registro->cpf	= str_pad( $this->_registro->cpf , 11 , "0" , STR_PAD_LEFT );
		$this->_registro->cpf2	= $this->lib->setMask( $this->_registro->cpf , '999.999.999-99' );

		switch( $tpCert )
		{
			case 1:
				$this->_registro->tipo = 'Colaborador';
				break;
			case 2:
				$this->_registro->tipo = 'Palestrante';
				break;
			default:
				$this->_registro->tipo = 'Participante';
				break;
		}

		if ( !$seConfirmado )
		{
			return false;
		}

		return true;
	}

	public function getRegistro()
	{
		return $this->_registro;
	}

	public function getCertificadoGerado()
	{
		$where		= array();
		$where[]	= 'p.id_evento=' . intval( $this->idevento );
		$where[]	= 'p.tp_reg=' . intval( $this->_registro->tp_reg );
		$where[]	= 'p.id_participante=' . intval( $this->id );
		$where[]	= 'p.nome=' . $this->lib->db->Quote( $this->_registro->nome );
		$where[]	= 'p.cpf=' . intval( $this->_registro->cpf );
		$where		= ' WHERE ' . implode( ' AND ' , $where );
		$check		= $this->lib->getDados( 'p22eventos_certificados_data' , 'p.id' , $where , '' , 'loadResult' );

		$this->_registro->id_certificado = intval( $check );

		return ( $check > 0 ) ? false : true;
	}
	
	public function getEventName()
	{
		$query = 'SELECT DISTINCT p.nome'
		. ' FROM #__p22eventos AS p'
		. ' WHERE p.id=' . intval( $this->idevento )
		;
		$name = $this->lib->getRegistrosCustom( $query , 'loadResult' );

		return $name;
	}

	public function getPalestra()
	{
		$palestraid	= JRequest::getInt('palestraid');
		$palestra	= $this->lib->getDados( 'p22eventos_palestras' , 'p.nome AS palestra' , ' WHERE p.id=' . $palestraid , '' , 'loadResult' );

		$this->_registro->id_palestra	= $palestraid;
		$this->_registro->palestra		= $palestra;

		return $palestra;
	}
}
