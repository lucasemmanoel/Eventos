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

$img = ( $this->palestra->tipo == 1 ) ? 'minicursos.gif' : 'palestras.png' ;
$tipo = ( $this->palestra->tipo == 1 ) ? 'Mini-curso' : 'Palestra' ;
?>
<div>
	<div style="float:left">
		<h2><?php echo $tipo; ?></h2>
	</div>
	<div style="float:right">
		<img src="./components/com_p22evento/assets/images/<?php echo $img; ?>" alt="" />
	</div>
	<div style="clear:both"></div>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Palestra: </strong> <span id="box_desc_nome"><?php echo $this->palestra->palestra; ?></span>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Palestrante: </strong> <span id="box_desc_palestrante"><?php echo $this->palestra->palestrante; ?></span>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Trilha: </strong> <span id="box_desc_trilha"><?php echo $this->palestra->trilha; ?></span>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Horário: </strong>
	<span id="box_desc_horario">
		<?php echo implode( '/' , array_reverse( explode( '-' , $this->palestra->dia ) ) ); ?>
		das
		<?php echo JString::substr( $this->palestra->hora , 0 , 2 ); ?><?php echo JString::substr( $this->palestra->hora , 2 , 3 ); ?>
		/
		<?php echo JString::substr( $this->palestra->hora , 0 , 2 ) + 1; ?><?php echo JString::substr( $this->palestra->hora , 2 , 3 ); ?>
	</span>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Descrição: </strong>
	<br /><br />
	<span id="box_desc_desc"><?php echo nl2br( $this->palestra->resumo ); ?></span>
</div>
<div style="padding:8px;border-bottom:1px solid #333">
	<strong>Mini-currículo: </strong>
	<br /><br />
	<span id="box_desc_corriculo">
		<?php echo nl2br( $this->palestra->curriculo ); ?>
	</span>
</div>
<div style="text-align: right">
	<button type="button" onclick="this.blur();showThem('desc_pop','<?php echo $this->palestra->id_grade; ?>');return true;">Fechar</button>
</div>