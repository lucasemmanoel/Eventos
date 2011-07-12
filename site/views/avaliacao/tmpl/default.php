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
	.icon-48-p22Avaliacao{ background-image: url('administrator/components/com_p22evento/images/avaliacao.png') }
	.avalar$this->avaliacao_right {
		background-color: #EAEAEA;
		border-top:1px solid #DDD;
		border-right:1px solid #DDD;
		border-bottom:1px solid #DDD;
		cursor: pointer;
	}
	.avalar$this->avaliacao_right:hover { background-color: #DDD; }
	.trilha_item {
		padding:10px;
		background-color: #EAEAEA;
		border:1px solid #DDD;
		margin-bottom:3px;
		width: 175px;
	}
	.trilha_item:hover { background-color: #DDD; }
	.trilha_active {
/*		width: 194px;*/
		background-color: #DDD;
	}
	.trilha_item:hover{ cursor: pointer }
	.trilha_item:hover a ,
	.trilha_item a:hover { font-weight: bold; }
	.trilha_active a { font-weight: bold; }
	.trilha_item a:hover,
	.trilha_active a:hover,
	.trilha_item a:active,
	.trilha_active a:active,
	.trilha_item a:focus,
	.trilha_active a:focus { text-decoration: none !important; }
</style>
<script type="text/javascript">
function mostraCampo( tp , linkField , textLink , textLinkHidden )
{
	var campo = document.getElementById( tp );
	var link = document.getElementById( linkField );

	if( campo.style.display == 'none' )
	{
		campo.style.display = '';
		link.innerHTML = textLinkHidden;
	}
	else
	{
		campo.style.display = 'none';
		link.innerHTML = textLink;
	}
}
</script>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<?php if ( !JRequest::getCmd('direct') ) : ?>
			<div id="toolbar" class="toolbar">
				<table class="toolbar">
					<tbody>
						<tr>
							<td id="toolbar-new" class="button">
								<a class="toolbar" onclick="javascript:window.location.href='<?php echo JRoute::_( 'index2.php?option=com_p22evento&task=mykeynotes&idevento='. intval( $this->idevento ) .'&Itemid=' . JRequest::getInt('Itemid') ); ?>'" href="#">
									<span title="Delete" class="icon-32-back"></span>
									Voltar
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
			<div class="header icon-48-p22Avaliacao">
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
				<div class="header" style="padding-left:0px">
					Avaliação de <?php echo ( $this->avaliacao->tipo )  ? 'Mini-curso' : 'Palestra'; ?>
				</div>
				<div style="border:1px solid #eaeaea">
					<div style="background:#ddd;border:1px solid #ccc;padding:5px;font-weight:bold">
						<div style="float:left">LEGENDA</div>
						<div style="float:right;font-weight:normal">
							<a id="expandir_legenda" href="javascript:void(0)" onclick="javascript:mostraCampo( 'campo_legenda' , 'expandir_legenda' , 'Mostrar' , 'Esconder' )">
								Mostrar
							</a>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="border:1px solid #ccc;padding:3px;display:none" id="campo_legenda">
						<div style="padding: 5px">
							<div style="float:left;margin-bottom:10px;clear:right">
								<table class="adminlist" width="100%">
									<thead>
										<tr>
											<th width="33%">C</th>
											<th width="33%">G</th>
											<th width="33%">E</th>
										</tr>
									</thead>
									<tbody>
										<tr class="$this->avaliacao0">
											<td align="center">Não conheço bem o assunto. Sou generalista.</td>
											<td align="center">Não sou expert, mas sinto-me confortável com o assunto.</td>
											<td align="center">Sou expert, conheço bastante o assunto.</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div style="float:left;margin-bottom:10px;clear:right">
								<table class="adminlist" width="100%">
									<thead>
										<tr>
											<th width="25%">R</th>
											<th width="25%">r</th>
											<th width="25%">a</th>
											<th width="25%">A</th>
										</tr>
									</thead>
									<tbody>
										<tr class="$this->avaliacao0">
											<td align="center">Rejeição forte - Tenho argumentos fortes para rejeitar o trabalho.</td>
											<td align="center">Rejeição fraca - Não tenho argumentos fortes para rejeitar o trabalho; tenho mais argumentos para rejeitar o trabalho do que para aceitar.</td>
											<td align="center">Aceitação fraca - Não tenho argumentos fortes para aceitar o trabalho; tenho mais argumentos para aceitar o trabalho do que para rejeitar.</td>
											<td align="center">Aceitação forte - Tenho argumentos fortes para aceitar o trabalho.</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div style="float:left;clear:left">
								<table class="adminlist" width="100%">
									<thead>
										<tr>
											<th width="20%">Nenhuma</th>
											<th width="20%">Pouca</th>
											<th width="20%">Alguma</th>
											<th width="20%">Muita</th>
											<th width="20%">Extrema</th>
										</tr>
									</thead>
									<tbody>
										<tr class="$this->avaliacao0">
											<td align="center">1</td>
											<td align="center">2</td>
											<td align="center">3</td>
											<td align="center">4</td>
											<td align="center">5</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div style="float:left;margin-left:4px">
								<table class="adminlist" width="100%">
									<thead>
										<tr>
											<th width="20%">Nenhuma</th>
											<th width="20%">Pouca</th>
											<th width="20%">Alguma</th>
											<th width="20%">Muita</th>
											<th width="20%">Extrema</th>
										</tr>
									</thead>
									<tbody>
										<tr class="$this->avaliacao0">
											<td align="center">1</td>
											<td align="center">2</td>
											<td align="center">3</td>
											<td align="center">4</td>
											<td align="center">5</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div style="clear:both"></div>
						</div>
					</div>
				</div>
				<div style="border:1px solid #eaeaea;margin-top:3px">
					<div style="padding:3px" id="campo_avaliacoes">
						<div style="float:left;width:100%" id="div_palestras">
							<div id="div_palestra_<?php echo $this->avaliacao->id_palestra; ?>">
								<table width="100%" style="margin-top:5px">
									<tr>
										<td width="30%" style="border:1px solid #DDD" valign="top">
											<div>
												<div style="background-color:#DDD;padding:7px">
													<span style="font-weight: bold;color:#333"><?php echo ( $this->avaliacao->tipo )  ? 'MINI-CURSO' : 'PALESTRA'; ?></span>
												</div>
												<div style="padding:7px">
													<div style="margin-bottom:20px;border-bottom:1px solid #DDD">
														<table width="100%">
															<tr>
																<td width="10%"><strong>Trilha:</strong> </td>
																<td>
																	<?php echo $this->avaliacao->trilha; ?>
																</td>
															</tr>
															<tr>
																<td><strong>Nível:</strong></td>
																<td>
																	<?php
																	switch( $this->avaliacao->nivel )
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
															</tr>
														</table>
													</div>
													<div style="margin-top:20px;margin-bottom:20px;font-weight: bold;padding-top:10px;padding-bottom:10px">
														<?php echo $this->avaliacao->palestra; ?>
													</div>
													<div style="margin-top:10px;text-align: right;font-size: 10px;border-top:1px solid #DDD;padding-top:5px">
														<?php echo $this->avaliacao->palestrante; ?>
													</div>
												</div>
												<div style="background-color:#DDD;padding:7px;margin-top:2px;font-size:10px">
													<span style="color:#333">Avaliação em </span>
													<?php
													$last_reg	= explode( ' ' ,  $this->lib->now( true , $this->avaliacao->registrado_em ) );
													$last_reg	= implode( '/' , array_reverse( explode( '-' , $last_reg[0] ) ) ) . ' às ' . $last_reg[1];
													echo $last_reg;
													?>
												</div>
											</div>
										</td>
										<td style="border:1px solid #DDD" valign="top">
											<div>
												<div style="background-color:#DDD;padding:7px">
													<span style="font-weight: bold;color:#333">RESULTADO DA AVALIAÇÃO</span>
												</div>
												<div style="padding:7px">
													<div style="margin-bottom:20px;border-bottom:1px solid #DDD">
														<table width="100%">
															<tr>
																<td width="10%"><strong>Condição:</strong></td>
																<td style="color: #008000">
																	<strong><?php echo $this->palestraStatus[ $this->avaliacao->id_palestra ]; ?></strong>
																</td>
															</tr>
														</table>
													</div>
													<div style="margin-top:10px;margin-bottom:20px">
														<div style="font-size: 14px;margin-bottom:5px;float:left"><strong>Pontuação: <span style="color: <?php echo $this->graphColor[ $this->avaliacao->id_palestra ]; ?>"><?php echo $this->pontuacao[ $this->avaliacao->id_palestra ]; ?>%</span></strong></div>
														<div style="float:left;clear:left;width:<?php echo $this->graphWidth[ $this->avaliacao->id_palestra ]; ?>px;background-color: <?php echo $this->graphColor[ $this->avaliacao->id_palestra ]; ?>;padding:20px">&nbsp;</div>
														<div style="clear:both"></div>
													</div>
												</div>
											</div>
										</td>
										<td width="3%" style="border:1px solid #DDD" valign="top">
											<div style="background-color:#DDD;padding:7px">
												<span style="font-weight: bold;color:#333">APROVADO</span>
											</div>
											<div style="padding:7px;text-align: center;margin-top:70px">
												<?php
												$aprovado = ( $this->avaliacao->published ) ? 'tick.png' : 'publish_x.png';
												?>
												<img src="./administrator/images/<?php echo $aprovado; ?>" alt="" />
											</div>
										</td>
									</tr>
									<tr id="campo_detalhe_<?php echo $this->avaliacao->id_palestra; ?>">
										<td colspan="3" style="border:1px solid #DDD" valign="top">
											<div style="padding:7px">
												<div>
													<table class="adminlist" width="100%">
														<thead>
															<tr>
																<th width="3%">#</th>
																<th style="text-align: left">Avaliador</th>
																<th width="10%">Confiança</th>
																<th width="10%">Relevância</th>
																<th width="10%">Qualidade Técnica</th>
																<th width="10%">Experiência</th>
																<th width="10%">Recomendação</th>
																<th width="10%">Resumo</th>
																<th width="12%">Avaliado em</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$k = 0;
															for( $c = 0 ; $c < count( $this->avaliacao->details ); $c++ )
															{
																$detail = &$this->avaliacao->details[ $c ];
																$last_reg2	= explode( ' ' , $this->lib->now( true , $detail->registrado_em ) );
																$last_reg2	= implode( '/' , array_reverse( explode( '-' , $last_reg2[0] ) ) ) . ' às ' . $last_reg2[1];
															?>
															<tr class="<?php echo "row$k"; ?>">
																<td align="center"><?php echo $k + 1; ?></td>
																<td><?php echo $detail->avaliador; ?></td>
																<td align="center"><?php echo $detail->confianca; ?></td>
																<td align="center"><?php echo $detail->relevancia; ?></td>
																<td align="center"><?php echo $detail->qualidade_tecnica; ?></td>
																<td align="center"><?php echo $detail->experiencia; ?></td>
																<td align="center"><?php echo $detail->recomendacao; ?></td>
																<td align="center">Sim</td>
																<td align="center"><?php echo $last_reg2; ?></td>
															</tr>
															<?php
																$k = 1 - $k;
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
											<div style="padding:7px;margin-top:8px">
												<div style="font-size: 14px">
													<strong>Comentários de Avaliadores:</strong>
												</div>
												<?php
												for( $c = 0 ; $c < count( $this->avaliacao->details ); $c++ )
												{
													$detail = &$this->avaliacao->details[ $c ];
													if ( !$detail->comentario ) continue;
												?>
												<div style="margin-top:5px;border:1px solid #DDD">
													<div>
														<div style="background-color:#DDD;padding:5px">Comentário de <strong><?php echo $detail->avaliador; ?></strong> :</div>
													</div>
													<div style="padding: 10px">
														<?php echo nl2br( $detail->comentario ); ?>
													</div>
												</div>
												<?php } ?>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>

				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="controller" value="avaliacao" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="task" value="avaliacao" />
				<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
				<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
				<input type="hidden" name="filter_trilha" id="filter_trilha" value="" />
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