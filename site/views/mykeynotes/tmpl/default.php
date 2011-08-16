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
	.icon-48-p22Palestras{ background-image: url('/./administrator/components/com_p22evento/images/palestras.png') }
</style>
<div style="width:82%">
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
							<td id="toolbar-Tem certeza que deseja excluir?" class="button">
								<a class="toolbar" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('Selecione alguma palestra para deletá-la.');}else{if(confirm('Tem certeza que deseja excluir o(a) minicurso?')){submitbutton('remove');}}" href="#">
									<span title="Delete" class="icon-32-delete"></span>
									Delete
								</a>
							</td>
							<td id="toolbar-new" class="button">
								<a class="toolbar" onclick="" href="javascript:window.location.href='<?php echo JRoute::_( 'index.php?option=com_p22evento&task=palestra&tmpl=component&idevento='. intval( $this->idevento ) .'&palestraid='. intval( $row->id ) .'&Itemid=' . JRequest::getInt('Itemid') ); ?>'">
									<span title="Novo" class="icon-32-new"></span>
									Novo
								</a>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
			<div class="header icon-48-p22Palestras">
				Minhas Palestras / Mini-cursos
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
			<form action="<?php echo JRoute::_('index2.php?option=com_p22evento&task=mykeynotes&idevento='. intval( $this->idevento ) .'&Itemid=' . JRequest::getInt('Itemid') ); ?>" method="post" name="adminForm">
				<div id="editcell">
					<table class="adminlist">
						<thead>
							<tr style="background-color: #f2f2f2;">
								<th width="2%">
									&nbsp;
								</th>
								<th width="2%">
									<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->registros ); ?>);" />
								</th>
								<th nowrap="nowrap" class="title" style="text-align:left">
									<?php echo JText::_( 'Nome' ); ?>
								</th>
								<th width="10%" style="text-align: left">
									<?php echo JText::_( 'Tipo' ); ?>
								</th>
								<th width="3%" style="text-align: left">
									<?php echo JText::_( 'Trilha' ); ?>
								</th>
								<th width="4%" style="text-align: left">
									<?php echo JText::_( 'Nível' ); ?>	
								</th>
								<th width="4%" style="text-align: left">
									<?php echo JText::_( 'Aprovado' ); ?>
								</th>
								<th width="4%" style="text-align: left">
									<?php echo JText::_( 'Avaliado' ); ?>
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="8">
									<?php echo $this->pageNav->getListFooter(); ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$k = 0;
							for ($i = 0, $n=count( $this->registros ); $i < $n; $i++)
							{
								$row			= &$this->registros[$i];
								$link			= JRoute::_( 'index2.php?option=com_p22evento&task=palestra&idevento='. intval( $this->idevento ) .'&palestraid='. intval( $row->id ) .'&Itemid=' . JRequest::getInt('Itemid') );
								$checked		= JHTML::_('grid.id',   $i, $row->id );
								$image			= ( $row->aprovado ) ? 'tick.png' : 'publish_x.png';
								$aprovado		= JHTML::_('image.administrator',  $image , 'administrator/images/' );
								switch( $row->nivel )
								{
									case 0:
									default:
										$nivel = 'Básico';
										break;
									case 1:
										$nivel = 'Intermediário';
										break;
									case 2:
										$nivel = 'Avançado';
										break;
								}
								?>
								<tr class="<?php echo "row$k"; ?>">
									<td align="center">
										<?php echo $this->pageNav->getRowOffset( $i ); ?>
									</td>
									<td>
										<?php
										if ( !$this->pontuacao[ $row->id ] ) :
											echo $checked;
										endif;
										?>
									</td>
									<td>
										<a href="<?php echo $link; ?>"><?php echo $row->nome; ?></a>
									</td>
									<td>
										<?php echo ( $row->tipo ) ? 'Mini-curso' : 'Palestra'; ?>
									</td>
									<td>
										<div style="float:left" title="<?php echo $row->trilha; ?>">
											<div style="width: 30px; height: 20px; background-color: <?php echo $row->cor; ?>">&nbsp;</div>
										</div>
										<div style="clear: both"></div>
									</td>
									<td align="center">
										<?php echo $nivel; ?>
									</td>
									<td align="center">
										<?php echo $aprovado; ?>
									</td>
									<td align="center">
										<?php echo ( $this->pontuacao[ $row->id ] ) ? '<a href="'. JRoute::_('index2.php?option=com_p22evento&task=avaliacao&idevento='. $this->idevento .'&palestraid='. $row->id .'&Itemid=' . JRequest::getInt('Itemid') ) .'" title="Nota: '. $this->pontuacao[ $row->id ] .' '. $this->avaliado[ $row->id ] .'">Sim</a>' : 'Não'; ?>
									</td>
								</tr>
								<?php
								$k = 1 - $k;
							}
							?>
						</tbody>
					</table>
				</div>

				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="task" value="mykeynotes" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
				<input type="hidden" name="idevento" value="<?php echo $this->idevento; ?>" />
				<input type="hidden" name="controller" value="palestra" />
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