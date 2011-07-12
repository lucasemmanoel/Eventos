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
<table class="adminlist">
	<?php
	if (  count( $this->registros ) )
	{
	?>
		<thead>
			<tr style="background-color: #f2f2f2;">
				<th width="2%">
					&nbsp;
				</th>
				<th nowrap="nowrap" class="title" style="text-align:left">
					<?php echo JText::_( 'Nome' ); ?>
				</th>
				<th width="8%" align="center">
					<?php echo JText::_( 'CPF' ); ?>
				</th>
				<th width="15%" align="center">
					<?php echo JText::_( 'Cidade-UF' ); ?>
				</th>
				<th width="20%" align="center">
					<?php echo JText::_( 'Profissão' ); ?>
				</th>
				<th width="13%" align="center">
					<?php echo JText::_( 'Registrado em' ); ?>
				</th>
				<th width="4%" align="center">
					<?php echo JText::_( 'Confirmado' ); ?>
				</th>
				<th width="4%" align="center">
					<?php echo JText::_( 'ID' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;

			for ($i = 0, $n=count( $this->registros ); $i < $n; $i++)
			{
				$row					= &$this->registros[$i];
				$datetime				= explode( ' ' , $row->registrado_em );
				$registrado_em			= $this->lib->dateFormat( $row->registrado_em , 'datetime' );
				$display_publish_user	= ( !$row->published ) ? 'none' : '';
				$display_unpublish_user	= ( $row->published ) ? 'none' : '';
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $k + 1; ?>
					</td>
					<td>
						<?php if ( $row->tp_reg ) : ?>
							<?php echo ( $row->name ) ? $row->name : '<em>Usuário Inexistente</em>'; ?> [ Colaborador ]
						<?php else: ?>
							<?php echo ( $row->name ) ? $row->name : '<em>Usuário Inexistente</em>'; ?>
						<?php endif; ?>
					</td>
					<td align="center">
						<?php echo $this->lib->setMask( $row->cpf , '999.999.999-99' ); ?>
					</td>
					<td>
						<?php echo $row->cidade; ?>-<?php echo $row->uf; ?>
					</td>
					<td>
						<?php echo $row->profissao; ?>
					</td>
					<td align="center">
						<?php echo $registrado_em; ?>
					</td>
					<td align="center">
						<div id="user_confirmed_<?php echo $row->id; ?>">
							<a href="javascript:confirmUser( '<?php echo $row->id; ?>' , 0 )" style="display:<?php echo $display_publish_user; ?>" id="user_publish_<?php echo $row->id; ?>">
								<img src="images/tick.png" alt="" title="Confirmar presença" />
							</a>
							<a href="javascript:confirmUser( '<?php echo $row->id; ?>' , 1 )" style="display:<?php echo $display_unpublish_user; ?>" id="user_unpublish_<?php echo $row->id; ?>">
								<img src="images/publish_x.png" alt="" title="Desconfirmar presença" />
							</a>
						</div>
						<div id="loader_confirmed_<?php echo $row->id; ?>" style="display:none">
							<img src="components/com_p22evento/images/loader.gif" alt="" />
						</div>
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
		<?php
		}
		else
		{
		?>
			<tr>
				<td>
					<div style="padding:10px;color:#CCCCCC"><em>Pesquisa de Inscritos</em></div>
				</td>
			</tr>
		<?php
		}
	?>
</table>