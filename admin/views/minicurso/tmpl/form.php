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

		if (form.id_trilha.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe o trilha.', true ); ?>" );
			form.id_trilha.focus();
			return false;
		}
		
		if (form.id_palestrante.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe o palestrante.', true ); ?>" );
			form.id_palestrante.focus();
			return false;
		}

		if (form.nivel.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe o nível.', true ); ?>" );
			form.nivel.focus();
			return false;
		}
		
		submitform(pressbutton);
	}
</script>
<style type="text/css">
	.icon-48-p22Palestra{ background-image: url('components/com_p22evento/images/palestras.png') }
</style>
<table>
	<tr>
		<td>
			<h1><?php echo $this->titleRegistro; ?></h1>
		</td>
	</tr>
</table>
<form action="index.php" method="post" name="adminForm">
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			
			<table class="admintable" width="800px" border="0">
				<tr>
					<td class="key">
						<label for="nome">
							<?php echo JText::_( 'Nome do Mini-curso' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="nome"
					   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_trilha">
							<?php echo JText::_( 'Trilha' ); ?>:
						</label>
					</td>
					<td>
						<div style="float: left" id="div_trilhas">
							<?php echo $this->select['trilha']; ?>
						</div>
						<div style="float: left; margin-left: 12px; margin-top: 3px">
							<a title="Gerenciar Trilhas" class="modal" href="index3.php?option=com_p22evento&task=trilhas&idevento=<?php echo $this->idevento; ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
								Gerenciar
							</a>
						</div>
						<div style="float: clear"></div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_palestra">
							<?php echo JText::_( 'Palestrante' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->select['palestrante']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="nivel">
							<?php echo JText::_( 'Nível' ); ?>:
						</label>
					</td>
					<td>
						<select name="nivel" id="nivel">
							<option value="0">Básico</option>
							<option value="1">Intermediário</option>
							<option value="2">Avançado</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="resumo">
							<?php echo JText::_( 'Resumo' ); ?>:
						</label>
						<div style="text-align: right;font-size: 10px;color: #ccc">
							Vale como pontuação positiva na avaliação do Mini-curso.
						</div>
					</td>
					<td>
						<textarea name="resumo" id="resumo" cols="69" rows="10"><?php echo $this->registro->resumo; ?></textarea>
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
	
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="minicurso" />
	<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>