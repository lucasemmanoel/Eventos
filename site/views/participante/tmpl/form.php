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

if ( !empty( $this->registro->curriculo ) )
{
	$curriculo = str_replace( "\r\n" , '<br />' , $this->registro->curriculo );
	$chkCurriculo = true;
}
$first_name		= explode( ' ' , $this->registro->nome );
$first_name		= $first_name[0];

$orkut_w		= ( !empty( $this->registro->orkut ) ) ? -10 : -232;
$orkut_h		= -8;

$twitter_w		= ( !empty( $this->registro->twitter ) ) ? -11 : -233;
$twitter_h		= -61;

$youtube_w		= ( !empty( $this->registro->youtube ) ) ? -12 : -234;
$youtube_h		= -168;

$blog_w			= ( !empty( $this->registro->site ) ) ? -13 : -235;
$blog_h			= -273;
?>
<style type="text/css">
.icon-48-p22Inscritos{ background-image: url('/./administrator/components/com_p22evento/images/inscritos.png') }
#system-message dt { display: none; }
dd.notice , dd.error , dd.message{ margin-left: 0px; }
dd.notice ul, dd.error ul, dd.message ul { padding: 9px !important; }
dd.notice ul li, dd.error ul li, dd.message ul li { list-style: none; margin-left: 30px; }
</style>
<script language="javascript" type="text/javascript">
	<!--
	Window.onDomReady(function(){
		carregaCidade( document.getElementById('uf').value , 'getCidades' , 'td_cidades' , '<?php echo $this->registro->id_cidade; ?>' );
		window.setTimeout( "document.josForm.nome.focus()" , 1500 );
		window.setTimeout( "updateDataProfile()" , 1000 );
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});

	function enableFields( act )
	{
		var fields	= new Array(
								document.getElementById('cpf'),
								document.getElementById('uf'),
								document.getElementById('id_profissao')
							);

		for( var b = 0 ; b < fields.length ; b++ )
		{
			fields[ b ].value		= '';
			fields[ b ].disabled	= act;
		}

		document.getElementById('id_cidade').value = '';
		document.getElementById('id_cidade').disabled = act;

		document.getElementById('profissao_link').style.visibility = ( act == true ) ? 'hidden' : 'visible';
	}

	function updateDataProfile()
	{
		var field		= new Array( 'first_name' , 'profissao' , 'curriculo' );
		var height		= new Array( 'orkut_h' , 'twitter_h' , 'youtube_h' , 'blog_h' );
		var curriculo	= '<?php echo $chkCurriculo; ?>';
		
		window.top.document.getElementById( 'curriculo_div_field' ).style.display = ( curriculo == 1 ) ? '' : 'none';

		var value;
		for ( var a = 0 ; a < field.length ; a++ )
		{
			if( window.top.document.getElementById( field[ a ] + '_field'  ) )
			{
				switch( field[ a ] )
				{
					case 'first_name':
						value = '<?php echo $first_name; ?>';
						break;
					case 'profissao':
						value = '<?php echo $this->registro->profissao ?>';
						break;
					case 'curriculo':
						value = '<?php echo $curriculo; ?>';
						break;
				}
				window.top.document.getElementById( field[ a ] + '_field' ).innerHTML = value;
			}
		}

		window.top.document.getElementById( 'icon_orkut' ).setAttribute( 'style' , 'background-position: <?php echo $orkut_w; ?>px <?php echo $orkut_h; ?>px' );
		window.top.document.getElementById( 'icon_twitter' ).setAttribute( 'style' , 'background-position: <?php echo $twitter_w; ?>px <?php echo $twitter_h; ?>px' );
		window.top.document.getElementById( 'icon_youtube' ).setAttribute( 'style' , 'background-position: <?php echo $youtube_w; ?>px <?php echo $youtube_h; ?>px' );
		window.top.document.getElementById( 'icon_blog' ).setAttribute( 'style' , 'background-position: <?php echo $blog_w; ?>px <?php echo $blog_h; ?>px' );
	}
	// -->
</script>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div class="header icon-48-p22Inscritos">
				Participante <span style="font-size: 13px">[Edição]</span>
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
			<form action="<?php echo JRoute::_('index2.php?option=com_p22evento&controller=participante&task=edit'); ?>" method="post" id="josForm" name="josForm" class="form-validate">
				<div class="col100">
					<fieldset class="adminform">
						<legend><?php echo JText::_( 'Details' ); ?></legend>

						<table class="admintable" border="0" width="100%">
							<tr id="label_nome">
								<td class="key">
									<label for="nome" id="nomemsg">
										<?php echo JText::_( 'Nome' ); ?>:
									</label>
								</td>
								<td>
									<input type="text" name="nome" class="inputbox required"
								   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
								</td>
							</tr>
							<tr id="label_nome">
								<td class="key">
									<label for="email" id="emailmsg">
										<?php echo JText::_( 'E-mail' ); ?>:
									</label>
								</td>
								<td>
									<input type="text" name="email" class="inputbox required validate-email"
								   id="email" size="50" maxlength="150" value="<?php echo $this->registro->email; ?>" />
								</td>
							</tr>
							<tr id="label_username">
								<td class="key">
									<label>
										<?php echo JText::_( 'Usuário' ); ?>:
									</label>
								</td>
								<td>
									<?php echo $this->registro->username; ?>
									<input type="hidden" name="username" id="username" value="<?php echo $this->registro->username; ?>"
								</td>
							</tr>
							<tr id="label_password">
								<td class="key">
									<label for="password">
										<?php echo JText::_( 'Senha' ); ?>:
									</label>
								</td>
								<td>
									<input class="inputbox validate-password" type="password" name="password"
								   id="password" size="30" maxlength="150" value="" />
								</td>
							</tr>
							<tr id="label_password_verify">
								<td class="key">
									<label for="verifypassword">
										<?php echo JText::_( 'Verificar Senha' ); ?>:
									</label>
								</td>
								<td>
									<input class="inputbox validate-passverify" type="password" name="password2"
								   id="password2" size="30" maxlength="150" value="" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="cpf" id="cpfmsg">
										<?php echo JText::_( 'CPF' ); ?>:
									</label>
								</td>
								<td>
									<input class="inputbox required" type="text" name="cpf"
								   id="cpf" size="14" maxlength="14" value="<?php echo $this->registro->cpf; ?>" onkeyup="FormataCpf(this, event)" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="uf" id="ufmsg">
										<?php echo JText::_( 'Estado' ); ?>:
									</label>
								</td>
								<td>
									<?php echo $this->select['UF']; ?>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="id_cidade" id="id_cidademsg">
										<?php echo JText::_( 'Cidade' ); ?>:
									</label>
								</td>
								<td>
									<div style="float:left" id="td_cidades">
										<select name="id_cidade" class="inputbox required" id="id_cidade" disabled="true">
											<option value="">-Selecione-</option>
										</select>
									</div>
									<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_cidades">
										<img src="/./administrator/components/com_p22evento/images/loader.gif" alt="" />
									</div>
									<div style="clear:both"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="id_profissao" id="id_profissaomsg">
										<?php echo JText::_( 'Profissão' ); ?>:
									</label>
								</td>
								<td id="profissoes_field">
									<div style="float: left" id="p22SelectProfissoes">
										<?php echo $this->select['profissoes']; ?>
									</div>
									<div style="float: left; margin-left: 8px; margin-top: 3px;" id="profissao_link">
										<a href="javascript:addProfissao()">
											Adicionar
										</a>
									</div>
									<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_profissao">
										<img src="/./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
									</div>
									<div class="clr"></div>
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="twitter">
										Usuário Twitter:
									</label>
								</td>
								<td>
									@
									<input class="inputbox" type="text" name="twitter"
								   id="twitter" size="28" maxlength="150" value="<?php echo $this->registro->twitter; ?>" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="twitter">
										ID do Orkut:
									</label>
								</td>
								<td>
									<input class="inputbox" type="text" name="orkut"
								   id="orkut" size="30" maxlength="150" value="<?php echo $this->registro->orkut; ?>" />
								   <img src="/./components/com_p22evento/assets/images/information.png" alt="" title="Somente o UID. Exemplo: no http://www.orkut.com.br/Main#Profile?uid=16703771483943557407, informar apenas 16703771483943557407." style="position:relative;top:3px" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="twitter">
										Canal do Youtube:
									</label>
								</td>
								<td>
									<input class="inputbox" type="text" name="youtube"
								   id="youtube" size="30" maxlength="150" value="<?php echo $this->registro->youtube; ?>" />
								   <img src="/./components/com_p22evento/assets/images/information.png" alt="" title="Link completo." style="position:relative;top:3px" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="twitter">
										Blog / Site
									</label>
								</td>
								<td>
									<input class="inputbox" type="text" name="site"
								   id="site" size="30" maxlength="150" value="<?php echo $this->registro->site; ?>" />
								   <img src="/./components/com_p22evento/assets/images/information.png" alt="" title="Link completo." style="position:relative;top:3px" />
								</td>
							</tr>
							<tr>
								<td class="key">
									<label for="twitter">
										Mini-curriculo
									</label>
								</td>
								<td>
									<textarea name="curriculo" id="curriculo" rows="5" cols="48"><?php echo $this->registro->curriculo; ?></textarea>
								   <img src="/./components/com_p22evento/assets/images/information.png" alt="" title="Mini-currículo tem de ser curto e objetivo." style="position:relative;top:3px" />
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
				<input type="hidden" name="id_user" id="id_user" value="<?php echo $this->registro->id_user; ?>" />
				<input type="hidden" name="task" value="save" />
				<input type="hidden" name="controller" value="participante" />
				<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
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