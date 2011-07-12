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
<div style="<?php echo $this->style; ?>">
	<div id="main_box" style="border:2px dotted #EAEAEA;text-align:justify;float:left;position:absolute;line-height:<?php echo $this->registro->line_height; ?>px;width: <?php echo $this->registro->box_width; ?>px;margin-top: <?php echo $this->registro->margin_top; ?>px;margin-left: <?php echo $this->registro->margin_left; ?>px;font-size: <?php echo $this->registro->font_size; ?>px">
		<?php echo nl2br( $this->maintext ); ?>
	</div>
	<div id="cpf_box" style="border:2px dotted #EAEAEA;text-align:justify;float:left;position:absolute;margin-top: <?php echo $this->registro->cpf_margin_top; ?>px;margin-left: <?php echo $this->registro->cpf_margin_left; ?>px;font-size: <?php echo $this->registro->cpf_font_size; ?>px">
		<strong>CPF:</strong> 999.999.999-99
	</div>
	<div id="num_box" style="border:2px dotted #EAEAEA;text-align:justify;float:left;position:absolute;margin-top: <?php echo $this->registro->num_margin_top; ?>px;margin-left: <?php echo $this->registro->num_margin_left; ?>px;font-size: <?php echo $this->registro->num_font_size; ?>px">
		<strong>Certifcado Número:</strong> 1.233
	</div>
</div>