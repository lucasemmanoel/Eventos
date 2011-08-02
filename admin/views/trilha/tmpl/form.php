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

$seImgExists	= ( $this->registro->avatar_img ) ? file_exists( JPATH_SITE . DS . $this->registro->avatar_img ) : false;
$avatar_img		= ( $seImgExists ) ? '../' . $this->registro->avatar_img : 'components/com_p22evento/images/no_picture.jpg';
?>
<script language="javascript" type="text/javascript">
	window.onload=function()
	{
		document.getElementById('nome').focus();
		window.setTimeout( 'loadTrilhas()' , 1000 );
	}
	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		if (form.nome.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe o nome.', true ); ?>" );
			form.nome.focus();
			return false;
		}

		if (form.cor.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe a cor.', true ); ?>" );
			form.cor.focus();
			return false;
		}
		
		submitform(pressbutton);
	}

	function loadTrilhas()
	{
		var dados	= 'option=com_p22evento&format=raw&task=ajax';
			dados	+= '&tp=trilhas';
			dados	+= '&acao=loadTrilhas';
			dados	+= '&idevento=' + document.getElementById('idevento').value;

		ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
	}

	function loadSelect()
	{
		var selecttrilhas = document.getElementById('div_trilhas_select').innerHTML;
		if ( selecttrilhas.length )
			window.top.document.getElementById('div_trilhas').innerHTML = selecttrilhas;
	}
</script>
<style type="text/css">
	.icon-48-p22Palestra{ background-image: url('components/com_p22evento/images/palestras.png') }
	#system-message dt { display: none; }
	dd.notice , dd.error , dd.message{ margin-left: 0px; }
	dd.notice ul, dd.error ul, dd.message ul { padding: 9px !important; }
	dd.notice ul li, dd.error ul li, dd.message ul li { list-style: none; margin-left: 30px; }
</style>
<div class="padding">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div id="toolbar" class="toolbar">
				<table class="toolbar">
					<tbody>
						<tr>
							<td id="toolbar-cancel" class="button">
								<a class="toolbar" onclick="javascript: submitbutton('cancel')" href="#">
									<span title="<?php echo JText::_( 'Close' ); ?>" class="icon-32-cancel"></span>
									<?php echo JText::_( 'Close' ); ?>
								</a>
							</td>
							<td id="toolbar-apply" class="button">
								<a class="toolbar" onclick="javascript: submitbutton('apply')" href="#">
									<span title="<?php echo JText::_( 'Apply' ); ?>" class="icon-32-apply"></span>
									<?php echo JText::_( 'Apply' ); ?>
								</a>
							</td>
							<td id="toolbar-save" class="button">
								<a class="toolbar" onclick="javascript: submitbutton('save_continue')" href="#">
									<span title="<?php echo JText::_( 'Continue' ); ?>" class="icon-32-save"></span>
									<?php echo JText::_( 'Continue' ); ?>
								</a>
							</td>
							<td id="toolbar-save" class="button">
								<a class="toolbar" onclick="javascript: submitbutton('save')" href="#">
									<span title="<?php echo JText::_( 'Save' ); ?>" class="icon-32-save"></span>
									<?php echo JText::_( 'Save' ); ?>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="header icon-48-p22Palestra">
				Trilhas
			</div>
			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
	</div>
	<div class="clr"></div>
	<div id="element-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<form action="index3.php?option=com_p22evento&task=<?php echo $this->nome_plural; ?>&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
				<div class="col100">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Details' ); ?></legend>

						<table class="admintable" border="0">
							<tr>
								<td class="key">
									<label for="nome">
										<?php echo JText::_( 'Nome da Trilha' ); ?>:
									</label>
								</td>
								<td>
									<input class="inputbox" type="text" name="nome"
								   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="font_color">
										<?php echo JText::_( 'Cor' ); ?>:
									</label>
								</td>
								<td>
									<input type="text" size="7" name="cor" id="cor" class="inputbox" value="<?php echo $this->registro->cor; ?>" />

									<span onmousedown="jQuery.noConflict();jQuery(this).ColorPicker({
										onSubmit: function(hsb, hex, rgb, el) {
											hex=hex.toUpperCase();
											jQuery(document.getElementById('cor')).val('#'+hex);
											jQuery(el).ColorPickerHide();
											el.style.backgroundColor = '#'+hex
										},
										onBeforeShow: function () {
											jQuery(this).ColorPickerSetColor(document.getElementById('cor').value);
										}
									})"
									style="border: 1px solid silver; margin: 0pt; padding: 0pt; display: inline-block; cursor: pointer; background-color: <?php echo $this->registro->cor; ?>;" id="font_colorCOLORBOX">
										&nbsp;&nbsp;&nbsp;&nbsp;
									</span>
								</td>
							</tr>
							<tr>
								<td class="key">&nbsp;</td>
								<td>
									<div style="float: left">
										<input type="radio" name="published" id="published0" value="0" <?php echo ( $this->registro->published ) ? '' : 'checked'; ?>>
										<label for="published0">Não Publicado</label>
									</div>
									<div style="float: left">
										<input type="radio" name="published" id="published1" value="1" <?php echo ( $this->registro->published ) ? 'checked' : ''; ?>>
										<label for="published1">Publicado</label>
									</div>
									<div style="clear: both"></div>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
				<div id="div_trilhas_select" style="display:none"></div>
				<div class="clr"></div>
				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="controller" value="trilha" />
				<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>

			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
	</div>
</div>