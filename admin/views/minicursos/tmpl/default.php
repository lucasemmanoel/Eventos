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
	.icon-48-p22Palestras{ background-image: url('components/com_p22evento/images/palestras.png') }
</style>
<form action="index.php?option=com_p22evento&task=<?php echo $this->nome_plural; ?>&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
	<div id="editcell">
		<table>
			<tr>
				<td colspan="2">
					<h1><?php echo ucfirst( $this->nome_plural); ?></h1>
				</td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td align="left">
					<?php echo JText::_( 'Filter' ); ?>:
					<input type="text" name="search" id="search" value="<?php echo $this->lists[ 'search' ];?>" class="text_area" onchange="document.adminForm.submit();" />
					<button onclick="this.form.submit();">
						<?php echo JText::_( 'Go' ); ?>
					</button>
					<button onclick="document.getElementById('search').value='';this.form.submit();">
						<?php echo JText::_( 'Limpar filtro' ); ?>
					</button>
				</td>
				<td align="right">
					<div style="float: right;margin-left: 2px">
						<?php echo $this->lists['state']; ?>
					</div>
					<div style="float: right;margin-left: 2px">
						<?php echo $this->lists['nivel']; ?>
					</div>
					<div style="float: right;margin-left: 2px">
						<?php echo $this->lists['trilha']; ?>
					</div>
					<div style="float: right;margin-left: 2px">
						<?php echo $this->lists['palestrante']; ?>
					</div>
					<div style="clear:both"></div>
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
						<?php echo JHTML::_('grid.sort',  'Palestra', 'p.nome', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , $this->nome_plural ); ?>
					</th>
					<th nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JHTML::_('grid.sort',  'Palestrante', 'palestrante', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , $this->nome_plural ); ?>
					</th>
					<th width="20%" style="text-align: left">
						<?php echo JText::_( 'Trilha' ); ?>
					</th>
					<th width="12%">
						<?php echo JText::_( 'Registrado em' ); ?>
					</th>
					<th width="4%" style="text-align: left">
						<?php echo JHTML::_('grid.sort',  'Nível', 'p.nivel', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , $this->nome_plural ); ?>
					</th>
					<th width="4%">
						<?php echo JText::_( 'Publicado' ); ?>
					</th>
					<th width="4%" style="text-align: left">
						<?php echo JHTML::_('grid.sort',  'Aprovado', 'p.aprovado', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , $this->nome_plural ); ?>
					</th>
					<th width="2%">
						<?php echo JText::_( 'ID' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
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
					$link			= JRoute::_( 'index.php?option=com_p22evento&controller='. $this->nome .'&idevento='. intval( $this->idevento ) .'&task=edit&cid[]='. $row->id );
					$link2			= JRoute::_( 'index.php?option=com_p22evento&controller=palestrante&idevento='. intval( $this->idevento ) .'&task=edit&cid[]='. $row->id_palestrante );
					$checked		= JHTML::_('grid.id',   $i, $row->id );
					$published		= JHTML::_('grid.published', $row, $i );
					$datetime		= explode( ' ' , $row->registrado_em );
					$registrado_em	= $this->lib->dateFormat( $row->registrado_em , 'datetime' );
					$image			= ( $row->aprovado ) ? 'tick.png' : 'publish_x.png';
					$aprovado		= JHTML::_('image.administrator',  $image , 'images/', NULL, NULL, 1 );
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
							<?php echo $checked; ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>"><?php echo $row->nome; ?></a>
						</td>
						<td>
							<a href="<?php echo $link2; ?>">
								<?php echo $row->palestrante; ?>
							</a>
						</td>
						<td>
							<div style="float:left">
								<div style="width: 30px; height: 20px; background-color: <?php echo $row->cor; ?>">&nbsp;</div>
							</div>
							<div style="float:left; margin-left: 4px; margin-top: 4px">
								<?php echo $row->trilha; ?>
							</div>
							<div style="clear: both"></div>
						</td>
						<td align="center">
							<?php echo $registrado_em; ?>
						</td>
						<td align="center">
							<?php echo $nivel; ?>
						</td>
						<td align="center">
							<?php echo $published; ?>
						</td>
						<td align="center">
							<?php echo $aprovado; ?>
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

	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="task" value="<?php echo $this->nome_plural; ?>" />
	<input type="hidden" name="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="<?php echo $this->nome; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists[ 'order' ]; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists[ 'order_Dir' ]; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>