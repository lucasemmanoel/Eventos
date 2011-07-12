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

	function formatadata(campo, e)
	{
		//	var tecla = e.keyCode;
		var tecla = (window.event) ? event.keyCode : e.which;

		//alert (tecla);
		var vr = new String(campo.value);
		vr = vr.replace("/", "");
		vr = vr.replace("/", "");
		vr = vr.replace("/", "");
		tam = vr.length + 1;
		if (tecla != 8)
		{
			if (tam > 0 && tam < 2)
				campo.value = vr.substr(0, 2) ;
			if (tam > 2 && tam < 4)
				campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2);
			if (tam > 4 && tam < 7)
				campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2) + '/' + vr.substr(4, 7);
		}
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
		if (form.id_local.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe o local do evento.', true ); ?>" );
			form.id_local.focus();
			return false;
		}
		else if (form.ano.value.length == 0) {
			alert( "<?php echo JText::_( 'Informe o ano do evento.', true ); ?>" );
			form.ano.focus();
			return false;
		}

		submitform(pressbutton);
	}
</script>
<style type="text/css">
	.icon-48-p22Eventos{ background-image: url('components/com_p22evento/images/eventos.png') }
</style>
<form action="index.php" method="post" name="adminForm">
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			
			<table class="admintable" width="800px" border="0">
				<tr>
					<td class="key">
						<label for="nome">
							<?php echo JText::_( 'Nome do Evento' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="nome"
					   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_local">
							<?php echo JText::_( 'Local' ); ?>:
						</label>
					</td>
					<td>
						<div style="float: left" id="p22SelectLocais">
							<?php echo $this->lists['locais']; ?>
						</div>
						<div style="float: left; margin-left: 8px; margin-top: 3px;">
							<a href="javascript:addLocal()">
								Adicionar
							</a>
						</div>
						<div style="float: left; margin-left: 8px;visibility: hidden" id="p22loader">
							<img src="components/com_p22evento/images/load.gif" border="0" />
						</div>
						<div class="clr"></div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data_inicio">
							<?php echo JText::_( 'Data Inícial' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="data_inicio"
                        id="data_inicio" size="10" maxlength="10" value="<?php echo $dt_inicio; ?>"
						onKeyUp="formatadata(this,event)"
						/>

                        <input type="reset" class="button" value="..." title="Selecione a data"
                        onclick="return showCalendar('data_inicio','%d/%m/%Y');" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="dataFim">
							<?php echo JText::_( 'Data Final' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="data_fim"
                        id="data_fim" size="10" maxlength="10" value="<?php echo $dt_fim; ?>"
						onKeyUp="formatadata(this,event)" />

                        <input type="reset" class="button" value="..." title="Selecione a data"
                        onclick="return showCalendar('data_fim','%d/%m/%Y');" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="ano">
							<?php echo JText::_( 'Ano' ); ?>:
						</label>
					</td>
					<td>
						<select name="ano" id="ano">
							<option value="">Selecione o ano do Evento</option>
							<?php
							$ano = date('Y');
							
							if( date('Y') == $this->registro->ano )
							{
								$chkAnoAtual = 'selected="true"';
							}
							else
							{
								$chkAnoAtual = '';
								$chkOutroAno = '';
							}
							?>
							<option value="<?php echo $ano; ?>" <?php echo $chkAnoAtual; ?>><?php echo $ano; ?></option>
							<?php

							for( $a = 0 ; $a < 2 ;  $a++ )
							{
								$ano++;
								if( !empty( $chkOutroAno ) )
								{
									if( $ano == $this->registro->ano )
									{
										$option = 'selected';
									}
									else
									{
										$option = '';
									}
								}
								else
								{
									$option = '';
								}
							?>
							<option value="<?php echo $ano; ?>" <?php echo $option; ?>><?php echo $ano; ?></option>
							<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="descricao">
							<?php echo JText::_( 'Descrição' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="descricao"
                        id="descricao" size="80" value="<?php echo $this->registro->descricao; ?>" />
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="evento" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>