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
	.icon-48-p22Inscritos{ background-image: url('components/com_p22evento/images/inscritos.png') }
</style>
<script type="text/javascript">
window.addEvent("domready",function(){
	searchInscritos( true , '' )
});
</script>
<form action="index.php?option=com_p22evento&task=<?php echo $this->nome_plural; ?>&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
	<div id="editcell">
		<div>
			<div style="float:left;margin-left: 10px">
				<h1>Confirmação de Presença de Inscritos</h1>
			</div>
			<div style="float:right;margin-right: 10px;margin-top:10px">
				<div style="float:right">
					<button style="width:200px" type="button" id="global_datauser_button" onclick="javascript:searchInscritos( true , '' )" title="Prepara o sistema para busca de inscritos. Caso não encontre algum registro, atualize neste botão.">Atualizar Relação de Inscritos</button>
				</div>
				<div style="float:right;margin-right: 7px;margin-top:2px;">
					<img src="components/com_p22evento/images/loader.gif" alt="" id="global_datauser_update" style="visibility:hidden" />
				</div>
			</div>
			<div style="clear:both"></div>
		</div>

		<fieldset class="adminform" id="campo_form_registros">
			<legend>Buscar Inscritos</legend>
			<div id="editcell">
				<table class="admintable" width="100%">
					<tr>
						<td class="key">
							<label for="nome">
								<?php echo JText::_( 'Tipo de Busca' ); ?>:
							</label>
						</td>
						<td>
							<div>
								<div style="float:left">
									<div style="float:left"><input onchange="showHideCampo('id')" checked type="radio" name="tipo_busca" id="tipo_buscaID" value="id" /></div>
									<div style="float:left;margin-top:4px;margin-left:3px"><label for="tipo_buscaID">Núm. de Inscrição</label></div>
								</div>
								<div style="float:left;margin-left:10px">
									<div style="float:left"><input onchange="showHideCampo('nome')" type="radio" name="tipo_busca" id="tipo_buscaNOME" value="nome" /></div>
									<div style="float:left;margin-top:4px;margin-left:3px"><label for="tipo_buscaNOME">Nome</label></div>
								</div>
								<div style="float:left;margin-left:10px">
									<div style="float:left"><input onchange="showHideCampo('cpf')" type="radio" name="tipo_busca" id="tipo_buscaCPF" value="cpf" /></div>
									<div style="float:left;margin-top:4px;margin-left:3px"><label for="tipo_buscaCPF">CPF</label></div>
								</div>
							</div>
							<div style="clear:both"></div>
						</td>
					</tr>
					<tr id="tr_id">
						<td class="key">
							<label for="id">
								<?php echo JText::_( 'Núm. de Inscrição' ); ?>:
							</label>
						</td>
						<td>
							<div style="float:left">
								<input type="text" name="id" size="11" id="id" value="" onkeyup="searchInscritos( false , 'id' )" />
							</div>
							<div id="loader_id" style="float:left;margin-left:5px;visibility:hidden">
								<img src="./components/com_p22evento/images/loader.gif" alt="Carregando..." title="Carregando..." />
							</div>
						</td>
					</tr>
					<tr id="tr_nome" style="display:none">
						<td class="key">
							<label for="nome">
								<?php echo JText::_( 'Nome' ); ?>:
							</label>
						</td>
						<td>
							<div style="float:left">
								<input type="text" name="nome" size="50" id="nome" value="" onkeyup="searchInscritos( false , 'nome' )" />
							</div>
							<div id="loader_nome" style="float:left;margin-left:5px;visibility:hidden">
								<img src="./components/com_p22evento/images/loader.gif" alt="Carregando..." title="Carregando..." />
							</div>
						</td>
					</tr>
					<tr id="tr_cpf" style="display:none">
						<td class="key">
							<label for="nome">
								<?php echo JText::_( 'CPF' ); ?>:
							</label>
						</td>
						<td>
							<div style="float:left"><input type="text" name="cpf" size="14" maxlength="14" id="cpf" onkeyup="FormataCpf(this,event);searchInscritos( false , 'cpf' )" value="" /></div>
							<div class="ajudaForm" style="float:left;margin-left:10px;margin-top:3px">Somente números</div>
							<div id="loader_cpf" style="float:left;margin-left:10px;visibility:hidden">
								<img src="./components/com_p22evento/images/loader.gif" alt="Carregando..." title="Carregando..." />
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" id="td_profs_result" style="border:1px solid #CCC"></td>
					</tr>
				</table>
			</div>
		</fieldset>
	</div>

	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>