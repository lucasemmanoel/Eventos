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
$edit		= ( $this->registro->id ) ? true : false;

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// do field validation
		if (form.dia.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe o tipo.', true ); ?>" );
			form.tipo.focus();
			return false;
		}
		if (form.dia.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe o dia.', true ); ?>" );
			form.dia.focus();
			return false;
		}
		if (form.id_palestra.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe a palestra.', true ); ?>" );
			form.id_palestra.focus();
			return false;
		}
		if (form.id_sala.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe a sala.', true ); ?>" );
			form.id_sala.focus();
			return false;
		}
		if (form.hora.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe a hora.', true ); ?>" );
			form.hora.focus();
			return false;
		}

		submitform(pressbutton);
	}

	window.addEvent("domready",function(){
		chkInfosGrade( '<?php echo $edit; ?>','dias','<?php echo $this->tipo_palestra; ?>','<?php echo $this->registro->id_palestra; ?>','<?php echo $this->registro->id_sala; ?>','<?php echo $this->registro->hora; ?>')
	});
</script>
<style type="text/css">
	.icon-48-p22Grade{ background-image: url('components/com_p22evento/images/grade_palestras.png') }
</style>
<form action="index.php" method="post" name="adminForm">
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			
			<table class="admintable" width="800px" border="0">
				<tr>
					<td class="key">
						<label for="tipo">
							<?php echo JText::_( 'Tipo' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->select['tipos']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="dia">
							<?php echo JText::_( 'Dia' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_dias">
							<?php echo $this->select['dias']; ?>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_dias">
							<img src="components/com_p22evento/images/loader.gif" alt="" border="" />
						</div>
						<div style="clear:both"></div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_palestra">
							<?php echo JText::_( 'Palestra / Mini-curso' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_palestras">
							<select name="id_palestra" disabled="true">
								<option value="">-Selecione-</option>
							</select>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_palestras">
							<img src="components/com_p22evento/images/loader.gif" alt="" border="" />
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_sala">
							<?php echo JText::_( 'Sala' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_salas">
							<select name="id_palestra" disabled="true">
								<option value="">-Selecione-</option>
							</select>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_salas">
							<img src="components/com_p22evento/images/loader.gif" alt="" border="" />
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="hora">
							<?php echo JText::_( 'Hora' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_horas">
							<select name="id_palestra" disabled="true">
								<option value="">-Selecione-</option>
							</select>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_horas">
							<img src="components/com_p22evento/images/loader.gif" alt="" border="" />
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="hora">
							<?php echo JText::_( 'Publicado' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->select['published']; ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="grade" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>