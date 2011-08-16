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
<?php if ( $this->safe ) : ?>
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
			<form method="post" action="<?php echo JRoute::_('index2.php?option=com_p22evento'); ?>" name="certForm" onsubmit="return ( confirm('Tem certeza que deseja gerar este certificado?\n Esta ação não poderá ser revertida caso seu nome e CPF estejam errados.') )">
				<div class="header" style="padding-left:0px">
					Confirmação de Geração de Certificado
				</div>
				<div style="border:1px solid #eaeaea;margin-top:3px">
					<div style="padding:10px">
						<div style="font-size: 14px">
							Seu certificado ainda não foi gerado, pois é necessário que você confirme os dados que serão exibidos.
							<br /><br />
							Se os dados não forem conforme exibidos abaixo, não confirme a geração do certificado, pois esta ação é irreversível.
							<br /><br />
							O certificado a ser gerado é o de <strong><?php echo $this->registro->tipo; ?></strong>. Confirme os dados abaixo:
							<br /><br />
						</div>
						<div style="background-color:#F9F9F9;border:1px solid #eaeaea;width:620px;padding:10px;margin-left:auto;margin-right:auto">
							<table cellspacing="0" cellpadding="0" class="admintable" width="600px">
								<tr>
									<td class="key">Nome:</td>
									<td><?php echo $this->registro->nome; ?></td>
								</tr>
								<tr>
									<td class="key">CPF:</td>
									<td><?php echo $this->registro->cpf2; ?></td>
								</tr>
								<?php if ( $this->registro->tp_reg == 2 ) : ?>
								<tr>
									<td class="key">Palestra:</td>
									<td><?php echo $this->registro->palestra; ?></td>
								</tr>
								<?php endif; ?>
								<tr>
									<td colspan="2" class="key" style="text-align:center!important">
										<button class="button" type="submit">Confirmar</button>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="controller" value="certificado" />
				<input type="hidden" name="task" value="registra_certificado" />
				<input type="hidden" name="id" value="<?php echo $this->registro->id; ?>" />
				<input type="hidden" name="id_palestra" value="<?php echo $this->registro->id_palestra; ?>" />
				<input type="hidden" name="tp_reg" value="<?php echo $this->registro->tp_reg; ?>" />
				<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
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
<?php else: ?>
<div style="<?php echo $this->style; ?>;margin-left:auto;margin-right:auto;margin-top:20px;color:#333">
	<div style="<?php echo $this->style3; ?>"></div>
	<div style="<?php echo $this->style2; ?>">
		<img src="<?php echo $this->filepath; ?>" alt="" />
	</div>
	<div id="main_box" style="text-align:justify;float:left;position:absolute;line-height:<?php echo $this->registro->line_height; ?>px;width: <?php echo $this->registro->box_width; ?>px;margin-top: <?php echo $this->registro->margin_top; ?>px;margin-left: <?php echo $this->registro->margin_left; ?>px;font-size: <?php echo $this->registro->font_size; ?>px">
		<?php echo nl2br( $this->maintext ); ?>
	</div>
	<div id="cpf_box" style="text-align:justify;float:left;position:absolute;margin-top: <?php echo $this->registro->cpf_margin_top; ?>px;margin-left: <?php echo $this->registro->cpf_margin_left; ?>px;font-size: <?php echo $this->registro->cpf_font_size; ?>px">
		<strong>CPF:</strong> <?php echo $this->registro->cpf2; ?>
	</div>
	<div id="num_box" style="text-align:justify;float:left;position:absolute;margin-top: <?php echo $this->registro->num_margin_top; ?>px;margin-left: <?php echo $this->registro->num_margin_left; ?>px;font-size: <?php echo $this->registro->num_font_size; ?>px">
		<strong>Certifcado Número:</strong> <?php echo $this->registro->id_certificado; ?>
	</div>
</div>
<?php endif; ?>