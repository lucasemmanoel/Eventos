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
<script type="text/javascript">
window.onload=function()
{
	window.setTimeout( 'loadTrilhas()' , 1000 );
}
function loadTrilhas()
{
	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&tp=trilhas';
		dados	+= '&acao=loadTrilhas';
		dados	+= '&idevento=' + document.getElementById('idevento').value;

	ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
}

function loadSelect()
{
	var selecttrilhas = document.getElementById('div_trilhas_select').innerHTML;
	if ( selecttrilhas.length )
		window.top.document.getElementById('div_trilhas').innerHTML = selecttrilhas;
}
</script>
<style type="text/css">
	.icon-48-p22Palestra{ background-image: url('components/com_p22evento/images/palestras.png') }
	#system-message dt { display: none; }
	dd.notice , dd.error , dd.message{ margin-left: 0px; }
	dd.notice ul, dd.error ul, dd.message ul { padding: 9px !important; }
	dd.notice ul li, dd.error ul li, dd.message ul li { list-style: none; margin-left: 30px; }
</style>
<div class="padding">
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
							<td id="toolbar-publish" class="button">
								<a class="toolbar" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('Please make a selection from the list to publish'); ?>');}else{  submitbutton('publish')}" href="#">
									<span title="<?php echo JText::_( 'Publish' ); ?>" class="icon-32-publish"></span>
									<?php echo JText::_( 'Publish' ); ?>
								</a>
							</td>
							<td id="toolbar-unpublish" class="button">
								<a class="toolbar" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('Please make a selection from the list to unpublish'); ?>');}else{  submitbutton('unpublish')}" href="#">
									<span title="<?php echo JText::_( 'Unpublish' ); ?>" class="icon-32-unpublish"></span>
									<?php echo JText::_( 'Unpublish' ); ?>
								</a>
							</td>
							<td id="toolbar-Tem certeza que deseja excluir o(a) palestra?" class="button">
								<a class="toolbar" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('Please make a selection from the list to delete'); ?>');}else{if(confirm('<?php echo JText::_('Tem certeza que deseja excluir o(a) trilha?'); ?>')){submitbutton('remove');}}" href="#">
									<span title="<?php echo JText::_( 'Delete' ); ?>" class="icon-32-delete"></span>
									<?php echo JText::_( 'Delete' ); ?>
								</a>
							</td>
							<td id="toolbar-edit" class="button">
								<a class="toolbar" onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('Please make a selection from the list to edit'); ?>');}else{ hideMainMenu(); submitbutton('edit')}" href="#">
									<span title="<?php echo JText::_( 'Edit' ); ?>" class="icon-32-edit"></span>
									<?php echo JText::_( 'Edit' ); ?>
								</a>
							</td>
							<td id="toolbar-new" class="button">
								<a class="toolbar" onclick="javascript:hideMainMenu(); submitbutton('add')" href="#">
								<span title="<?php echo JText::_( 'New' ); ?>" class="icon-32-new"></span>
								<?php echo JText::_( 'New' ); ?>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="header icon-48-p22Palestra">
				Trilhas
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
			<form action="index3.php?option=com_p22evento&task=<?php echo $this->nome_plural; ?>&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
				<div id="editcell">
					<table>
						<tr>
							<td align="left" width="100%">
								<?php echo JText::_( 'Filter' ); ?>:
								<input type="text" name="search" id="search" value="<?php echo $this->lists[ 'search' ];?>" class="text_area" onchange="document.adminForm.submit();" />
								<button onclick="this.form.submit();">
									<?php echo JText::_( 'Go' ); ?>
								</button>
								<button onclick="document.getElementById('search').value='';this.form.submit();">
									<?php echo JText::_( 'Limpar filtro' ); ?>
								</button>
							</td>
						</tr>
					</table>
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
									<?php echo JHTML::_('grid.sort',  'Nome', 'p.nome', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , 'trilhas' ); ?>
								</th>
								<th width="4%" align="center">
									<?php echo JText::_( 'Publicado' ); ?>
								</th>
								<th width="4%" align="center">
									<?php echo JText::_( 'ID' ); ?>
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
							for ($i = 0, $n=count( $this->registros ); $i < $n; $i++)
							{
								$row		= &$this->registros[$i];
								$checked	= JHTML::_('grid.id',   $i, $row->id );
								$published 	= JHTML::_('grid.published', $row, $i );
								$link		= JRoute::_( 'index3.php?option=com_p22evento&controller=trilha&idevento='. intval( $this->idevento ) .'&task=edit&cid[]='. $row->id );
								?>
								<tr class="<?php echo "row$k"; ?>">
									<td align="center">
										<?php echo $this->pageNav->getRowOffset( $i ); ?>
									</td>
									<td>
										<?php echo $checked; ?>
									</td>
									<td>
										<a href="<?php echo $link; ?>"><?php echo $row->nome; ?></a>
									</td>
									<td align="center">
										<?php echo $published; ?>
									</td>

									<td align="center">
										<?php echo $row->id; ?>
									</td>
								</tr>
								<?php
								$k = 1 - $k;
							}
							?>

						</tbody>
					</table>
				</div>
				<div id="div_trilhas_select" style="display:none"></div>
				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="task" value="<?php echo $this->nome_plural; ?>" />
				<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="controller" value="trilha" />
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