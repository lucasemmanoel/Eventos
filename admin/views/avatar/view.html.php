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

// Recursos Joomla! para trabalhar com views.
jimport( 'joomla.application.component.view' );

class P22eventosViewAvatar extends JView
{
	function display( $tpl = null )
	{		
		$model =& $this->getModel();

		$doc = JFactory::getDocument();
		$uri = JFactory::getURI();
		$doc->addScript('components/com_p22evento/js/jquery-pack.js');
		$doc->addScript('components/com_p22evento/js/jquery.imgareaselect.min.js');

		$image			= $model->lib->getDados( 'p22eventos_participantes' , 'p.avatar_img' , 'WHERE p.id = ' . JRequest::getInt('id') , '' , 'loadResult' );
		$renderImage	= false;
		$closeWindow	= false;

		// Verifica o nome da imagem para saber se já é uma imagem de avatar
		$expImg = explode( '_' , $image );

		if( count( $expImg ) )
		{
			if( !in_array( 'resize' , $expImg ) )
			{
				$renderImage = true;
			}
		}

		$post = JRequest::get('post');
		
		if( count( $post ) )
		{
			if( $post['act'] == 'upload' )
			{
				if( !$model->upload( $post ) )
				{
					JError::raiseWarning( 100 , JText::_( 'Erro ao enviar arquivo') );
				}
				else
				{
					$image			= $model->lib->getDados( 'p22eventos_participantes' , 'p.avatar_img' , 'WHERE p.id = ' . intval( $post['id'] ) , '' , 'loadResult' );
					$thumb_width	= $model->thumb_width;
					$thumb_height	= $model->thumb_height;

					$current_large_image_width	= $model->getWidth( $model->filepath );
					$current_large_image_height = $model->getHeight( $model->filepath );

					$filepath	= str_replace( JPATH_SITE , '' , $model->filepath );
//					$expFile	= array_filter( explode( DS , $filepath ) );
//					$filepath	= $expFile[4] . DS . $expFile[5] . DS . $expFile[6] . DS. $expFile[7] . DS. $expFile[8] . DS. $expFile[9];
				}
			}
			elseif( $post['act'] == 'upload_thumb' )
			{
				if( file_exists( JPATH_SITE . DS . $post['filepath'] ) )
				{
					$expFile = explode( DS , $post['filepath'] );
					if( count( $expFile ) )
					{
						$thumbPathArray = array();
						foreach( $expFile AS $path )
						{
							if( $path != '..' && $path != $post['image'] )
							{
								$thumbPathArray[] = $path;
							}
						}
					}
					$filepath			= JPATH_SITE . DS . implode( DS , $thumbPathArray ) . DS . $post['image'];
					$thumbPathArray[]	= 'resize_' . $post['image'];
					$thumbPath			= JPATH_SITE . DS . implode( DS , $thumbPathArray );
					$scale				= $model->thumb_width / $post['w'];
					
					if( $model->resizeThumbnailImage( $thumbPath , $filepath , $post['w'] , $post['h'] , $post['x1'] , $post['y1'] , $scale ) )
					{
						if( $model->renomearRegistro( $post['id'] , implode( DS , $thumbPathArray )  ) )
						{
							unlink( $filepath );
						}
					}
				}
				$image = 'resize_' . $post['image'];
				
				$closeWindow = true;
			}
			elseif( $post['act'] == 'delete' )
			{
				$model->deleteDir( $post['id'] );
				unset( $image );
			}
			elseif( $post['act'] == 'all' )
			{
				$filepath		= $post['filepath'];

				$model->renomearRegistro( $post['id'] , $filepath  );
				
				$image			= $post['image'];
				$closeWindow	= true;
			}
		}
		else
		{
			if( $image && $renderImage )
			{
				$filepath	= 'images' . DS . 'stories' . DS . 'eventos' . DS . JRequest::getVar('pid') . DS . $image;

				if(file_exists( $filepath ) )
				{
					$current_large_image_width	= $model->getWidth( $filepath );
					$current_large_image_height = $model->getHeight( $filepath );

					$thumb_width	= 180;
					$thumb_height	= 180;
					$orig_width		= $model->getWidth( $filepath );
					$orig_height	= $model->getHeight( $filepath );

					if( $orig_width <= $thumb_width && $orig_height <= $thumb_height )
					{
						$model->usar_imagem_inteira = true;
					}
				}
				else
				{
					unset( $image );
				}
			}
			else
			{
				unset( $image );
			}
		}

		// Prepara os dados para o template.
		$this->assignRef( 'usar_imagem_inteira'			, $model->usar_imagem_inteira );
		$this->assignRef( 'image'						, $image );
		$this->assignRef( 'filepath'					, $filepath );
		$this->assignRef( 'thumb_width'					, $thumb_width );
		$this->assignRef( 'thumb_height'				, $thumb_height );
		$this->assignRef( 'current_large_image_width'	, $current_large_image_width );
		$this->assignRef( 'current_large_image_height'	, $current_large_image_height );
		$this->assignRef( 'closeWindow'					, $closeWindow );

		// Carrega o template.
		parent::display( $tpl );
	}
}
