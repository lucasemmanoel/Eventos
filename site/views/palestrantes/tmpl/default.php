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
	.icon-48-p22Eventos{ background-image: url('administrator/components/com_p22evento/images/eventos.png') }
</style>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div class="header icon-48-p22Eventos">
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
			<div id="editcell">
				<div class="header" style="margin-left:0px!important;padding-left:0px!important;">
					Palestrantes Aprovados
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
	<div class="clr" style="margin-bottom:7px"></div>
	<div id="element-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div id="editcell">
				<?php
				for ($i=0, $n=count( $this->registros ); $i < $n; $i++)
				{
					$row = &$this->registros[$i];
					$seImgExists	= ( $row->avatar_img ) ? file_exists( JPATH_SITE . DS . $row->avatar_img ) : false;
					$avatar_img		= ( $seImgExists ) ? './' . $row->avatar_img : './administrator/components/com_p22evento/images/no_picture.jpg';
					?>
					<div style="padding:5px;border-bottom:1px dotted #DDD">
						<div style="float:left">
							<img src="<?php echo $avatar_img; ?>" alt="" />
						</div>
						<div style="float:left;margin-left:3px">
							<div style="float:left;clear:right;padding:10px;border-bottom:1px solid #DDD;width:100%">
								<div style="font-size: 16px;color:#333;font-weight: bold">
									<a href="<?php echo JRoute::_( JURI::base() . 'index.php?option=com_p22evento&pid=' . intval( $row->id_palestrante ) ); ?>">
										<?php echo $row->nome; ?>
									</a>
								</div>
								<div style="font-size: 12px;color:#333;margin-left:5px">
									<?php echo $row->profissao; ?>
								</div>
								<div style="font-size: 10px;color:#333;margin-left:5px">
									<?php echo $row->cidade; ?> - <?php echo $row->uf; ?>
								</div>
							</div>
							<div style="float:left;clear:both;padding:10px"><?php echo nl2br( $row->curriculo ); ?></div>
						</div>
						<div class="clr"></div>
					</div>
					<?php
				}
				?>
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