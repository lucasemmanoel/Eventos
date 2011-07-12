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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
	
class P22eventosModelAvatar extends JModel
{
	public $filepath;
	public $filename;
	public $thumb_width			= 180;
	public $thumb_height		= 240;
	public $usar_imagem_inteira = false;
	public $lib;
	public $idevento;

	/*
	 * Constructor (recupera o valor do ID)
	 */
	function __construct()
	{
		global $mainframe;

		parent::__construct();

		require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
		$this->idevento = JRequest::getInt( 'idevento' );
		$this->lib		= new P22Eventos();
		$this->lib->setDBO( $this->_db );

		$user	= &JFactory::getUser();
		$pid	= JRequest::getInt( 'pid' );
		
		if( !$user->guest )
		{
			$part		= $this->lib->getDados( 'p22eventos_participantes' , 'p.id,p.id_user', ' WHERE p.id_user=' . intval( $user->id ), '', 'loadObject' );
			$this->id	= $part->id;
		}
		elseif ( !$pid ) { $mainframe->close( 'Acesso incorreto ou negado.' ); }
		
		if ( $pid && ( $pid != $this->id ) ) $mainframe->close( 'Acesso incorreto ou negado.' );
	}

	function upload()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
		
		$id_prof		= $this->id;
		$eventName		= $this->lib->eventName();
		$eventName		= JFolder::makeSafe( $this->remove_acentos( $eventName ) );
		$file			= JRequest::getVar( 'image' , '', 'files', 'array' );
		$base			= JPATH_SITE . DS . 'images' . DS . 'stories' . DS . 'eventos';
		$format			= JRequest::getVar( 'format', 'html', '', 'cmd');
		$folder			= 'avatar';
		$err			= null;
		$file['name']	= JFile::makeSafe( $this->remove_acentos( $file['name'] ) );

		// Max width allowed for the large image
		$max_width			= 500;
		$this->filename		= $file['name'];
		
		if ( isset($file['name']) )
		{
			$path = JPath::clean( $base . DS . $folder . DS . $id_prof );
			
			if( !empty( $file['name'] ) )
			{
				$filepath = JPath::clean( $path . DS . strtolower( $file['name'] ) );

				if( ! ( JFolder::exists( $path ) ) )
				{
					JFolder::create( $path );
					JFile::write( $path . DS . "index.html" , "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>" );
				}

				// Verifica se já  existe algum arquivo com este nome.
				$fileExists = false;
				if ( JFolder::exists( $path ) )
				{
					$files = JFolder::files( $path , '.' , true );
					
					foreach ( $files as $filename )
					{
						if ( $filename != 'index.html')
						{
							JFile::delete( $path . DS . $filename );
						}
					}
				}
				
				if ( !$this->canUpload( $file , $err ) )
				{
					if ($format == 'json')
					{
						jimport('joomla.error.log');
						$log = &JLog::getInstance('upload.error.php');
						$log->addEntry(array('comment' => 'Inválido: '.$filepath.': '.$err));
						header('HTTP/1.0 415 Unsupported Media Type');
						jexit('Erro. Tipo de Arquivo não permitido!');
					}
					else
					{
						JError::raiseNotice( 100, JText::_( $err ) );
						return false;
					}
				}

				if ( JFile::exists( $filepath ) )
				{
					if ($format == 'json')
					{
						jimport('joomla.error.log');
						$log = &JLog::getInstance('upload.error.php');
						$log->addEntry(array('comment' => 'O arquivo ' . $file['name'] . ' já existe: '.$filepath));
						header('HTTP/1.0 409 Conflict');
						jexit('Erro. O arquivo ' . $file['name'] . ' já existe');
					}
					else
					{
						JError::raiseNotice(100, JText::_('Erro. O arquivo ' . $file['name'] . ' já existe.'));
						return false;
					}
				}
				
				if ( !JFile::upload( $file['tmp_name'], $filepath ) )
				{
					if ($format == 'json')
					{
						jimport('joomla.error.log');
						$log = &JLog::getInstance('upload.error.php');
						$log->addEntry(array('comment' => 'Impossível executar upload: '.$filepath));
						header('HTTP/1.0 400 Bad Request');
						jexit('Erro. Não foi possível enviar arquivos ' . $file['name']. '.');
					}
					else
					{
						JError::raiseWarning(100, JText::_('Erro. Não foi possível enviar arquivos: ' . $file['name']. '.'));
						return false;
					}
				}
			}
			else
			{
				JError::raiseWarning( 100 , JText::_( 'Erro ao enviar arquivos.') );
				return false;
			}
		}

		$this->filepath		= $filepath;
		$orig_width			= $this->getWidth( $filepath );
		$orig_height		= $this->getHeight( $filepath );

		if( $orig_width <= $this->thumb_width && $orig_height <= $this->thumb_height )
		{
			$this->usar_imagem_inteira = true;
		}

		chmod( $filepath , 0755 );

		$width	= $this->getWidth( $filepath );
		$height = $this->getHeight( $filepath );
		
		//Scale the image if it is greater than the width set above
		$scale = ( $width > $max_width ) ? $max_width/$width : 1;
		
		if( !$this->resizeImage( $filepath , $width , $height , $scale ) )
		{
			JError::raiseWarning( 100 , JText::_( 'Não foi possível redimensionar a imagem') );
			return false;
		}
		else
		{
			if( !$this->_registraImagem( $id_prof , $file['name'] ) )
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Checks if the file can be uploaded
	 *
	 * @param array File information
	 * @param string An error message to be returned
	 * @return boolean
	 */
	private function canUpload( $file, &$err )
	{
		$params = &JComponentHelper::getParams( 'com_media' );

		if( empty( $file['name'] ) )
		{
			$err = 'Informe um arquivo para enviar.';
			return false;
		}

		if ($file['name'] !== JFile::makesafe( $file['name'] ) )
		{
			$err = 'O nome do arquivo contém caracteres que podem representar perigo de segurança. Verifique!';
			return false;
		}

		$format		= strtolower( JFile::getExt( $file['name'] ) );
		$allowable	= explode( ',' , $params->get( 'upload_extensions' ) );
		$ignored	= explode( ',' , $params->get( 'ignore_extensions' ) );

		if ( !in_array( $format , $allowable ) && !in_array( $format , $ignored ) )
		{
			$err = 'Tipo de Arquivo não permitido';
			return false;
		}

		$maxSize = (int) $params->get( 'upload_maxsize', 0 );

		if ($maxSize > 0 && (int) $file['size'] > $maxSize )
		{
			$err = 'Tamanho do arquivo não permitido. O tamanho máximo permitido é de '. ( $maxSize / 1000 ) . ' Mb.';
			return false;
		}

		$user		= JFactory::getUser();
		$imginfo	= null;

		if( $params->get( 'restrict_uploads' , 1) )
		{
			$images = explode( ',' , $params->get( 'image_extensions' ) );

			if( in_array( $format , $images ) ) // if its an image run it through getimagesize
			{
				if( ( $imginfo = getimagesize( $file['tmp_name'] ) ) === FALSE )
				{
					$err = 'Extensão de imagem inválida.';
					return false;
				}
			}
			else if( !in_array( $format , $ignored ) )
			{
				// if its not an image...and we're not ignoring it
				$allowed_mime = explode( ',' , $params->get( 'upload_mime') );
				$illegal_mime = explode( ',' , $params->get( 'upload_mime_illegal') );

				if( function_exists('finfo_open') && $params->get( 'check_mime' , 1 ) ) // We have fileinfo
				{
					$finfo	= finfo_open(FILEINFO_MIME);
					$type	= finfo_file($finfo, $file['tmp_name']);
					
					if( strlen($type) && !in_array( $type, $allowed_mime ) && in_array( $type, $illegal_mime ) )
					{
						$err = 'Mime inválida.';
						return false;
					}
					finfo_close( $finfo );
				}
				else if( function_exists( 'mime_content_type' ) && $params->get( 'check_mime' , 1 ) )
				{
					// we have mime magic
					$type = mime_content_type($file['tmp_name']);
					if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
						//$err = 'WARNINVALIDMIME';
						return false;
					}
				} else if(!$user->authorize( 'login', 'administrator' )) {
					$err = 'Você deve ter permissões administrativas para enviar esta imagem.';
					return false;
				}
			}
		}

		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$err = 'Tags inválidas.';
				return false;
			}
		}
		return true;
	}

	##########################################################################################################
	# IMAGE FUNCTIONS																						 #
	# You do not need to alter these functions																 #
	##########################################################################################################
	function resizeImage( $image , $width , $height , $scale )
	{		
		list( $imagewidth , $imageheight , $imageType ) = getimagesize( $image );
		$imageType = image_type_to_mime_type( $imageType );

		$newImageWidth	= ceil( $width * $scale );
		$newImageHeight = ceil( $height * $scale );
		$newImage		= imagecreatetruecolor( $newImageWidth , $newImageHeight );
		
		switch($imageType)
		{
			case "image/gif":
				$source = imagecreatefromgif( $image );
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg( $image );
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng( $image );
				break;
		}
		
		imagecopyresampled( $newImage , $source , 0 , 0 , 0 , 0 , $newImageWidth , $newImageHeight , $width , $height );

		switch( $imageType )
		{
			case "image/gif":
				if( !imagegif( $newImage , $image ) )
				{
					return false;
				}

				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				if( !imagejpeg( $newImage , $image , 90 ) )
				{
					return false;
				}
				break;
			case "image/png":
			case "image/x-png":
				if( !imagepng( $newImage , $image ) )
				{
					return false;
				}
				break;
		}
		chmod( $image , 0755 );
		
		return true;
	}
	
	//You do not need to alter these functions
	function resizeThumbnailImage( $thumb_image_name , $image , $width , $height , $start_width , $start_height , $scale )
	{
		list( $imagewidth , $imageheight , $imageType ) = getimagesize( $image );
		$imageType = image_type_to_mime_type( $imageType );

		$newImageWidth	= ceil( $width * $scale );
		$newImageHeight = ceil( $height * $scale );
		$newImage		= imagecreatetruecolor( $newImageWidth , $newImageHeight );

		switch( $imageType )
		{
			case "image/gif":
				$source = imagecreatefromgif( $image );
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg( $image );
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng( $image );
				break;
		}

		imagecopyresampled( $newImage , $source , 0 , 0 , $start_width , $start_height , $newImageWidth , $newImageHeight , $width , $height );

		switch($imageType)
		{
			case "image/gif":
				imagegif($newImage,$thumb_image_name);
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$thumb_image_name,90);
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);
				break;
		}
		
		chmod( $thumb_image_name , 0755 );
		
		return true;
	}

	//You do not need to alter these functions
	function getHeight($image)
	{
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	//You do not need to alter these functions
	function getWidth($image)
	{
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}

	public function remove_acentos($var)
	{
		// retira espacos e acentos
		$var = strtolower( $var );
		$replace_chars = array(
			'á' => 'a',
			'à' => 'a',
			'â' => 'a',
			'ã' => 'a',

			'Á' => 'a',
			'À' => 'a',
			'Â' => 'a',
			'Ã' => 'a',

			'é' => 'e',
			'è' => 'e',
			'ê' => 'e',

			'É' => 'e',
			'È' => 'e',
			'Ê' => 'e',

			'ó' => 'o',
			'ò' => 'o',
			'ô' => 'o',
			'õ' => 'o',

			'Ó' => 'o',
			'Ò' => 'o',
			'Ô' => 'o',
			'Õ' => 'o',

			'ú' => 'u',
			'ù' => 'u',
			'û' => 'u',
			'ũ' => 'u',

			'Ú' => 'u',
			'Ù' => 'u',
			'Û' => 'u',
			'Ũ' => 'u',

			'ç' => 'c',
			'Ç' => 'c',

			' ' => '_',
			'-'	=> '_'
		);
		foreach($replace_chars as $k => $v) {
			$var = str_ireplace( $k, $v, $var );
		}
		return $var;
	}

	private function _registraImagem( $id_prof , $filename )
	{
		$obj				= new stdClass();
		$obj->id			= (int)$id_prof;
		$obj->avatar_img	= (string)$filename;

		if( !$this->_db->updateObject( '#__p22eventos_participantes', $obj, 'id' ) )
		{
			JError::raiseWarning( 100, $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}

	public function renomearRegistro( $id_prof , $novoNome  )
	{
		$obj				= new stdClass();
		$obj->id			= (int)$id_prof;
		$obj->avatar_img	= (string)$novoNome;

		if( !$this->_db->updateObject( '#__p22eventos_participantes', $obj, 'id' ) )
		{
			JError::raiseWarning( 100, $this->_db->getErrorMsg() );
			return false;
		}
		return true;
	}

	public function deleteDir( $id_prof )
	{
		$image		= $this->lib->getDados( 'p22eventos_participantes' , 'p.avatar_img' , 'WHERE p.id = ' . (int)$id_prof , '' , 'loadResult' );
		$base		= JPATH_SITE . DS . 'images' . DS . 'stories';
		$folder		= 'eventos' . DS . 'avatar';
		$path		= JPath::clean( $base . DS . $folder . DS . $id_prof );

		if( !JFolder::delete( $path ) )
		{
			return false;
		}
		return true;
	}

	public function renameFile( $filename , $newName , $path )
	{
		if( !JFile::copy( $filename , $newName , $path ) )
		{
			return false;
		}

		if( !JFile::delete( $path . DS . $filename ) )
		{
			return false;
		}
		return true;
	}
}