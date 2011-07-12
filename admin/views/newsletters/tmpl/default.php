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
	.icon-48-massmail { background-image: url('templates/khepri/images/header/icon-48-massmail.png') }
</style>
<form action="index.php" method="post" name="adminForm">
	<div class="col width-40">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Detalhes e Filtros' ); ?></legend>

				<table class="admintable" style="width:100%">
				<tr>
					<td class="key" width="30%">
						<label for="inscritos_t">
							<?php echo JText::_( 'Todos os Inscritos' ); ?>:
						</label>
					</td>
					<td>
						<input type="radio" checked="checked" name="inscritos" id="inscritos_t" value="t" />
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="inscritos_n">
							<?php echo JText::_( 'Cadastrados e não inscritos' ); ?>:
						</label>
					</td>
					<td>
						<input type="radio" name="inscritos" id="inscritos_n" value="n" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="html">
							<?php echo JText::_( 'Formato HTML' ); ?>:
						</label>
					</td>
					<td>
						<input type="checkbox" name="html" id="html" value="s" />
					</td>
				</tr>
				<tr>
			       <td class="key">
						<label for="bbc" title="<?php echo JText::_( 'Enviar como Blind Carbon Copy' ); ?>">
							<?php echo JText::_( 'Enviar como BCC' ); ?>:
						</label>
					</td>
					<td>
							<input type="checkbox" name="bcc" id="bcc" value="s" checked="checked" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="uf">
							<?php echo JText::_( 'Estado' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->select['UF']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="cidades">
							<?php echo JText::_( 'Cidades' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_cidades">
							<select size="10" name="cidades[]" disabled="true" style="width:220px">
								<option>-Selecione o Estado</option>
							</select>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_cidades">
							<img src="components/com_p22evento/images/loader.gif" alt="" />
						</div>
						<div style="clear:both"></div>
					</td>
				</tr>
				</table>
			</fieldset>
		</div>

		<div class="col width-60">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'E-mail' ); ?></legend>

				<table class="admintable">
				<tr>
					<td class="key">
						<label for="subject">
							<?php echo JText::_( 'Assunto' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="subject" id="subject" value="" size="70" />
					</td>
				</tr>
				<tr>
					<td valign="top" class="key">
						<label for="message">
							<?php echo JText::_( 'Mensagem' ); ?>:
						</label>
					</td>
					<td id="pane" >
						<textarea rows="20" cols="60" name="message" id="message" class="inputbox"></textarea>
					</td>
				</tr>
				</table>
			</fieldset>
		</div>
		<input type="hidden" name="option" value="com_p22evento" />
		<input type="hidden" name="controller" value="inscrito" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
</form>