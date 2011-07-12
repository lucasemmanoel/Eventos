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

JHTML::_('behavior.calendar');

$dt_inicio	= implode( '/' , array_reverse ( explode ( '-', $this->registro->data_inicio ) ) );
$dt_fim		= implode( '/' , array_reverse ( explode ( '-', $this->registro->data_fim ) ) );
?>
<script language="javascript" type="text/javascript">
	window.onload=function()
	{
		document.adminForm.nome.focus();
	}

	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		if (form.nome.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe o nome do evento.', true ); ?>" );
			form.nome.focus();
			return false;
		}

		submitform(pressbutton);
	}
</script>
<style type="text/css">
	.icon-48-p22Salas{ background-image: url('components/com_p22evento/images/salas.png') }
</style>
<form action="index.php" method="post" name="adminForm">
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			
			<table class="admintable" width="800px" border="0">
				<tr>
					<td class="key">
						<label for="nome">
							<?php echo JText::_( 'Nome' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="nome"
					   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
	<input type="hidden" name="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="id_local" value="<?php echo $this->idlocal; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="sala" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>