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
.icon-48-p22Certificados{ background-image: url('components/com_p22evento/images/certificados.png') }
.cert_menu_box {
	float: left;
	border: 1px solid #CCC;
	width: 103px;
	padding: 4px;
	background-color: #F7F7F7;
}
.cert_menu_option {
	float: left;
	padding: 4px;
	width: 84px;
	border: 1px solid #CCC;
	padding: 8px;
	margin: 2px 0 2px 0;
	background-color: #EAEAEA;
	cursor: pointer;
}
.cert_menu_option:hover {
	background-color: #DDD;
}
.cert_menu_option:hover a {
	color: #0000FF;
}
.cert_menu_option a {
	color: #CCC;
}
.option_active {
	background-color: #DDD;
	font-weight: bold;
}
.option_active a {
	color: #0000FF;
}
.cert_menu_option a:hover,
.cert_menu_option a:focus,
.cert_menu_option a:active,
.option_active a:hover,
.option_active a:focus,
.option_active a:active {
	text-decoration: none;
}
.cert_menu_main {
	float: left;
	margin-left: 3px;
	width: 820px;
}
.cert_stylebox {
	float:left;
	width: 273px;
}
.margin-value, .margin-type, .buttons {
	float: left;
}
.buttons {
	margin-top: -5px;
	float:right;
}
.cert_view_box {
	border-top: 3px solid #CCC;
	float: left;
	margin-left: 10px
}
table.admintable td.key, table.admintable td.paramlist_key {
	width:110px !important;
	font-size: 10px;
}
button {
	font-size:10px;
	padding: 0 6px;
	border: 1px solid #CCC;
}
</style>
<script type="text/javascript">
function inscreaseDecreaseValue( tp , name , box , stylename , inscrease )
{
	var valueNow = document.getElementById( tp + '_' + name ).value;

	if ( inscrease )
		valueNow++;
	else
		valueNow--;

	document.getElementById( tp + '_' + name ).value = valueNow;
	document.getElementById( tp + '_' + name + '_value' ).innerHTML = valueNow;

	switch( stylename )
	{
		case 'width':
			document.getElementById( box ).style.width = valueNow + 'px';
			break;
		case 'font-size':
			document.getElementById( box ).style.fontSize = valueNow + 'px';
			break;
		case 'line-height':
			document.getElementById( box ).style.lineHeight = valueNow + 'px';
			break;
		case 'margin-top':
			document.getElementById( box ).style.marginTop = valueNow + 'px';
			break;
		case 'margin-left':
			document.getElementById( box ).style.marginLeft = valueNow + 'px';
			break;
	}
}
function submitCertView( view )
{
	var form = document.adminForm;

	form.task.value		= 'setCertView';
	form.tp_view.value	= view;
	submitform();
}
</script>
<form action="index.php?option=com_p22evento&controller=certificado&task=certificadovisual&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
	<div>
		<div class="cert_menu_box">
			<div class="cert_menu_option <?php echo ( $this->view == 'inscritos' || empty( $this->view ) ) ? 'option_active' : ''; ?>" onclick="javascript:submitCertView('inscritos')">
				<a href="javascript:submitCertView('inscritos')">
					Inscritos
				</a>
			</div>
			<div class="cert_menu_option <?php echo ( $this->view == 'colaboradores' ) ? 'option_active' : ''; ?>" onclick="javascript:submitCertView('colaboradores')">
				<a href="javascript:submitCertView('colaboradores')">
					Colaboradores
				</a>
			</div>
			<div class="cert_menu_option <?php echo ( $this->view == 'palestrantes' ) ? 'option_active' : ''; ?>" onclick="javascript:submitCertView('palestrantes')">
				<a href="javascript:submitCertView('palestrantes')">
					Palestrantes
				</a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="cert_menu_main">
			<div class="cert_style_box" style="margin-left: 10px">
				<h1><?php echo ( empty( $this->view ) ) ? 'Inscritos' : ucfirst( $this->view ); ?></h1>
			</div>
			<div class="cert_style_box">
				<div class="cert_stylebox">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Texto Principal' ); ?></legend>

						<table class="admintable" width="100%" border="0">
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Tamanho do Campo' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="tp_box_width_value"><?php echo $this->registro->box_width; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'box_width' , 'main_box' , 'width' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'box_width' , 'main_box' , 'width' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Tamanho da Fonte' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="tp_font_size_value"><?php echo $this->registro->font_size; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'font_size' , 'main_box' , 'font-size' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'font_size' , 'main_box' , 'font-size' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Espaço entre as linhas' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="tp_line_height_value"><?php echo $this->registro->line_height; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'line_height' , 'main_box' , 'line-height' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'line_height' , 'main_box' , 'line-height' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem do Topo' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="tp_margin_top_value"><?php echo $this->registro->margin_top; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'margin_top' , 'main_box' , 'margin-top' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'margin_top' , 'main_box' , 'margin-top' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem Esquerda' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="tp_margin_left_value"><?php echo $this->registro->margin_left; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'margin_left' , 'main_box' , 'margin-left' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('tp' , 'margin_left' , 'main_box' , 'margin-left' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
						</table>
						<input type="hidden" name="tp_box_width" id="tp_box_width" value="<?php echo $this->registro->box_width; ?>" />
						<input type="hidden" name="tp_font_size" id="tp_font_size" value="<?php echo $this->registro->font_size; ?>" />
						<input type="hidden" name="tp_line_height" id="tp_line_height" value="<?php echo $this->registro->line_height; ?>" />
						<input type="hidden" name="tp_margin_top" id="tp_margin_top" value="<?php echo $this->registro->margin_top; ?>" />
						<input type="hidden" name="tp_margin_left" id="tp_margin_left" value="<?php echo $this->registro->margin_left; ?>" />
					</fieldset>
				</div>
				<div class="cert_stylebox">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'CPF' ); ?></legend>

						<table class="admintable" width="100%" border="0">
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Tamanho da Fonte' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="cpf_font_size_value"><?php echo $this->registro->cpf_font_size; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'font_size' , 'cpf_box' , 'font-size' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'font_size' , 'cpf_box' , 'font-size' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem do Topo' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="cpf_margin_top_value"><?php echo $this->registro->cpf_margin_top; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'margin_top' , 'cpf_box' , 'margin-top' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'margin_top' , 'cpf_box' , 'margin-top' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem Esquerda' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="cpf_margin_left_value"><?php echo $this->registro->cpf_margin_left; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'margin_left' , 'cpf_box' , 'margin-left' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('cpf' , 'margin_left' , 'cpf_box' , 'margin-left' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
						</table>
						<input type="hidden" name="cpf_margin_top" id="cpf_margin_top" value="<?php echo $this->registro->cpf_margin_top; ?>" />
						<input type="hidden" name="cpf_margin_left" id="cpf_margin_left" value="<?php echo $this->registro->cpf_margin_left; ?>" />
						<input type="hidden" name="cpf_font_size" id="cpf_font_size" value="<?php echo $this->registro->cpf_font_size; ?>" />
					</fieldset>
				</div>
				<div class="cert_stylebox">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Número do Certificado' ); ?></legend>

						<table class="admintable" width="100%" border="0">
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Tamanho da Fonte' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="num_font_size_value"><?php echo $this->registro->num_font_size; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'font_size' , 'num_box' , 'font-size' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'font_size' , 'num_box' , 'font-size' , false)"> - </button>
									</div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem do Topo' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="num_margin_top_value"><?php echo $this->registro->num_margin_top; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'margin_top' , 'num_box' , 'margin-top' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'margin_top' , 'num_box' , 'margin-top' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="tipo">
										<?php echo JText::_( 'Margem Esquerda' ); ?>:
									</label>
								</td>
								<td>
									<div class="margin-value" id="num_margin_left_value"><?php echo $this->registro->num_margin_left; ?></div>
									<div class="margin-type">px</div>
									<div class="buttons">
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'margin_left' , 'num_box' , 'margin-left' , true)"> + </button>
										<button type="button" onclick="inscreaseDecreaseValue('num' , 'margin_left' , 'num_box' , 'margin-left' , false)"> - </button>
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
						</table>
						<input type="hidden" name="num_margin_top" id="num_margin_top" value="<?php echo $this->registro->num_margin_top; ?>" />
						<input type="hidden" name="num_margin_left" id="num_margin_left" value="<?php echo $this->registro->num_margin_left; ?>" />
						<input type="hidden" name="num_font_size" id="num_font_size" value="<?php echo $this->registro->num_font_size; ?>" />
					</fieldset>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="cert_view_box">
			<?php echo $this->loadTemplate( 'view' ); ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<input type="hidden" name="controller" value="certificado" />
	<input type="hidden" name="tp_view" value="<?php echo $this->view; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>