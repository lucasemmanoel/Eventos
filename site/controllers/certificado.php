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
	var $_engine		= null;
	var $_header		= null;
	var $_margin_header	= 5;
	var $_margin_footer	= 10;
	var $_margin_top	= 27;
	var $_margin_bottom	= 25;
	var $_margin_left	= 2;
	var $_margin_right	= 2;

	// Scale ratio for images [number of points in user unit]
	var $_image_scale	= 4;

	/**
	 * Class constructore
	 *
	 * @access protected
	 * @param	array	$options Associative array of options
	 */
	function __construct()
	{
		parent::__construct();

		//set mime type
		$this->_mime = 'application/pdf';

		//set document type
		$this->_type = 'pdf';
		/*
		 * Setup external configuration options
		 */
		define('K_TCPDF_EXTERNAL_CONFIG', true);

		/*
		 * Path options
		 */

		// Installation path
		define("K_PATH_MAIN", JPATH_LIBRARIES.DS."tcpdf");

		// URL path
		define("K_PATH_URL", JPATH_BASE);

		// Fonts path
		define("K_PATH_FONTS", JPATH_SITE.DS.'language'.DS."pdf_fonts".DS);

		// Cache directory path
		define("K_PATH_CACHE", K_PATH_MAIN.DS."cache");

		// Cache URL path
		define("K_PATH_URL_CACHE", K_PATH_URL.DS."cache");

		// Images path
		define("K_PATH_IMAGES", K_PATH_MAIN.DS."images");

		// Blank image path
		define("K_BLANK_IMAGE", K_PATH_IMAGES.DS."_blank.png");

		/*
		 * Format options
		 */

		// Cell height ratio
		define("K_CELL_HEIGHT_RATIO", 1.25);

		// Magnification scale for titles
		define("K_TITLE_MAGNIFICATION", 1.3);

		// Reduction scale for small font
		define("K_SMALL_RATIO", 2/3);

		// Magnication scale for head
		define("HEAD_MAGNIFICATION", 1.1);

		/*
		 * Create the pdf document
		 */

		jimport('tcpdf.tcpdf');

		// Default settings are a portrait layout with an A4 configuration using millimeters as units
		$this->_engine = new TCPDF('L');

		//set margins
		$this->_engine->SetMargins($this->_margin_left, $this->_margin_top, $this->_margin_right);
		//set auto page breaks
		$this->_engine->SetAutoPageBreak(TRUE, $this->_margin_bottom);
		$this->_engine->SetHeaderMargin($this->_margin_header);
		$this->_engine->SetFooterMargin($this->_margin_footer);
		$this->_engine->setImageScale($this->_image_scale);
	}
	
	function registra_certificado()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$data	= JRequest::get('post');
		$db		= &JFactory::getDBO();

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$lib = new P22Eventos();
		$lib->setDBO( $db );

		$query = 'SELECT p.cpf,'
		. ' ( SELECT u.name FROM '. $db->nameQuote('#__users') .' AS u WHERE u.id = p.id_user ) AS nome'
		. ' FROM '. $db->nameQuote('#__p22eventos_participantes') . ' AS p'
		. ' WHERE p.id=' . intval( $data['id'] )
		;
		$reg = $lib->getRegistrosCustom( $query , 'loadObject' );

		$obj					= new stdClass();
		$obj->id_evento			= intval( $data['idevento'] );
		$obj->tp_reg			= intval( $data['tp_reg'] );
		$obj->id_participante	= intval( $data['id'] );
		$obj->nome				= (string)$reg->nome;
		$obj->cpf				= intval( $reg->cpf );
		$obj->id_palestra		= intval( $data['id_palestra'] );

		if ( !$db->insertObject( '#__p22eventos_certificados_data' , $obj ) )
		{
//			$msg = 'Erro ao gerar certificado se o problema persistir, informe aos administradores do sistema ' . $db->getErrorMsg();
			$msg = 'Erro ao gerar certificado se o problema persistir, informe aos administradores do sistema.';
			$errorType = 'error';
		}

		$link = 'index2.php?option=com_p22evento&task=certificado&cert='. intval( $data['tp_reg'] ) .'&idevento=' . intval( $data['idevento'] );
		$this->setRedirect( $link, $msg , $msgType );	
	}

	public function certificadoPDF()
	{
		$id				= JRequest::getInt('id' , '' , 'post');
		$modelAvatar	= &JModel::getInstance( 'avatar' , 'P22eventosModel' );
		$model			= &JModel::getInstance( 'certificado' , 'P22eventosModel' , $id );
		$registro		= $model->getRegistro();
		
		$img			= JPATH::clean( JPATH_SITE . DS . 'images'. DS .'stories'. DS  .'eventos'. DS . $registro->eventalias . DS .'certificados'. DS . $registro->image_name );
		$width			= $modelAvatar->getWidth( $img );
		$height			= $modelAvatar->getHeight( $img );
		$filepath		= JURI::root() . "images/stories/eventos/{$registro->eventalias}/certificados/{$registro->image_name}";
		$style			= "width:{$width}px;height:{$height}px;border:1px solid #CCC;position:relative;z-index:0;";
		$style2			= "position:absolute;z-index:0;";
		$style3			= "position:absolute;z-index:2;width:{$width}px;height:{$height}px;background: transparent url( './administrator/components/com_p22evento/images/1pixelbg.png' ) repeat 0 0";

		switch( $registro->tp_reg )
		{
			case 1:
				$tipo = 'colaborador';
				break;
			case 2:
				$tipo = 'palestrante';
				break;
			default:
				$tipo = 'participante';
				break;
		}

		$periodo		= $model->lib->getEventDetailString( $registro->id_evento );
		$text			= ( !$view ) ? 'texto_inscritos' : 'texto_' . $tipo;
		$maintext		= $registro->$text;

		$maintext		= str_replace( '[[NOME]]' , 'Fulano da Silva Júnior' , $maintext );
		$maintext		= str_replace( '[[PERIODO_EVENTO]]' , $periodo , $maintext );
		$maintext		= str_replace( '[[TIPO]]' , $tipo , $maintext );
		$maintext		= str_replace( '[[EVENTO]]' , $registro->evento , $maintext );
		$maintext		= str_replace( '[[PALESTRA]]' , 'Palestra Boa Demais! v10.2' , $maintext );
		$maintext		= str_replace( '[B]' , '<strong>' , $maintext );
		$maintext		= str_replace( '[/B]' , '</strong>' , $maintext );
		$maintext		= str_replace( '[I]' , '<i>' , $maintext );
		$maintext		= str_replace( '[/I]' , '</i>' , $maintext );
		$maintext		= str_replace( '[S]' , '<u>' , $maintext );
		$maintext		= str_replace( '[/S]' , '</u>' , $maintext );

		preg_match( "/[COLOR]?(([a-fA-F0-9]){3}){1,2}/" , $maintext , $matches );

		if ( count( $matches ) )
		{
			$color		= $matches[0];
			$maintext	= str_replace( "[COLOR={$color}]" , "<font style='color:#{$color}'>" , $maintext );
			$maintext	= str_replace( "[/COLOR]" , '</font>' , $maintext );
		}

		$html = '<div style="'.$style.';margin-left:auto;margin-right:auto;margin-top:20px">';
		$html .= '<div style="'.$style3.'"></div>';
		$html .= '<div style="'.$style2.'">';
		$html .= '<img src="'.$filepath.'" alt="" />';
		$html .= '</div>';
		$html .= '<div id="main_box" style="text-align:justify;float:left;position:absolute;line-height:'.$registro->line_height.'px;width:'.$registro->box_width.'px;margin-top:'.$registro->margin_top.'px;margin-left:'.$registro->margin_left.'px;font-size:'.$registro->font_size.'px">';
		$html .= nl2br( $maintext );
		$html .= '</div>';
		$html .= '<div id="cpf_box" style="text-align:justify;float:left;position:absolute;margin-top:'.$registro->cpf_margin_top.'px;margin-left:'.$registro->cpf_margin_left.'px;font-size:'.$this->registro->cpf_font_size.'px">';
		$html .= '<strong>CPF:</strong> 999.999.999-99';
		$html .= '</div>';
		$html .= '<div id="num_box" style="text-align:justify;float:left;position:absolute;margin-top:'.$registro->num_margin_top.'px;margin-left:'.$registro->num_margin_left.'px;font-size:'.$registro->num_font_size.'px">';
		$html .= '<strong>Certifcado Número:</strong> 1.233';
		$html .= '</div>';
		$html .= '</div>';

//		echo $html;
//		die;
		$doc	= &JFactory::getDocument();
		$pdf	= &$this->_engine;

		// Set PDF Metadata
		$pdf->SetCreator($doc->getGenerator());
		$pdf->SetSubject($doc->getDescription());
		$pdf->SetKeywords($doc->getMetaData('keywords'));

		// Set PDF Header and Footer fonts
		$lang = &JFactory::getLanguage();
		$font = $lang->getPdfFontName();
		$font = ($font) ? $font : 'freesans';

		$pdf->setRTL($lang->isRTL());

		$pdf->setHeaderFont(array($font, '', 10));
		$pdf->setFooterFont(array($font, '', 8));

		// Initialize PDF Document
		$pdf->AliasNbPages();
		$pdf->AddPage();

		// Build the PDF Document string from the document buffer
		$pdf->writeHTML($html, true, false, true, false);
		$pdf->Output( 'JOOMLA.pdf' , 'D');
	}
}