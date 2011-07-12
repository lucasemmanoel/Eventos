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
	.icon-48-p22Grade{ background-image: url('components/com_p22evento/images/grade_palestras.png') }
	table.adminlist tbody tr.row0:hover td,
	table.adminlist tbody tr.row1:hover td  { background-color: transparent !important; }
	table.adminlist tbody tr.show td.show-top { border-top: 2px solid #CCC; }
</style>
<form action="index.php?option=com_p22evento&task=grades&idevento=<?php echo $this->idevento; ?>" method="post" name="adminForm">
	<table width="100%">
		<tr>
			<td width="30%">
				<h1>Grade de Palestras - <small><?php echo ( $this->filter_tipo == 'P' ) ? 'Palestras' : 'Mini-cursos'; ?></small></h1>
			</td>
			<td width="70%" align="right">
				<strong>Exibir Grade de:</strong>
				<select name="filter_tipo" id="filter_tipo" onchange="this.form.submit();">
					<option <?php echo ( $this->filter_tipo == 'P' ) ? 'selected' : ''; ?> value="P">Palestras</option>
					<option <?php echo ( $this->filter_tipo == 'M' ) ? 'selected' : ''; ?> value="M">Mini-cursos</option>
				</select>
			</td>
		</tr>
</table>
	<div style="border:1px solid #CCC;background-color: #DDD;padding:10px;">
		<div style="float:left;margin-top:5px">
			<strong>Mostrar Descrições de Paletras na Grade Programação?</strong>
		</div>
		<div style="float:left;margin-left: 10px">
			<input type="radio" onchange="javascript:registraInfo('description',0)" name="showdescription" id="showDescriptionN" value="0" <?php echo ( !$this->show_descriptions ) ? 'checked' : ''; ?> />
			<label for="showDescriptionN">Não</label>
		</div>
		<div style="float:left;margin-left: 10px">
			<input type="radio" onchange="javascript:registraInfo('description',1)" name="showdescription" id="showDescriptionS" value="1" <?php echo ( $this->show_descriptions ) ? 'checked' : ''; ?> />
			<label for="showDescriptionS">Sim</label>
		</div>
		<div style="float:left;margin-left: 10px;margin-top:3px;visibility:hidden" id="loader_description">
			<img src="components/com_p22evento/images/loader_gray.gif" alt="" />
		</div>
		<div style="float:left;margin-top:5px;margin-left: 10px">
			<strong>Palestra ministrada mais de uma vez no mesmo dia?</strong>
		</div>
		<div style="float:left;margin-left: 10px">
			<input type="radio" onchange="javascript:registraInfo('palestras',0)" name="mesma_palestra" id="mesma_palestraN" value="0" <?php echo ( !$this->mesma_palestra ) ? 'checked' : ''; ?> />
			<label for="mesma_palestraN">Não</label>
		</div>
		<div style="float:left;margin-left: 10px">
			<input type="radio" onchange="javascript:registraInfo('palestras',1)" name="mesma_palestra" id="mesma_palestraS" value="1" <?php echo ( $this->mesma_palestra ) ? 'checked' : ''; ?> />
			<label for="mesma_palestraS">Sim</label>
		</div>
		<div style="float:left;margin-left: 10px;margin-top:3px;visibility:hidden" id="loader_palestras">
			<img src="components/com_p22evento/images/loader_gray.gif" alt="" />
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="border:1px solid #CCC;padding:4px;margin-top:5px">
		<table class="adminlist">
			<thead>
				<tr style="background-color: #f2f2f2;">
					<th width="2%">
						&nbsp;
					</th>
					<th width="8%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JHTML::_('grid.sort',  'Sala', 's.nome', @$this->lists[ 'order_Dir' ], @$this->lists[ 'order' ] , 'grades' ); ?>
					</th>
					<th width="6%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Hora'); ?>
					</th>
					<th width="24%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Palestra'); ?>
					</th>
					<th width="23%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Palestrante'); ?>
					</th>
					<th width="12%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Trilha'); ?>
					</th>
					<th width="8%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Nível'); ?>
					</th>
					<th width="5%" nowrap="nowrap" class="title" style="text-align:left">
						<?php echo JText::_('Publicado'); ?>
					</th>
					<th width="2%">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $this->num_reg; ?>);" />
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if( count( $this->registros ) )
			{
				foreach( $this->registros['reg'] AS $dia => $registros )
				{
					?>
					<tr>
						<td colspan="9" style="padding:10px;font-size:12px;font-weight:bold;background-color:#eaeaea">
							<?php echo implode( '/' , array_reverse( explode( '-' , $dia ) ) ); ?>
						</td>
					</tr>
					<?php
					$k			= 0;
					$j			= $this->limitstart;
					$chkSala	= array();
					$chkTime	= array();
					$loopTime	= array();
					for ( $i = 0, $n=count( $registros ); $i < $n; $i++)
					{
						$sala		= &$registros[$i];
						$show		= ( !in_array( $sala->id , $chkSala ) ) ? true : false;
						$numHoras	= count( $this->registros['data'][ $sala->id ] );

						if( !$loopTime[ $sala->id ] ) $loopTime[ $sala->id ] = 0;
						?>
						<tr class="<?php echo "row$k"; ?> <?php echo ( $show ) ? 'show' : ''; ?>">
							<?php if( $show ) : ?>
							<td align="center" rowspan="<?php echo $numHoras; ?>" class="show-top">
								<?php echo $j + 1; ?>
							</td>
							<td rowspan="<?php echo $numHoras; ?>" class="show-top">
								<strong><?php echo $sala->nome; ?></strong>
							</td>
							<?php endif; ?>
							<?php
							if ( $loopTime[ $sala->id ] == 0 || $chkTime[ $sala->id ] == $loopTime[ $sala->id ] )
							{
								$row		= &$this->registros['data'][ $sala->id ][ $loopTime[ $sala->id ] ];
								$checked	= JHTML::_('grid.checkedout',   $row, $i );
								$published	= JHTML::_('grid.published', $row , $i );
								$link		= JRoute::_( 'index.php?option=com_p22evento&controller=grade&idevento='. intval( $this->idevento ) .'&task=edit&cid[]='. $row->id );
								?>
								<td class="show-top">
									<?php if ( $row->hora ) : ?>
									<span style="font-weight:bold"><?php echo date( 'H:i' , $row->hora ); ?> hs</span>
									<?php endif; ?>
								</td>
								<td class="show-top">
									<a href="<?php echo $link; ?>">
										<?php echo $row->palestra; ?>
									</a>
								</td>
								<td class="show-top">
									<?php echo $row->palestrante; ?>
								</td>
								<td class="show-top">
									<?php echo $row->trilha; ?>
								</td>
								<td class="show-top">
									<?php
									switch( $row->nivel )
									{
										case 0:
										default:
											echo 'Básico';
											break;
										case 1:
											echo 'Intermediário';
											break;
										case 2:
											echo 'Avançado';
											break;
									}
									?>
								</td>
								<td align="center" class="show-top">
									<?php echo $published; ?>
								</td>
								<td align="center" class="show-top">
									<?php echo $checked; ?>
								</td>

							<?php
								$loopTime[ $sala->id ]++;
								$chkTime[ $sala->id ] = $loopTime[ $sala->id ];
							}
							?>
						</tr>
						<?php
						$chkSala[] = $sala->id;

						if( $show ) $j++;

						$k = 1 - $k;
					}
				}
			}
			?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="controller" value="grade" />
	<input type="hidden" name="task" value="grades" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists[ 'order' ]; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists[ 'order_Dir' ]; ?>" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>