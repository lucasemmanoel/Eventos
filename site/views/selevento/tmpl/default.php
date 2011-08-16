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
	.icon-48-p22Eventos{ background-image: url('/./administrator/components/com_p22evento/images/eventos.png') }
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
			<form action="index.php" method="post" name="adminForm">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td valign="top">
							<table class="adminlist">
								<tbody>
									<tr>
										<td>
											<div id="cpanel">
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Voltar" src="/./administrator/components/com_p22evento/images/back.png"><span>Voltar</span>
														</a>
													</div>
												</div>
												<?php if ( $this->icon->inscricao ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=subscribe&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Inscrição" src="/./administrator/components/com_p22evento/images/inscritos.png"><span>Inscrição</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<?php if ( $this->icon->colaborador ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=colaborador&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Colaboradores" src="/./administrator/components/com_p22evento/images/inscritos.png"><span>Colaboradores</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<?php if ( $this->icon->programacao ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=gradeprogramacao&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Programação" src="/./administrator/components/com_p22evento/images/grade_palestras.png"><span>Programação</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<?php if ( $this->icon->avaliador ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=avaliacao&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Avaliação" src="/./administrator/components/com_p22evento/images/avaliacao.png"><span>Avaliação</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<?php if ( $this->icon->palestras ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=palestra&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Submeter Trabalho" src="/./administrator/components/com_p22evento/images/palestras.png"><span>Submissão de<br /> Trabalho</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<?php if ( $this->icon->palestrantes ): ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=palestrantes&idevento='.$this->idevento.'&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Palestrantes" src="/./administrator/components/com_p22evento/images/palestrantes.png"><span>Palestrantes</span>
														</a>
													</div>
												</div>
												<?php endif; ?>
												<div style="float: left;">
													<div class="icon">
														<a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=certvalidate&Itemid='. JRequest::getInt('Itemid') ); ?>">
															<img alt="Validação de Certificados" src="/./administrator/components/com_p22evento/images/certificados.png"><span>Validação de <br />Certificados</span>
														</a>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td valign="top" width="220px" style="padding: 0 0 0 5px">
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