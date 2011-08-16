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

?>
<style type="text/css">
	.icon-48-p22Certificado{ background-image: url('/./administrator/components/com_p22evento/images/certificados.png') }
</style>
<script type="text/javascript">
function checkCert()
{
	var token		= document.getElementById('token').value;
	var cert_num	= document.getElementById('cert_num');

	if ( cert_num.value.length == 0 )
	{
		alert( 'Informe o número do certificado' );
		cert_num.focus();
		return;
	}

	cert_num.readOnly = true;
	buttonText( 'button_send' , 'Aguarde...' , true );

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&'+token+'=1';
		dados	+= '&acao=check_certificado';
		dados	+= '&cert_num=' + cert_num.value;

	ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
}
function cleanForm()
{
	var cert_num	= document.getElementById('cert_num');
	cert_num.value	= '';
	document.getElementById('result_field').style.display	= 'none';
	document.getElementById('result_field2').style.display	= 'none';
	document.getElementById('td_tipo').innerHTML			= '';
	document.getElementById('td_nome').innerHTML			= '';
	document.getElementById('td_cpf').innerHTML				= '';
	document.getElementById('td_palestra').innerHTML		= '';
	document.getElementById('td_tp_palestra').innerHTML		= '';
	document.getElementById('td_evento').innerHTML			= '';
	document.getElementById('td_periodo').innerHTML			= '';
	document.getElementById('tr_palestra').style.display	= 'none';

	cert_num.focus();
}
function buttonText(button,text,disabled)
{
	var el = document.getElementById( button );

	el.innerHTML = text;

	el.disabled = disabled;
}
</script>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div class="header icon-48-p22Certificado">
				<?php echo $this->title; ?>
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
			<div class="header" style="padding-left:0px">
				Validação de Certificado
			</div>
			<div style="border:1px solid #eaeaea;margin-top:3px">
				<div style="padding:10px">
					<div style="font-size: 13px">
						Você pode verificar a veracidade do certificado, e conferir se o mesmo foi realmente emitido pelo sistema.
						<br /><br />
						No próprio certificado existe um número. Informe-o abaixo e confira se os dados são verdadeiros com os que
						estão no certificado.
						<br /><br />
					</div>
					<div style="background-color:#F9F9F9;border:1px solid #eaeaea;padding:10px;margin-left:auto;margin-right:auto">
						<form method="post" action="index.php" name="certForm" onsubmit="return false">
							<table cellspacing="0" cellpadding="0" class="admintable">
								<tr>
									<td align="center">
										<input type="text" name="cert_num" size="20" class="inputbox" id="cert_num" value="" />
										<button type="button" class="button" id="button_send" onclick="checkCert()">Enviar</button>
									</td>
								</tr>
							</table>
							<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
						</form>
						<div style="display:none" id="result_field">
							<table cellspacing="0" cellpadding="0" class="admintable" width="100%">
								<tr>
									<td class="key">Tipo:</td>
									<td id="td_tipo"></td>
								</tr>
								<tr>
									<td class="key">Nome:</td>
									<td id="td_nome"></td>
								</tr>
								<tr>
									<td class="key">CPF:</td>
									<td id="td_cpf"></td>
								</tr>
								<tr id="tr_palestra" style="display:none">
									<td class="key" id="td_tp_palestra">Palestra:</td>
									<td id="td_palestra"></td>
								</tr>
								<tr id="tr_evento">
									<td class="key">Evento:</td>
									<td id="td_evento"></td>
								</tr>
								<tr id="tr_periodo">
									<td class="key">Período:</td>
									<td id="td_periodo"></td>
								</tr>
								<tr>
									<td colspan="2" class="key" style="text-align:center!important">
										<button class="button" type="button" onclick="cleanForm()">Limpar</button>
									</td>
								</tr>
							</table>
						</div>
						<div id="result_field2" style="margin-top:10px;display:none">
							<div style="border:1px solid red;background:#FFF;padding:10px;font-weight: bold;color:red">
								Este Certificado não está cadastrado no sistema.
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
	</div>
</div>