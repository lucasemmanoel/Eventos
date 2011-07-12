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
<script language="javascript" type="text/javascript">
	Window.onDomReady(function() {
		<?php if ( $this->user->guest) : ?>
			document.eventForm.email.focus();
		<?php endif; ?>
		document.formvalidator.setHandler('passverify', function (value) {
			return ($('password').value == value);
		});
	});

	var globalForm;
	function verifyUserEmail( form )
	{
		globalForm	= form;

		var email	= form.email;

		if ( email.value == '' )
		{
			email.focus();
			return;
		}

		var token	= form.token.value;
		var link	= document.getElementById('email_link');
		var loader	= document.getElementById('loader_email');

		email.value = email.value.toLowerCase();

		link.style.display		= 'none';
		loader.style.visibility = 'visible';
		email.readOnly			= true;

		if ( !isEmail( email.value ) )
		{
			alert( '<?php echo JText::_( 'E-mail inválido.' , true ); ?>' );
			link.style.display		= '';
			loader.style.visibility ='hidden';
			email.readOnly			= false;
			email.focus();
		}

		var dados	= 'option=com_p22evento&format=raw&task=ajax';
			dados	+= '&token=' + token;
			dados	+= '&'+token+'=1';
			dados	+= '&tp=verifyuseremail';
			dados	+= '&acao=verifyuseremail';
			dados	+= '&email=' + email.value;

		ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
	}
	
	function verifyUserCPF(fields)
	{
		form = globalForm;

		var cpf	= form.cpf;

		if ( cpf.value == '' )
		{
			cpf.focus();
			return;
		}
		else
		{
			if ( !ValidarCPF( cpf ) )
			{
				cpf.focus();
				return;
			}
		}

		var token	= form.token.value;
		var link	= document.getElementById('cpf_link');
		var loader	= document.getElementById('loader_cpf');

		link.style.display		= 'none';
		loader.style.visibility = 'visible';
		cpf.readOnly			= true;

		var dados	= 'option=com_p22evento&format=raw&task=ajax';
			dados	+= '&token=' + token;
			dados	+= '&'+token+'=1';
			dados	+= '&tp=verifyusercpf';
			dados	+= '&acao=verifyusercpf';
			dados	+= '&fields=' + fields;
			dados	+= '&cpf=' + cpf.value;

		ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
	}

	function verifyUserUsername()
	{
		form = globalForm;

		var username = form.username;

		if ( username.value == '' )
		{
			username.focus();
			return;
		}

		var token	= form.token.value;
		var link	= document.getElementById('username_link');
		var loader	= document.getElementById('loader_username');

		link.style.display		= 'none';
		loader.style.visibility = 'visible';
		username.readOnly		= true;

		var dados	= 'option=com_p22evento&format=raw&task=ajax';
			dados	+= '&token=' + token;
			dados	+= '&'+token+'=1';
			dados	+= '&tp=verifyuserusername';
			dados	+= '&acao=verifyuserusername';
			dados	+= '&username=' + username.value;

		ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
	}

	function enableDisableFields( act , fieldValues , showCPFLink , showUsernameLink , showProfLink )
	{
		var email			= document.getElementById('email');
		var fields			= fieldValues.split(',');
		var cpf				= globalForm.cpf;
		var username		= globalForm.username;
		var button			= document.getElementById('button_submit');
		var cpf_link		= document.getElementById('cpf_link');
		var username_link	= document.getElementById('username_link');
		var profissao_link	= document.getElementById('profissao_link');
		var field;
		
		for( var b = 0 ; b < fields.length ; b++ )
		{
			field = globalForm[ fields[ b ] ];

			if ( act )
			{
				field.disabled = false;
				field.style.backgroundColor = '#FFF';
			}
			else
			{
				field.value = '';
				field.disabled = true;
				field.style.backgroundColor = '#DDD';
			}
		}
		
		if ( email )
		{
			email.readOnly = ( act ) ? true : false;
		}
		
		cpf_link.style.display = ( showCPFLink == true ) ? '' : 'none';
		
		cpf.readOnly = ( act && showUsernameLink ) ? true : false;

		if ( username )
		{
			username.readOnly = ( act && showProfLink ) ? true : false;
		}

		button.style.visibility = ( act && showProfLink ) ? 'visible' : 'hidden';

		if ( username )
		{
			username_link.style.display = ( showUsernameLink == true ) ? '' : 'none';
		}

		profissao_link.style.visibility = ( showProfLink ) ? 'visible' : 'hidden';
	}

	function setTpUser( tp )
	{
		var newUser_box = document.getElementById('newUser_box');
		var user_box	= document.getElementById('user_box_login');

		newUser_box.style.display	= ( tp == 1 ) ? 'none ': '';
		user_box.style.display		= ( tp == 0 ) ? 'none' : '';

		if ( tp == 1 )
		{
			document.comlogin.username.focus();
		}
		else
		{
			document.eventForm.email.focus();
		}
	}

	function subscribeUser( id_evento )
	{
		if ( !id_evento ) return;

		var id = document.eventForm2.id.value;

		if ( !id ) return;

		document.getElementById('loader_subscribe').style.visibility = 'visible';
		document.getElementById('subscribe_button').disabled = true;

		var token	= document.eventForm2.token.value;

		var dados	= 'option=com_p22evento&format=raw&task=ajax';
			dados	+= '&token=' + token;
			dados	+= '&'+token+'=1';
			dados	+= '&tp=subscribe_user';
			dados	+= '&acao=subscribe_user';
			dados	+= '&tp_reg=0';
			dados	+= '&evento=' + id_evento;
			dados	+= '&user=' + id;
			
		ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
	}
</script>
<style type="text/css">
	.icon-48-p22Inscritos{ background-image: url('administrator/components/com_p22evento/images/inscritos.png') }
</style>
<div class="padding" style="width:99%;margin-left:auto;margin-right: auto;margin-top:5px">
	<div id="toolbar-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
			</div>
		</div>
		<div class="m">
			<div class="header icon-48-p22Inscritos">
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
	<?php if ( $this->user->guest ) : ?>
	<div id="user_radio_box" style="display:<?php echo ( !$this->user->guest ) ? 'none' : ''; ?>">
		<div id="toolbar-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<form action="index.php" method="post" name="tpUser">
					<div class="col100">
						<div style="width:65%;margin-left:auto;margin-right:auto">
							<div style="float:left">
								<div style="float:left">
									<input type="radio" name="tp_user" id="tpUser0" onchange="setTpUser(0)" value="0" checked="true" />
								</div>
								<div style="float:left;margin-left:3px; margin-top:2px">
									<label for="tpUser0">Não sou cadastrado</label>
								</div>
							</div>
							<div style="float:left;margin-left:30px">
								<div style="float:left">
									<input type="radio" name="tp_user" id="tpUser1" onchange="setTpUser(1)" value="1" />
								</div>
								<div style="float:left;margin-left:3px; margin-top:2px">
									<label for="tpUser1">Sou cadastrado</label>
								</div>
							</div>
							<div class="clr"></div>
						</div>
					</div>
				</form>

				<div class="clr"></div>
			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div id="newUser_box" style="display:<?php echo ( !$this->user->guest ) ? 'none' : ''; ?>">
		<div id="element-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<form action="index.php" method="post" name="eventForm" class="form-validate">
					<div class="col100">
						<fieldset class="adminform">
							<legend><?php echo JText::_( 'Inscrição' ); ?></legend>

							<table class="admintable" border="0" style="width:100%">
								<tr id="label_email">
									<td class="key">
										<label for="email" id="emailmsg">
											<?php echo JText::_( 'E-mail' ); ?>:
										</label>
									</td>
									<td>
										<div style="float: left">
											<input class="inputbox validate-email" type="text" name="email"
										   id="email" size="40" maxlength="150" value="<?php echo $this->registro->email; ?>" />
										</div>
										<div style="float: left; margin-left: 8px; margin-top: 3px" id="email_link">
											<a href="javascript:verifyUserEmail(document.eventForm)">
												Verificar
											</a>
										</div>
										<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_email">
											<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
										</div>
										<div class="clr"></div>
									</td>
								</tr>
								<tr>
									<td class="key">
										<label for="cpf" id="cpfmsg">
											<?php echo JText::_( 'CPF' ); ?>:
										</label>
									</td>
									<td>
										<div style="float: left">
											<input class="inputbox required validate-verifycpf" type="text" name="cpf" disabled="true" style="background-color: #DDD"
										   id="cpf" size="14" maxlength="14" value="<?php echo $this->registro->cpf; ?>" onkeyup="FormataCpf(this, event)" />
										</div>
										<div style="float: left; margin-left: 8px; margin-top: 3px;display:none" id="cpf_link">
											<a href="javascript:verifyUserCPF('nome,username')">
												Verificar
											</a>
										</div>
										<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_cpf">
											<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
										</div>
										<div class="clr"></div>
									</td>
								</tr>
								<tr id="label_nome">
									<td class="key">
										<label for="nome" id="nomemsg">
											<?php echo JText::_( 'Nome' ); ?>:
										</label>
									</td>
									<td>
										<input class="inputbox required" type="text" name="nome" disabled="true" style="background-color: #DDD"
									   id="nome" size="40" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
									</td>
								</tr>
								<tr id="label_username">
									<td class="key">
										<label for="username" id="usernamemsg">
											<?php echo JText::_( 'Usuário' ); ?>:
										</label>
									</td>
									<td>
										<div style="float: left">
											<input class="inputbox required validate-username" type="text" name="username" disabled="true" style="background-color: #DDD"
										   id="username" size="30" maxlength="150" value="<?php echo $this->registro->username; ?>" />
										</div>
										<div style="float: left; margin-left: 8px; margin-top: 3px;display:none" id="username_link">
											<a href="javascript:verifyUserUsername()">
												Verificar
											</a>
										</div>
										<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_username">
											<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
										</div>
										<div class="clr"></div>
									</td>
								</tr>
								<tr id="label_password">
									<td class="key">
										<label for="password" id="passwordmsg">
											<?php echo JText::_( 'Senha' ); ?>:
										</label>
									</td>
									<td>
										<input class="inputbox required validate-password" type="password" name="password" disabled="true" style="background-color: #DDD"
									   id="password" size="30" maxlength="150" value="" />
									</td>
								</tr>
								<tr id="label_password_verify">
									<td class="key">
										<label for="password2" id="password2msg">
											<?php echo JText::_( 'Verificar Senha' ); ?>:
										</label>
									</td>
									<td>
										<input class="inputbox required validate-passverify" type="password" name="password2" disabled="true" style="background-color: #DDD"
									   id="password2" size="30" maxlength="150" value="" />
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
											<select name="id_cidade" id="id_cidade" disabled="true" class="inputbox required" style="background-color:#DDD">
												<option value="">-Selecione-</option>
											</select>
										</div>
										<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_cidades">
											<img src="./administrator/components/com_p22evento/images/loader.gif" alt="" />
										</div>
										<div style="clear:both"></div>
									</td>
								</tr>
								<tr>
									<td class="key">
										<label for="id_profissao">
											<?php echo JText::_( 'Profissão' ); ?>:
										</label>
									</td>
									<td id="profissoes_field">
										<div style="float: left" id="p22SelectProfissoes">
											<?php echo $this->select['profissoes']; ?>
										</div>
										<div style="float: left; margin-left: 8px; margin-top: 3px;visibility:hidden" id="profissao_link">
											<a href="javascript:addProfissao()">
												Adicionar
											</a>
										</div>
										<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_profissao">
											<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
										</div>
										<div class="clr"></div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<button id="button_submit" style="visibility:hidden" class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>

					<div class="clr"></div>
					<input type="hidden" name="option" value="com_p22evento" />
					<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
					<input type="hidden" name="id" id="id" value="<?php echo $this->registro->id; ?>" />
					<input type="hidden" name="task" value="save" />
					<input type="hidden" name="controller" value="inscricao" />
					<input type="hidden" name="tp_cad" value="0" />
					<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
					<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->item; ?>" />
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
	<div id="user_box_login" style="display:none">
		<div id="element-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">

				<?php
				if ( JPluginHelper::isEnabled('authentication' , 'openid' ) ) :
				$lang = &JFactory::getLanguage();
				$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );

				$langScript = 	'var JLanguage = {};'.
								' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
								' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
								' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
								' var comlogin = 1;';
				$doc = &JFactory::getDocument();
				$doc->addScriptDeclaration( $langScript );
				JHTML::_('script', 'openid.js');
				endif;
				?>
				<form action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure')); ?>" method="post" name="comlogin" id="com-form-login" target="user_login">

					<div style="width:40%;margin-left:auto;margin-right:auto;border:1px solid #CCC;padding:10px;background-color:#f7f7f7">
						<p id="com-form-login-username">
							<label for="username"><strong><?php echo JText::_('Nome de Usuário') ?></strong></label><br />
							<input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
						</p>
						<p id="com-form-login-password">
							<label for="passwd"><strong><?php echo JText::_('Senha') ?></strong></label><br />
							<input type="password" id="passwd" name="passwd" class="inputbox" size="18" alt="password" />
						</p>
						<div style="text-align: center">
							<input type="submit" name="Submit" class="button" value="<?php echo JText::_('Entrar') ?>" />
						</div>
					</div>

					<input type="hidden" name="option" value="com_p22evento" />
					<input type="hidden" name="controller" value="inscricao" />
					<input type="hidden" name="idevento" value="<?php echo $this->idevento; ?>" />
					<input type="hidden" name="tmpl" value="component" />
					<input type="hidden" name="task" value="login" />
					<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
					<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->item; ?>" />
					<?php echo JHTML::_( 'form.token' ); ?>
					<iframe src="index2.php?option=com_p22evento&controller=inscricao&task=login" id="user_login" name="user_login" width="100%" marginwidth="0" marginheight="0" scrolling="no" style="width:0px;height:0px;border:0px solid #FFF" frameborder="0"></iframe>
<!--					<iframe src="index2.php?option=com_p22evento&controller=inscricao&task=login" id="user_login" name="user_login" width="100%" marginwidth="0" marginheight="0" scrolling="no" style="width:600px;height:300px;border:0px solid #FFF" frameborder="0"></iframe>-->
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
	<?php endif; ?>
	<div id="logged_user_box" style="display:<?php echo ( !$this->user->guest ) ? '' : 'none'; ?>">
		<div id="element-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<div id="logged_user_box_subscribe" style="display:<?php echo ( !$this->registro->id && !$this->registro->inscricao ) ? '' : 'none'; ?>">
					<form action="index.php" method="post" name="eventForm3" class="form-validate">
						<div class="col100">
							<fieldset class="adminform">
								<legend><?php echo JText::_( 'Inscrição' ); ?></legend>

								<table class="admintable" border="0" style="width:100%">
									<tr>
										<td class="key">
											<label for="cpf" id="cpfmsg">
												<?php echo JText::_( 'CPF' ); ?>:
											</label>
										</td>
										<td>
											<div style="float: left">
												<input class="inputbox required validate-verifycpf" type="text" name="cpf"
											   id="cpf" size="14" maxlength="14" value="<?php echo $this->registro->cpf; ?>" onkeyup="FormataCpf(this, event)" />
											</div>
											<div style="float: left; margin-left: 8px; margin-top: 3px" id="cpf_link">
												<a href="javascript:globalForm=document.eventForm3;verifyUserCPF('uf,id_cidade,id_profissao')">
													Verificar
												</a>
											</div>
											<div style="float: left; margin-left: 8px;visibility:hidden" id="loader_cpf">
												<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
											</div>
											<div class="clr"></div>
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
												<select name="id_cidade" id="id_cidade" disabled="true" class="inputbox required" style="background-color:#DDD">
													<option value="">-Selecione-</option>
												</select>
											</div>
											<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_cidades">
												<img src="./administrator/components/com_p22evento/images/loader.gif" alt="" />
											</div>
											<div style="clear:both"></div>
										</td>
									</tr>
									<tr>
										<td class="key">
											<label for="id_profissao">
												<?php echo JText::_( 'Profissão' ); ?>:
											</label>
										</td>
										<td id="profissoes_field">
											<div style="float: left" id="p22SelectProfissoes">
												<?php echo $this->select['profissoes']; ?>
											</div>
											<div style="float: left; margin-left: 8px; margin-top: 3px;visibility:hidden" id="profissao_link">
												<a href="javascript:addProfissao()">
													Adicionar
												</a>
											</div>
											<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_profissao">
												<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
											</div>
											<div class="clr"></div>
										</td>
									</tr>
									<tr>
										<td colspan="2" align="center">
											<button id="button_submit" style="visibility:hidden" class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
										</td>
									</tr>
								</table>
							</fieldset>
						</div>

						<div class="clr"></div>
						<input type="hidden" name="option" value="com_p22evento" />
						<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
						<input type="hidden" name="id_user" id="id_user" value="<?php echo $this->user->id; ?>" />
						<input type="hidden" name="id" id="id" value="<?php echo $this->registro->id; ?>" />
						<input type="hidden" name="task" value="save" />
						<input type="hidden" name="controller" value="inscricao" />
						<input type="hidden" name="tp_cad" value="1" />
						<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
						<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->item; ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</div>
				<div id="logged_user_box_form" style="display:<?php echo ( $this->registro->id && !$this->registro->inscricao ) ? '' : 'none'; ?>">
					<form action="index.php" method="post" name="eventForm2">
						<div class="col100">
							<fieldset class="adminform">
								<legend><?php echo JText::_( 'Inscrição' ); ?></legend>

								<table class="admintable" border="0" width="100%">
									<tr id="label_email">
										<td class="key">
											<label for="email">
												<?php echo JText::_( 'Clique para confirmar a Inscrição' ); ?>:
											</label>
										</td>
										<td width="65%">
											<div style="float: left">
												<button class="button" id="subscribe_button" type="button" onclick="subscribeUser('<?php echo $this->idevento; ?>')"><?php echo JText::_('Confirmar'); ?></button>
											</div>
											<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_subscribe">
												<img src="./administrator/components/com_p22evento/images/load.gif" alt="" border="0" />
											</div>
											<div class="clr"></div>
										</td>
									</tr>
								</table>
							</fieldset>
						</div>

						<div class="clr"></div>
						<input type="hidden" name="option" value="com_p22evento" />
						<input type="hidden" name="id" id="id" value="<?php echo $this->registro->id; ?>" />
						<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
						<input type="hidden" name="task" value="inscrever" />
						<input type="hidden" name="controller" value="inscricao" />
						<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
						<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->item; ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</div>
				<div id="logged_user_box_registered" style="display:<?php echo ( $this->registro->id && $this->registro->inscricao ) ? '' : 'none'; ?>">
					<div style="background-color: #E5ED00;border: 2px solid #D1D800;float: left;width: 98%;margin-left: 10px; margin-bottom: 10px">
						<div style="background-color: #D1D800;padding:7px;font-weight: bold;color: #333;font-size: 16px">
							Opa!
						</div>
						<div style="padding: 10px;font-size: 14px">
							Você já está inscrito neste evento.
						</div>
						<div style="margin-bottom:10px" align="center">
							<button class="button" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_p22evento&Itemid=' . intval( $this->item ) ); ?>'"><?php echo JText::_('Voltar ao Perfil'); ?></button>
							<button class="button" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_p22evento&task=selevento&idevento='. intval( $this->idevento ) .'&Itemid=' . intval( $this->item ) ); ?>'"><?php echo JText::_('Voltar ao Painel do Evento'); ?></button>
						</div>
					</div>
				</div>
				<div id="logged_user_box_msg" style="display:none">

					<div style="background-color: #E5ED00;border: 2px solid #D1D800;float: left;width: 98%;margin-left: 10px; margin-bottom: 10px">
						<div style="background-color: #D1D800;padding:7px;font-weight: bold;color: #333;font-size: 16px">
							Sucesso!
						</div>
						<div style="padding: 10px;font-size: 14px">
							<div style="margin-bottom:10px">
								Sua inscrição no evento <strong><?php echo $this->eventName; ?></strong> foi realizada com sucesso!
							</div>
							<div style="margin-bottom:10px">
								Para que você tenha acesso ao certificado de Participação do evento, você deve confirmar a inscrição do evento
								junto a equipe organizadora.
							</div>
							<div style="margin-bottom:10px" align="center">
								<button class="button" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_p22evento&Itemid=' . intval( $this->item ) ); ?>'"><?php echo JText::_('Voltar ao Perfil'); ?></button>
							<button class="button" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_p22evento&task=selevento&idevento='. intval( $this->idevento ) .'&Itemid=' . intval( $this->item ) ); ?>'"><?php echo JText::_('Voltar ao Painel do Evento'); ?></button>
							</div>
						</div>
					</div>
					<div style="clear:both"></div>

				</div>
				<div class="clr"></div>
			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
		</div>
	</div>
</div>