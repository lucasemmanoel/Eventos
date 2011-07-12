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

$buttons		= array( 'inscricoes','colaboradores','palestras','minicursos','programacao','palestrantes','certificados' );
$tit_buttons	= array( 'Inscrições','Colaboradores','Palestras','Mini-cursos','Programação','Palestrantes','Certificados' );
$tip_buttons	= array(
		'Pubicar inscrições públicas de participantes.',
		'Pubicar inscrições públicas de colaboradores.',
		'Pubicar inscrições públicas de palestras.',
		'Pubicar inscrições públicas de mini-cursos.',
		'Pubicar visualização pública da programação.',
		'Pubicar visualização pública de palestrantes.',
		'Liberação de Certificados para Download.'
);
?>
<style type="text/css">
	.icon-48-p22Eventos{ background-image: url('components/com_p22evento/images/eventos.png') }
	div.publish_button {
		padding:5px;
		border-right:1px solid #CCC;
		border-bottom:1px solid #CCC;
	}
	div.publish_button:hover {
		border-right:1px solid #333;
		border-bottom:1px solid #333;
		cursor: pointer;
	}
</style>
<form action="index.php" method="post" name="adminForm">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td colspan="2">
				<div style="border:1px solid #CCC;background-color: #DDD;padding:10px;">
					<div style="margin-top:5px">
						<strong>PUBLICAR:</strong>
					</div>
					<?php for( $a = 0 ; $a < count( $buttons ) ; $a++ ) : ?>
					<div style="float:left;margin-right:10px" class="publish_button hasTip" title="<?php echo $tit_buttons[ $a ]; ?>::<?php echo $tip_buttons[ $a ]; ?>">
						<div style="float:left;margin-top:5px">
							<label for="mark_<?php echo $buttons[ $a ]; ?>"><strong><?php echo $tit_buttons[ $a ]; ?></strong></label>
						</div>
						<div style="float:left;margin-top:2px">
							<input type="checkbox" onchange="javascript:publisEventAction('<?php echo $this->idevento; ?>','<?php echo $buttons[ $a ]; ?>',this)" name="mark_incricoes" id="mark_<?php echo $buttons[ $a ]; ?>" value="1" <?php echo ( $this->registro->$buttons[ $a ] ) ? 'checked' : ''; ?> />
						</div>
						<div style="float:left;margin-top: 2px;margin-top:3px;visibility:hidden" id="loader_<?php echo $buttons[ $a ]; ?>">
							<img src="components/com_p22evento/images/loader_gray.gif" alt="" />
						</div>
					</div>			
					<?php endfor; ?>
					
					<div style="clear:both"></div>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="adminlist">
					<tbody>
						<tr>
							<td>
								<div id="cpanel">
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento">
												<img alt="Voltar" src="components/com_p22evento/images/back.png"><span>Voltar</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=certificados&idevento=<?php echo $this->idevento; ?>">
												<img alt="Certificados" src="components/com_p22evento/images/certificados.png"><span>Certificados</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=inscritos&idevento=<?php echo $this->idevento; ?>">
												<img alt="Inscritos" src="components/com_p22evento/images/inscritos.png"><span>Inscritos</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=grades&idevento=<?php echo $this->idevento; ?>">
												<img alt="Programação" src="components/com_p22evento/images/grade_palestras.png"><span>Programação</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=avaliacao&idevento=<?php echo $this->idevento; ?>">
												<img alt="Avaliação" src="components/com_p22evento/images/avaliacao.png"><span>Avaliação</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=minicursos&idevento=<?php echo $this->idevento; ?>">
												<img alt="Mini-cursos" src="components/com_p22evento/images/palestras.png"><span>Mini-cursos</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=palestras&idevento=<?php echo $this->idevento; ?>">
												<img alt="Palestras" src="components/com_p22evento/images/palestras.png"><span>Palestras</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=palestrantes&idevento=<?php echo $this->idevento; ?>">
												<img alt="Palestrantes" src="components/com_p22evento/images/palestrantes.png"><span>Palestrantes</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=salas&idevento=<?php echo $this->idevento; ?>">
												<img alt="Salas" src="components/com_p22evento/images/salas.png"><span>Salas</span>
											</a>
										</div>
									</div>
									<div style="float: left;">
										<div class="icon">
											<a href="index.php?option=com_p22evento&task=colaboradores&idevento=<?php echo $this->idevento; ?>">
												<img alt="Colaboradores" src="components/com_p22evento/images/colaboradores.png"><span>Colaboradores</span>
											</a>
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td valign="top" width="320px" style="padding: 0 0 0 5px">
				<?php
				$title = JText::_( 'Estatísticas de Inscritos' );
				echo $this->pane->startPane( 'stat-pane' );
				echo $this->pane->startPanel( $title, 'evento' );

				?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_( 'Inscrições Efetuadas' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->venue[0]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Inscrições Confirmadas' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->venue[1]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Inscrições Não-confirmadas' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->venue[2]; ?></b>
						</td>
					</tr>
				</table>

				
				<?php

				$title = JText::_( 'Estatísticas de Eventos' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'inscritos' );
				
				?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_( 'Palestrantes Cadastrados' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->events[0]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Palestras Cadastradas' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->events[1]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Salas' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->events[2]; ?></b>
						</td>
					</tr>
				</table>
				<?php

				$title = JText::_( 'Estatísticas de Colaboradores' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, 'colaboradores' );

				?>
				<table class="adminlist">
					<tr>
						<td>
							<?php echo JText::_( 'Colaboradores Cadastrados' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->category[0]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Colaboradores Ativos' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->category[1]; ?></b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'Colaboradores Não-ativos' ).': '; ?>
						</td>
						<td>
							<b><?php echo $this->category[2]; ?></b>
						</td>
					</tr>
				</table>
				<?php
				echo $this->pane->endPanel();
				echo $this->pane->endPane();
				?>
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="evento" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists[ 'order' ]; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists[ 'order_Dir' ]; ?>" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
</form>