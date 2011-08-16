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

class P22eventosControllerCertificado extends P22eventosController
{
	/*
	 * Constructor (registra tarefas adicionais ao método)
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks	
		$this->registerTask( 'certificadovisual' , 'certificados' );
	}

	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
		$this->lib	= new P22Eventos();
		$db			= &JFactory::getDBO();
		$this->lib->setDBO( $db );

		$data = JRequest::get( 'post' );

		$data['id_evento']			= intval( $data['idevento'] );

		$data['eventalias']			= $db->Quote( $data['eventalias'] );
		$data['image_name']			= $db->Quote( $data['image_name'] );

		$data['texto_inscritos']	= JRequest::getVar( 'texto_inscritos', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['texto_inscritos']	= $db->Quote( $data['texto_inscritos'] );

		$data['texto_colaboradores']= JRequest::getVar( 'texto_colaboradores', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['texto_colaboradores']= $db->Quote( $data['texto_colaboradores'] );

		$data['texto_palestrantes']	= JRequest::getVar( 'texto_palestrantes', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$data['texto_palestrantes']	= $db->Quote( $data['texto_palestrantes'] );

		$chkData = $this->lib->countDados( 'p22eventos_certificados' ,  ' WHERE p.id_evento=' . intval( $data['id_evento'] ) );

		if ( $chkData )
		{
			$query		= "UPDATE ". $db->nameQuote( '#__p22eventos_certificados' ) ." SET eventalias={$data['eventalias']}, image_name={$data['image_name']}, texto_inscritos={$data['texto_inscritos']}, texto_colaboradores={$data['texto_colaboradores']}, texto_palestrantes={$data['texto_palestrantes']} WHERE id_evento={$data['id_evento']}";
			$msg		= 'atualizar';
			$success	= 'atualizados';
		}
		else
		{
			$query		= "INSERT INTO ". $db->nameQuote( '#__p22eventos_certificados' ) ." (id_evento, eventalias, image_name, texto_inscritos, texto_colaboradores, texto_palestrantes,font_size, cpf_font_size,num_font_size) VALUES ( {$data['id_evento']}, {$data['eventalias']}, {$data['image_name']}, {$data['texto_inscritos']}, {$data['texto_colaboradores']}, {$data['texto_palestrantes']} , 12,12,12 ) ";
			$msg		= 'inserir';
			$success	= 'inseridos';
		}

		$db->setQuery( $query );

		if( !$db->query() )
		{
			$msg = 'Erro ao '. $msg .': ' . $db->getErrorMsg();
			$this->setRedirect( 'index.php?option=com_p22evento&task=certificados&idevento=' . intval( $data['id_evento'] ) , $msg , 'error' );
		}
		else
		{
			$msg = "Registros {$success} com sucesso!";
			$this->setRedirect( 'index.php?option=com_p22evento&task=certificados&idevento=' . intval( $data['id_evento'] ) , $msg );
		}
	}

	function apply_certificado()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db					= &JFactory::getDBO();
		$data				= JRequest::get( 'post' );
		$data['id_evento']	= intval( $data['idevento'] );
		$box_width			= intval( $data['tp_box_width'] );
		$margin_top			= intval( $data['tp_margin_top'] );
		$margin_left		= intval( $data['tp_margin_left'] );
		$font_size			= intval( $data['tp_font_size'] );
		$line_height		= intval( $data['tp_line_height'] );
		$cpf_margin_top		= intval( $data['cpf_margin_top'] );
		$cpf_margin_left	= intval( $data['cpf_margin_left'] );
		$cpf_font_size		= intval( $data['cpf_font_size'] );
		$num_margin_top		= intval( $data['num_margin_top'] );
		$num_margin_left	= intval( $data['num_margin_left'] );
		$num_font_size		= intval( $data['num_font_size'] );
		$view				= ( $data['tp_view'] ) ? '&viewcertificado=' . $data['view'] : '';
		
		$query = "UPDATE ". $db->nameQuote( '#__p22eventos_certificados' ) ." SET box_width={$box_width}, margin_left={$margin_left}, margin_top={$margin_top}, font_size={$font_size}, line_height={$line_height}, cpf_margin_top={$cpf_margin_top}, cpf_margin_left={$cpf_margin_left}, cpf_font_size={$cpf_font_size}, num_margin_top={$num_margin_top}, num_margin_left={$num_margin_left}, num_font_size={$num_font_size} WHERE id_evento={$data['id_evento']}";

		$db->setQuery( $query );

		if( !$db->query() )
		{
			$msg = 'Erro ao atualizar coordenadas de registros de certificado: ' . $db->getErrorMsg();
			$this->setRedirect( 'index.php?option=com_p22evento&controller=certificado&task=certificadovisual&idevento=' . intval( $data['id_evento'] ) . $view , $msg , 'error' );
		}
		else
		{
			$msg = "Registros atualizados com sucesso!";
			$this->setRedirect( 'index.php?option=com_p22evento&controller=certificado&task=certificadovisual&idevento=' . intval( $data['id_evento'] ) . $view , $msg );
		}
	}

	function setCertView()
	{
		$id_evento	= JRequest::getInt( 'idevento' );
		$view		= JRequest::getCmd( 'tp_view' );

		$this->setRedirect( 'index.php?option=com_p22evento&controller=certificado&task=certificadovisual&idevento=' . $id_evento .'&viewcertificado='. $view );
	}
}