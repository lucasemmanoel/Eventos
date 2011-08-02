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

if( strlen( $this->image ) > 0 )
{
?>
<script type="text/javascript">
<?php if( $this->closeWindow ) : ?>
	window.parent.changeImgAvatar( '<?php echo JRequest::getInt('pid'); ?>' , '<?php echo $this->image; ?>' );
	window.parent.document.getElementById('sbox-window').close();
<?php else: ?>
function preview(img, selection)
{
	var scaleX = '<?php echo $this->thumb_width;?>' / selection.width;
	var scaleY = '<?php echo $this->thumb_height;?>' / selection.height;

	jQuery('#thumbnail + div > img').css({
		width: Math.round(scaleX * <?php echo $this->current_large_image_width;?>) + 'px',
		height: Math.round(scaleY * <?php echo $this->current_large_image_height;?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
	jQuery('#x1').val(selection.x1);
	jQuery('#y1').val(selection.y1);
	jQuery('#x2').val(selection.x2);
	jQuery('#y2').val(selection.y2);
	jQuery('#w').val(selection.width);
	jQuery('#h').val(selection.height);
}

function enviar( act )
{
	var w	= document.thumbnail.w;
	var h	= document.thumbnail.h;
	var x1	= document.thumbnail.x1;
	var x2	= document.thumbnail.x2;
	var y1	= document.thumbnail.y1;
	var y2	= document.thumbnail.y2;

	switch( act )
	{
		case 'thumb':
			if(
				w.value.length == 0 ||
				h.value.length == 0 ||
				x1.value.length == 0 ||
				x2.value.length == 0 ||
				y1.value.length == 0 ||
				y2.value.length == 0
			)
			{
				alert( 'Faça uma seleção com o mouse para recortar e enviar.' );
				return false;
			}
			document.thumbnail.act.value = 'upload_thumb';
			break;
		case 'del':
			document.thumbnail.act.value = 'delete';
			break;
		case 'all':
			document.thumbnail.act.value = 'all';
			break;
	}
	document.getElementById('save_image').disabled	= true;
	document.getElementById('save_thumb').disabled	= true;
	document.getElementById('del_image').disabled	= true;

	if( act )
	{
		document.thumbnail.submit();
	}
}

Window.onDomReady(function() {
	window.parent.changeImgAvatar( '<?php echo JRequest::getInt('pid'); ?>' , '<?php echo $this->image; ?>' );
	jQuery('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $this->thumb_height/$this->thumb_width; ?>', onSelectChange: preview });
});
<?php endif; ?>
</script>
<?php } ?>
<div style="width:82%;margin-top:5px">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Novo Arquivo' ); ?></legend>
		<div id="campo_file">
			<form action="<?php echo JRoute::_('index2.php?option=com_p22evento&controller=participante&task=avatar&pid=' . JRequest::getInt('pid') ); ?>" name="adminForm" method="post" enctype="multipart/form-data">
				<table width="100%">
					<tr>
						<td>
							<input size="30" type="file" name="image" id="image" value="" />
							<button type="submit" name="upload">Enviar</button>
							<span>&nbsp; &nbsp; </span>
							<span style="visibility:hidden" id="campo_loader">Aguarde...</span>
						</td>
					</tr>
				</table>
				<input type="hidden" name="act" value="upload" id="act" />
				<input type="hidden" name="pid" value="<?php echo JRequest::getInt('pid'); ?>" id="id" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</div>
	</fieldset>
</div>
<div style="width:82%;margin-top:5px">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Definir Imagem' ); ?></legend>
		<div id="campo_imagem">
			<?php if( strlen( $this->image ) > 0 ) { ?>
			<div>
				<div style="float:left;width:505px;margin-left:3px">
					<h2 style="margin:2px 0px 2px 0px">Imagem Original</h2>
				</div>
				<div style="float:left;margin-left:3px">
					<h2 style="margin:2px 0px 2px 0px">Imagem a Ser Enviada</h2>
				</div>
			</div>
			<div style="clear:both"></div>
			<div align="center">
				<img src="./<?php echo $this->filepath; ?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
				<div style="float:left; position:relative; overflow:hidden; width:<?php echo $this->thumb_width; ?>px; height:<?php echo $this->thumb_height; ?>px;">
					<img src="./<?php echo $this->filepath; ?>" style="position: relative;" alt="Thumbnail Preview" width="180" />
				</div>
				<br style="clear:both;"/>
				<hr />
				<form action="<?php echo JRoute::_('index2.php?option=com_p22evento&controller=participante&task=avatar&pid='); ?>" name="thumbnail" method="post">
					<input type="hidden" name="x1" value="" id="x1" />
					<input type="hidden" name="y1" value="" id="y1" />
					<input type="hidden" name="x2" value="" id="x2" />
					<input type="hidden" name="y2" value="" id="y2" />
					<input type="hidden" name="w" value="" id="w" />
					<input type="hidden" name="h" value="" id="h" />
					<input type="hidden" name="act" value="" id="act" />
					<input type="hidden" name="image" value="<?php echo $this->image; ?>" id="image" />
					<input type="hidden" name="filepath" value="<?php echo $this->filepath; ?>" id="filepath" />
					<input type="hidden" name="pid" value="<?php echo JRequest::getInt('pid'); ?>" id="id" />
					<input type="button" name="upload_image" value="<?php echo JText::_( 'Recortar e Enviar' ); ?>" id="save_thumb" onclick="enviar('thumb')" />
					<input type="button" name="del_image" style="visibility:<?php echo ( file_exists( $this->filepath ) ) ? 'visible' : 'hidden'; ?>" value="<?php echo JText::_( 'Deletar Imagem' ); ?>" id="del_image" onclick="enviar('del')" />
					<input type="button" name="upload_thumbnail" style="visibility:<?php echo ( $this->usar_imagem_inteira ) ? 'visible' : 'hidden'; ?>" value="<?php echo JText::_( 'Enviar Imagem Inteira' ); ?>" id="save_image" onclick="enviar('all')" />
				</form>
			</div>
			<hr />
		<?php } ?>
		</div>
	</fieldset>
</div>