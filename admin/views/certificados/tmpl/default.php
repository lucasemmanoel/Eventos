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
	.icon-48-p22Certificados{ background-image: url('components/com_p22evento/images/certificados.png') }
</style>
<script type="text/javascript">
function obsHelp()
{
	var campo	= document.getElementById('campo_obs_help');
	var img		= document.getElementById('img_obs_help');
	var link	= document.getElementById('link_obs_help');

	if ( campo.style.display == 'none' )
	{
		img.setAttribute( 'src' , 'images/collapseall.png' );
		link.innerHTML = 'Esconder Orientações';
		campo.style.display = '';
	}
	else
	{
		img.setAttribute( 'src' , 'images/expandall.png' );
		link.innerHTML = 'Mostrar Orientações';
		campo.style.display = 'none';
	}
}
</script>
<div style="margin-left:10px;margin-bottom: 4px">
	<div style="float:left">
		<a href="javascript:obsHelp()" title="Mostrar/Esconder Orientações">
			<img src="images/expandall.png" id="img_obs_help" alt="" border="0" />
		</a>
	</div>
	<div style="float:left;margin-left: 5px; margin-top: 2px">
		<a href="javascript:obsHelp()" id="link_obs_help">
			Mostrar Orientações
		</a>
	</div>
	<div style="clear:both"></div>
</div>
<div id="campo_obs_help" style="display:none;background-color: #E5ED00;border: 2px solid #D1D800;float: left;width: 98%;margin-left: 10px; margin-bottom: 10px">
	<div style="background-color: #D1D800;padding:7px;font-weight: bold;color: #333;font-size: 14px">
		Orientações:
	</div>
	<div style="padding: 10px">
		<div>
			Para que o certificado seja emitido com sucesso para todos os participantes, é necessário informar:
		</div>
		<div>
			<ol>
				<li style="padding: 5px">O caminho da imagem do certificado: <em>A imagem terá de ser colocada no ambiente de mídia manualmente e o caminho deve ser informado abaixo. <strong>Quanto melhor a qualidade da imagem melhor.</strong></em></li>
				<li style="padding: 5px">
					Texto para ser exibido no certificado: <em>Inscritos, Colaboradores e Palestrantes;</em>
				</li>
			</ol>
		</div>
		<div>
			O certificado possui informações que são exibidas como padrão para qualquer certificado emitido. Estas informações devem ser marcadas no seu texto de forma que
			que o sistema possa substituir as marcações pelas informações necessárias. Estas marcações são:
		</div>
		<div>
			<ol>
				<li style="padding: 5px"><strong>[[NOME]]</strong>: <em>Campo que aparecerá o nome do participante no certificado, seja para inscrito, palestrante ou colaborador;</em></li>
				<li style="padding: 5px"><strong>[[TIPO]]</strong>: <em>Campo que aparecerá o tipo de participação: <strong>participante</strong>, <strong>colaborador</strong> ou <strong>palestrante</strong>. O nome será exibido com letras minúsculas;</em></li>
				<li style="padding: 5px"><strong>[[EVENTO]]</strong>: <em>Campo que aparecerá o nome do evento. O aparecerá assim: <strong><?php echo $this->eventName; ?></strong>;</em></li>
				<li style="padding: 5px">
					<strong>[[PERIODO_EVENTO]]</strong>:
					<em>Campo que aparecerá o período do nome do evento. O período referente a este evento será exibida da seguinte forma: <strong><?php echo $this->eventDetailString; ?></strong>;</em>
				</li>
				<li style="padding: 5px"><strong>[[PALESTRA]]</strong>: <em>Campo que aparecerá o nome da palestra em certificados de palestrantes. O aparecerá assim: <strong><?php echo $this->eventName; ?></strong>;</em></li>
			</ol>
		</div>
		<div>
			Caso você queira que alguma das marcações apareça com alguma variação de fonte, você poderá fazer assim:
		</div>
		<div>
			<ul>
				<li style="padding: 5px"><strong>Negrito</strong>: [B]<em>marcação</em>[/B]. Exemplo: [B][[NOME]][/B] --> <strong>[[NOME]]</strong></li>
				<li style="padding: 5px"><strong>Itálico</strong>: [I]<em>marcação</em>[/I]. Exemplo: [I][[NOME]][/I] --> <em>[[NOME]]</em></li>
				<li style="padding: 5px"><strong>Sublinhado</strong>: [S]<em>marcação</em>[/s]. Exemplo: [S][[NOME]][/S] --> <u>[[NOME]]</u></li>
				<li style="padding: 5px"><strong>COR</strong>: [COLOR=FF0000]<em>marcação</em>[/COLOR]. Exemplo: [COLOR=FF0000][[NOME]][/COLOR] --> <font style="color:#FF0000">[[NOME]]</font></li>
			</ul>
		</div>
	</div>
</div>
<div style="clear:both"></div>
<form action="index.php?option=com_p22evento&task=certificados&idevento=<?php echo intval( $this->idevento ); ?>" method="post" name="adminForm">
	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Imagem do Certificado' ); ?></legend>
			<div>
				<table class="admintable" width="800px" border="0">
					<tr>
						<td class="key">
							<label>
								<?php echo JText::_( 'Caminho da Imagem' ); ?>:
							</label>
						</td>
						<td>
							<div style="float: left;margin-top:3px">
								<?php echo $this->pathimages; ?>
							</div>
							<div style="float: left;margin-left: 5px">
								<input type="text" name="image_name" size="20" id="image_name" value="<?php echo $this->pathimagefile; ?>" />
								<input type="hidden" name="eventalias" size="20" id="eventalias" value="<?php echo $this->registro->eventalias; ?>" />
							</div>
							<div style="clear: both"></div>
						</td>
					</tr>
				</table>
			</div>
		</fieldset>
	</div>

	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Texto de Certificado para Inscritos' ); ?></legend>
			<div>
				<textarea name="texto_inscritos" id="texto_inscritos" rows="10" cols="90"><?php echo $this->registro->texto_inscritos; ?></textarea>
			</div>
		</fieldset>
	</div>

	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Texto de Certificado para Colaboradores' ); ?></legend>
			<div>
				<textarea name="texto_colaboradores" id="texto_colaboradores" rows="10" cols="90"><?php echo $this->registro->texto_colaboradores; ?></textarea>
			</div>
		</fieldset>
	</div>

	<div id="editcell">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Texto de Certificado para Palestrantes' ); ?></legend>
			<div>
				<textarea name="texto_palestrantes" id="texto_palestrantes" rows="10" cols="90"><?php echo $this->registro->texto_palestrantes; ?></textarea>
			</div>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="task" value="certificados" />
	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<input type="hidden" name="controller" value="certificado" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>