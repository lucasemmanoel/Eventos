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
	.icon-48-p22Eventos{ background-image: url('components/com_p22evento/images/eventos.png') }
</style>
<form action="index.php" method="post" name="adminForm">
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
				<td nowrap="nowrap">
					<select name="filtro_ano" id="filtro_ano" class="inputbox" size="1" onchange="submitform( );">
						<?php
						if ( $this->lists[ 'filtro_ano' ] == 0  )
						{
							?>
							<option value="0"  selected="selected">- Selecione um ano -</option>
							<?php
						}
						else
						{
							?>
							<option value="">- Selecione um ano -</option>
							<?php
						}
						?>


						<?php
						for ($i=0, $n=count( $this->listaAno ); $i < $n; $i++)
						{
							$row = &$this->listaAno[ $i ];

							if ( $this->lists[ 'filtro_ano' ] == $row->ano )
							{
								echo "<option value='" . $row->ano . "' selected='selected'>" . $row->ano . "</option>";
							}
							else
							{
								echo "<option value='" . $row->ano . "'>" . $row->ano . "</option>";
							}
						}
						?>
					</select>
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
						<?php echo JHTML::_('grid.sort',  'Nome do Evento', 'p.nome', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] ); ?>
					</th>
					<th width="20%" style="text-align:left"	>
						<?php echo JText::_( 'Local' ); ?>
					</th>
					<th width="30%" style="text-align:left">
						<?php echo JText::_( 'Descrição' ); ?>
					</th>
					<th width="4%" align="center">
						<?php echo JText::_( 'Ano' ); ?>
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
					<td colspan="9">
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
					$link		= JRoute::_( 'index.php?option=com_p22evento&task=evento&idevento='. $row->id );
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center">
							<?php echo $m; ?>
						</td>
						<td>
							<?php echo $checked; ?>
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
							<?php echo $row->ano; ?>
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
					$m++;
				}
				?>
			
			</tbody>
		</table>
	</div>
	
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="evento" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists[ 'order' ]; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists[ 'order_Dir' ]; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>