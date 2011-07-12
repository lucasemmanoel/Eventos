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
	.icon-32-search{ background-image: url('components/com_p22evento/assets/images/toolbar/icon-32-search.png') }
</style>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div id="toolbar" class="toolbar">
				<table class="toolbar">
					<tbody>
						<tr>
							<td id="toolbar-certvalidate" class="button">
								<a class="toolbar" onclick="javascript:window.location.href='<?php echo JRoute::_( 'index.php?option=com_p22evento&task=certvalidate&Itemid=' . JRequest::getInt('Itemid') ); ?>'" href="#">
									<span title="Delete" class="icon-32-search"></span>
									Validar Certificado
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="header icon-48-p22Eventos">
				Eventos Disponíveis
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
			<form action="<?php echo JRoute::_('index.php?option=com_p22evento&Itemid=' . JRequest::getInt('Itemid') ); ?>" method="post" name="adminForm">
				<div id="editcell">
					<table class="adminlist">
						<thead>
							<tr style="background-color: #f2f2f2;">
								<th width="2%">
									&nbsp;
								</th>
								<th nowrap="nowrap" class="title" style="text-align:left">
									<?php echo JHTML::_('grid.sort',  'Nome do Evento', 'p.nome', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] ); ?>
								</th>
								<th width="20%" style="text-align:left"	>
									<?php echo JText::_( 'Local' ); ?>
								</th>
								<th width="30%" style="text-align:left">
									<?php echo JText::_( 'Descrição' ); ?>
								</th>
								<th width="20%" align="center">
									<?php echo JText::_( 'Período' ); ?>
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="5">
									<?php echo $this->pageNav->getListFooter(); ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$k = 0;
							$m = 1;
							for ($i=0, $n=count( $this->registros ); $i < $n; $i++)
							{
								$row		= &$this->registros[$i];
								$checked	= JHTML::_('grid.id',   $i, $row->id );
								$published 	= JHTML::_('grid.published', $row, $i );
								$link		= JRoute::_( 'index.php?option=com_p22evento&task=selevento&idevento='. $row->id . '&Itemid=' . JRequest::getInt('Itemid') );
								?>
								<tr class="<?php echo "row$k"; ?>">
									<td align="center">
										<?php echo $m; ?>
									</td>
									<td>
										<a href="<?php echo $link; ?>"><?php echo $row->nome; ?></a>
									</td>
									<td>
										<?php echo $row->local; ?>
									</td>
									<td>
										<?php echo $row->descricao; ?>
									</td>
									<td align="center">
										<?php echo $this->periodo[ $row->id ]; ?>
									</td>
								</tr>
								<?php
								$k = 1 - $k;
								$m++;
							}
							?>

						</tbody>
					</table>
				</div>

				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
				<input type="hidden" name="controller" value="evento" />
				<input type="hidden" name="filter_order" value="<?php echo $this->lists[ 'order' ]; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists[ 'order_Dir' ]; ?>" />
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