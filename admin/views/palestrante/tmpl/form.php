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

$seImgExists	= ( $this->registro->avatar_img ) ? file_exists( JPATH_SITE . DS . $this->registro->avatar_img ) : false;
$avatar_img		= ( $seImgExists ) ? '../' . $this->registro->avatar_img : 'components/com_p22evento/images/no_picture.jpg';
?>
<script language="javascript" type="text/javascript">
	window.onload=function()
	{
		<?php if ( $this->registro->id ) : ?>
			checkForm( 1 , true );
		<?php else: ?>
			document.adminForm.nome.focus();
		<?php endif; ?>

		carregaCidade( document.getElementById('uf').value , 'getCidades' , 'td_cidades' , '<?php echo $this->registro->id_cidade; ?>' );
	}
	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		if ( document.getElementById('tp_cadN') )
		{
			if ( document.getElementById('tp_cadN').checked == true )
			{
				// do field validation
				if (form.nome.value.length == 0)
				{
					alert( "<?php echo JText::_( 'Informe o nome.', true ); ?>" );
					form.nome.focus();
					return false;
				}
				if (form.email.value.length == 0) {
					alert( "<?php echo JText::_( 'Informe o email.', true ); ?>" );
					form.email.focus();
					return false;
				}

				if( form.email.value.indexOf('@') == -1 || form.email.value.indexOf('.') == -1 )
				{
					alert ('Email inválido!');
					form.email.focus();
					return false;
				}

				if (form.username.value.length == 0) {
					alert( "<?php echo JText::_( 'Informe o Usuário.', true ); ?>" );
					form.username.focus();
					return false;
				}

				if (form.password.value.length == 0) {
					alert( "<?php echo JText::_( 'Informe a Senha.', true ); ?>" );
					form.username.focus();
					return false;
				}

				if (form.password.value != form.password2.value ) {
					alert( "<?php echo JText::_( 'Verificação de senha não bate com a Senha informada.', true ); ?>" );
					return false;
				}
			}
		}
		else
		{
			if (form.id_user.value.length == 0) {
				alert( "<?php echo JText::_( 'Informe o nome do participante.', true ); ?>" );
				form.id_user.focus();
				return false;
			}
		}

		if( !ValidarCPF( form.cpf ) )
		{
			return false;
		}

		if ( form.uf.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe o Estado.', true ); ?>" );
			form.uf.focus();
			return false;
		}

		if ( form.id_cidade.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe a Cidade.', true ); ?>" );
			form.id_cidade.focus();
			return false;
		}

		if ( form.id_profissao.value.length == 0)
		{
			alert( "<?php echo JText::_( 'Informe a Profissao.', true ); ?>" );
			form.id_profissao.focus();
			return false;
		}
		
		submitform(pressbutton);
	}

	function checkForm( value , load )
	{
		var act	= ( value ) ? true : false;
		var labels = new Array(
								document.getElementById('label_nome'),
								document.getElementById('label_email'),
								document.getElementById('label_username'),
								document.getElementById('label_password'),
								document.getElementById('label_password_verify')
							);
		var users = document.getElementById('label_users');

		for( var b = 0 ; b < labels.length ; b++ )
		{
			labels[ b ].style.display = ( value == 1 ) ? 'none' : '';
		}

		users.style.display = ( value == 1 ) ? '' : 'none';
		
		if ( !load ) enableFields( act );
	}

	function changeImgAvatar( id , nome_img )
	{
		var img = '<img';
		var src;

		if( nome_img )
		{
			var vr;
			vr = nome_img.split( '_' );

			if( vr[0] == 'resize' )
			{
				src = '../images/stories/eventos/avatar/' + id + '/' + nome_img;
			}
			else
			{
				src = 'components/com_p22evento/images/no_picture.jpg';
			}
		}
		else
		{
			src = 'components/com_p22evento/images/no_picture.jpg';
		}
		img += ' src="'+ src +'" alt="" width="180px" />';
		document.getElementById('campo_img_avatar').innerHTML = img;
	}

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
</script>
<style type="text/css">
	.icon-48-p22Palestrantes{ background-image: url('components/com_p22evento/images/palestrantes.png') }
</style>
<table>
	<tr>
		<td>
			<h1><?php echo $this->titleRegistro; ?></h1>
		</td>
	</tr>
</table>
<form action="index.php" method="post" name="adminForm">
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
			
			<table class="admintable" width="800px" border="0">
				<?php if ( !$this->registro->id ) : ?>
				<tr>
					<td class="key">
						<label>
							<?php echo JText::_( 'Tipo de Usuário' ); ?>:
						</label>
					</td>
					<td>
						<div style="float: left">
							<input type="radio" name="tp_cad" id="tp_cadN" value="0" checked onchange="return checkForm( 0 , false )" /><label for="tp_cadN">Não Cadastrado no Sistema</label>
						</div>
						<div style="float: left;margin-left: 20px">
							<input type="radio" name="tp_cad" id="tp_cadS" value="1" onchange="return checkForm( 1 , false )" /><label for="tp_cadS">Cadastrado no Sistema</label>
						</div>
						<div style="clear: both"></div>
					</td>
				</tr>
				<?php endif; ?>
				<tr id="label_users" style="display: none">
					<td class="key">
						<label for="id_user">
							<?php echo JText::_( 'Usuários do Sistema' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left">
							<?php if ( !$this->registro->id ) : ?>
								<?php echo $this->select['users']; ?>
							<?php else: ?>
								<?php echo $this->registro->name; ?>
								<input type="hidden" name="id_user" id="id_user" value="<?php echo $this->registro->id_user; ?>" />
							<?php endif; ?>
						</div>
						<div style="float:left;margin-left:8px;visibility:hidden" id="users_loader">
							<img src="components/com_p22evento/images/loader.gif" alt="" />
						</div>
					</td>
				</tr>
				<tr id="label_nome">
					<td class="key">
						<label for="nome">
							<?php echo JText::_( 'Nome' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="nome"
					   id="nome" size="50" maxlength="150" value="<?php echo $this->registro->nome; ?>" />
					</td>
				</tr>
				<tr id="label_email">
					<td class="key">
						<label for="email">
							<?php echo JText::_( 'email' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="email"
					   id="email" size="50" maxlength="150" value="<?php echo $this->registro->email; ?>" />
					</td>
				</tr>
				<tr id="label_username">
					<td class="key">
						<label for="username">
							<?php echo JText::_( 'Usuário' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="username"
					   id="username" size="50" maxlength="150" value="<?php echo $this->registro->username; ?>" />
					</td>
				</tr>
				<tr id="label_password">
					<td class="key">
						<label for="password">
							<?php echo JText::_( 'Senha' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="password" name="password"
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
						<input class="inputbox" type="password" name="password2"
					   id="password2" size="30" maxlength="150" value="" />
					</td>
				</tr>
				<?php if ( $this->registro->id ) : ?>
				<tr>
					<td class="key">
						<label for="aliasFoto">
							<?php echo JText::_( 'Foto' ); ?>:
						</label>
					</td>
					<td>
						<div id="campo_img_avatar" style="width: 180px;border:3px solid #ddd;margin-bottom:5px">
							<img src="<?php echo $avatar_img; ?>" border="0" alt="" />
						</div>
						<div id="div_pathFoto" style="margin-bottom:5px">
							<strong>Caminho da imagem:</strong> <?php echo $this->registro->avatar_img; ?>
						</div>
						<div>
							<a title="Definir Novo Avatar" class="modal" href="index3.php?option=com_p22evento&controller=palestrante&task=avatar&idevento=<?php echo $this->idevento; ?>&id=<?php echo $this->registro->id; ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
								Novo Foto
							</a>
						</div>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td class="key">
						<label for="curriculo">
							<?php echo JText::_( 'Mini-currículo' ); ?>:
						</label>
					</td>
					<td>
						<textarea name="curriculo" id="curriculo" cols="69" rows="10"><?php echo $this->registro->curriculo; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="cpf">
							<?php echo JText::_( 'CPF' ); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="cpf"
					   id="cpf" size="14" maxlength="14" value="<?php echo $this->registro->cpf; ?>" onkeyup="FormataCpf(this, event)" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="uf">
							<?php echo JText::_( 'Estado' ); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->select['UF']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="id_cidade">
							<?php echo JText::_( 'Cidade' ); ?>:
						</label>
					</td>
					<td>
						<div style="float:left" id="td_cidades">
							<select name="id_cidade" id="id_cidade" disabled="true">
								<option value="">-Selecione-</option>
							</select>
						</div>
						<div style="float:left;margin-left: 10px;visibility:hidden" id="loader_cidades">
							<img src="components/com_p22evento/images/loader.gif" alt="" />
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
						<div style="float: left; margin-left: 8px; margin-top: 3px;" id="profissao_link">
							<a href="javascript:addProfissao()">
								Adicionar
							</a>
						</div>
						<div style="float: left; margin-left: 8px;visibility: hidden" id="loader_profissao">
							<img src="components/com_p22evento/images/load.gif" alt="" border="0" />
						</div>
						<div class="clr"></div>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	
	<input type="hidden" name="option" value="com_p22evento" />
	<input type="hidden" name="idevento" id="idevento" value="<?php echo $this->idevento; ?>" />
	<input type="hidden" name="id" id="id" value="<?php echo $this->registro->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="palestrante" />
	<?php if( $this->registro->id ) : ?>
		<input type="hidden" name="tp_cad" value="2" />
	<?php endif; ?>
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>