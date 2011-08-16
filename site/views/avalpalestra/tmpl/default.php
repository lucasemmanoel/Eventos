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
.icon-48-p22Avaliacao{ background-image: url('/./administrator/components/com_p22evento/images/avaliacao.png') }
</style>
<script language="javascript" type="text/javascript">
	<!--
	Window.onDomReady(function() {
		document.formvalidator.setHandler('radio_q', function (par) {
			var nl, i;
			if(par.parentNode == null){
			   return true;
			}else{
				var options = par.parentNode.parentNode.parentNode.getElementsByTagName('input');

				var returnV = false;
				for (i=0, nl = options; i<nl.length; i++)
				{
					if (nl[i].checked)
						returnV = true;
				}

				return returnV;
			}
		});

		<?php if ( JRequest::getCmd('close') ) : ?>
		window.parent.document.getElementById('sbox-window').close();
		<?php endif; ?>

		<?php if ( $this->registro->id ) : ?>
			window.setTimeout( 'updateData()' , 800 );
		<?php endif; ?>
	});

	function updateData()
	{
		var html = '<span style="color:gray"><?php echo $this->registro->nome; ?> <span style="font-size:10px">[ avaliado ]</span></span>';
		window.parent.document.getElementById( 'link_avalpalestra_<?php echo $this->registro->id_palestra; ?>' ).innerHTML = html;
	}
	// -->
</script>
<div style="width:82%">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div class="header icon-48-p22Avaliacao">
				Avaliação de Palestra
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
			<div class="header" style="padding-left:0px;padding-bottom:2px;line-height: 0;margin-top:20px;margin-bottom:20px">
				Palestra: <span style="color:#333;font-size: 14px"><?php echo $this->registro->nome; ?></span>
			</div>
			<?php if ( $this->registro->resumo ) : ?>
			<div style="padding:10px;font-size: 12px;margin-left:5px;border:1px solid #DDD;margin-bottom:10px;background-color:#F7F7F7">
				<?php echo nl2br( $this->registro->resumo ); ?>
			</div>
			<?php endif; ?>

			<?php if ( $this->registro->avaliar ) { ?>
			<form action="<?php echo JRoute::_('index2.php?option=com_p22evento&task=avalpalestra&idevento='. intval( $this->idevento ) .'&palestraid='. JRequest::getInt('palestraid') .'&Itemid=' . JRequest::getInt('Itemid') ); ?>" method="post" id="josForm" name="josForm" class="form-validate">
				<div>
					<fieldset class="adminform">
						<table class="admintable" border="0" width="100%">
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Confiança:
											<span style="font-weight: normal!important">
												Sua Auto-avaliação com relação ao tema da proposta.
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$array = array(
										'G' => 'Não sou expert, mas sinto-me confortável com o assunto.',
										'C' => 'Não conheço bem o assunto. Sou generalista.',
										'E' => 'Sou expert, conheço bastante o assunto.'
									);
									$a = 0;
									foreach( $array AS $value => $text )
									{
									?>
									<div style="margin-left:10px;padding:5px">
										<div style="float:left">
											<input type="radio" class="validate-radio_q" name="confianca" id="confianca<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ( $this->registro->confianca == $value ) ? 'checked="checked"' : ''; ?> />
										</div>
										<div style="float:left;margin-top:3px;margin-left:5px">
											<label for="confianca<?php echo $value; ?>"><strong><?php echo $value; ?></strong> - <?php echo $text; ?></label>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php $a++; } ?>
								</td>
							</tr>
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Relevância:
											<span style="font-weight: normal!important">
												Relevância da proposta para o evento, a partir do resumo fornecido pelo autor.
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$array = array(
										1 => 'Nenhuma',
										2 => 'Pouca',
										3 => 'Alguma',
										4 => 'Extrema'
									);

									foreach( $array AS $value => $text )
									{
									?>
									<div style="margin-left:10px;padding:5px">
										<div style="float:left">
											<input type="radio" class="validate-radio_q" name="relevancia" id="relevancia<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ( $this->registro->relevancia == $value ) ? 'checked="checked"' : ''; ?> />
										</div>
										<div style="float:left;margin-top:3px;margin-left:5px">
											<label for="relevancia<?php echo $value; ?>"><strong><?php echo $value; ?></strong> - <?php echo $text; ?></label>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Qualidade técnica:
											<span style="font-weight: normal!important">
												Qualidade técnica da proposta, a partir do resumo fornecido pelo autor.
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$array = array(
										1 => 'Nenhuma',
										2 => 'Pouca',
										3 => 'Alguma',
										4 => 'Extrema'
									);

									foreach( $array AS $value => $text )
									{
									?>
									<div style="margin-left:10px;padding:5px">
										<div style="float:left">
											<input type="radio" class="validate-radio_q" name="qualidade_tecnica" id="qualidade_tecnica<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ( $this->registro->qualidade_tecnica == $value ) ? 'checked="checked"' : ''; ?> />
										</div>
										<div style="float:left;margin-top:3px;margin-left:5px">
											<label for="qualidade_tecnica<?php echo $value; ?>"><strong><?php echo $value; ?></strong> - <?php echo $text; ?></label>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Experiência:
											<span style="font-weight: normal!important">
												Experiência do autor no tema da proposta, a partir do mini-currículum fornecido pelo autor e outros meios (buscas na web, outros eventos, atuação da comunidade etc).
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$array = array(
										1 => 'Nenhuma',
										2 => 'Pouca',
										3 => 'Alguma',
										4 => 'Extrema'
									);

									foreach( $array AS $value => $text )
									{
									?>
									<div style="margin-left:10px;padding:5px">
										<div style="float:left">
											<input type="radio" class="validate-radio_q" name="experiencia" id="experiencia<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ( $this->registro->experiencia == $value ) ? 'checked="checked"' : ''; ?> />
										</div>
										<div style="float:left;margin-top:3px;margin-left:5px">
											<label for="experiencia<?php echo $value; ?>"><strong><?php echo $value; ?></strong> - <?php echo $text; ?></label>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Recomendação:
											<span style="font-weight: normal!important">
												Sua Sugestão como avaliador com relação à proposta.
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$array = array(
										'R' => 'Rejeição forte - Tenho argumentos fortes para rejeitar o trabalho.',
										'r' => 'Rejeição fraca - Não tenho argumentos fortes para rejeitar o trabalho; tenho mais argumentos para rejeitar o trabalho do que para aceitar.',
										'a' => 'Aceitação fraca - Não tenho argumentos fortes para aceitar o trabalho; tenho mais argumentos para aceitar o trabalho do que para rejeitar.',
										'A' => 'Aceitação forte - Tenho argumentos fortes para aceitar o trabalho.'
									);

									foreach( $array AS $value => $text )
									{
									?>
									<div style="margin-left:10px;padding:5px">
										<div style="float:left;margin-top:2px">
											<input type="radio" class="validate-radio_q" name="recomendacao" id="recomendacao<?php echo $value; ?>" value="<?php echo $value; ?>" <?php echo ( $this->registro->recomendacao == $value ) ? 'checked="checked"' : ''; ?> />
										</div>
										<div style="float:left;margin-top:3px;margin-left:5px;width:95%">
											<label for="recomendacao<?php echo $value; ?>"><strong><?php echo $value; ?></strong> - <?php echo $text; ?></label>
										</div>
										<div style="clear:both"></div>
									</div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td class="key" style="text-align: left!important">
									<label id="confiancamsg">
										<div style="padding:10px">
											Comentário:
											<span style="font-weight: normal!important">
												sua observação vai ajudar a avaliar a aprovação desta palestra.
												Qual o seu comentário sobre suas respostas?
											</span>
										</div>
									</label>
								</td>
							</tr>
							<tr>
								<td align="center">
									<textarea name="comentario" rows="10" cols="100"><?php echo $this->registro->comentario; ?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center !important;padding:10px !important" class="key">
									<button class="button" type="button" onclick="window.parent.document.getElementById('sbox-window').close();">Fechar</button>
									<button class="button validate" type="submit">Enviar</button>
								</td>
							</tr>
						</table>

					</fieldset>
				</div>
				<div class="clr"></div>
				<input type="hidden" name="option" value="com_p22evento" />
				<input type="hidden" name="id" id="id" value="<?php echo $this->registro->id; ?>" />
				<input type="hidden" name="id_palestra" id="id_palestra" value="<?php echo $this->registro->id_palestra; ?>" />
				<input type="hidden" name="id_avaliador" id="id_avaliador" value="<?php echo $this->registro->id_participante; ?>" />
				<input type="hidden" name="id_evento" id="id_evento" value="<?php echo JRequest::getInt('idevento'); ?>" />
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
				<input type="hidden" name="resumo" id="resumo" value="<?php echo $this->registro->seResumo; ?>" />
				<input type="hidden" name="id_user" id="id_user" value="<?php echo $this->registro->id_user; ?>" />
				<input type="hidden" name="task" value="save" />
				<input type="hidden" name="controller" value="avalpalestra" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
			<?php } else { ?>
			<div class="col100">
				<fieldset class="adminform">
					<strong>Palestra já foi avaliada.</strong>
				</fieldset>
			</div>
			<?php } ?>
			<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
	</div>
</div>