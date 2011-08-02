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

$first_name		= explode( ' ' , $this->registro->nome );
$expReg			= explode( ' ' , $this->registro->registrado_em );
$dataReg		= implode( '/' , array_reverse( explode( '-' , $expReg[0] ) ) );
$horaReg		= $expReg[1];

$orkut_w		= ( $this->registro->orkut ) ? -10 : -232;
$orkut_h		= -8;
$orkut_hover	= -121;

$twitter_w		= ( $this->registro->twitter ) ? -11 : -233;
$twitter_h		= -61;
$twitter_hover	= -122;

$youtube_w		= ( $this->registro->youtube ) ? -12 : -234;
$youtube_h		= -168;
$youtube_hover	= -123;

$blog_w			= ( $this->registro->site ) ? -13 : -235;
$blog_h			= -273;
$blog_hover		= -124;

$expLink		= explode( 'index.php' , JURI::base() );
$link_profile	= $expLink[0];
?>
<style type="text/css">
#icon_orkut {
	position:absolute;
	margin:-2px 0px 0px 5px;
	width:112px;
	height:42px;
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $orkut_w; ?>px <?php echo $orkut_h; ?>px;
	cursor:pointer;
}

#icon_orkut:hover {
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $orkut_hover; ?>px <?php echo $orkut_h; ?>px;
}

#icon_twitter {
	position:absolute;
	margin:-2px 0px 0px 135px;
	width:112px;
	height:42px;
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $twitter_w; ?>px <?php echo $twitter_h; ?>px;
	cursor:pointer;
}

#icon_twitter:hover {
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $twitter_hover; ?>px <?php echo $twitter_h; ?>px;
}

#icon_youtube {
	position:absolute;
	margin:-2px 0px 0px 265px;
	width:112px;
	height:45px;
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $youtube_w; ?>px <?php echo $youtube_h; ?>px;
	cursor:pointer;
}

#icon_youtube:hover {
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $youtube_hover; ?>px <?php echo $youtube_h; ?>px;
}

#icon_blog {
	position:absolute;
	margin:-2px 0px 0px 395px;
	width:112px;
	height:45px;
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $blog_w; ?>px <?php echo $blog_h; ?>px;
	cursor:pointer;
}

#icon_blog:hover {
	background:url( components/com_p22evento/assets/images/sociais.png ) no-repeat <?php echo $blog_hover; ?>px <?php echo $blog_h; ?>px;
}
</style>
<script type="text/javascript">
function showIcons()
{
	var show_icons	= ( screen.width > 1024 ) ? true : false;

	if ( !show_icons )
	{
		document.getElementById('tr_icons').style.display = 'none';
		document.getElementById('td_avatar').setAttribute('rowspan', '2' );
		document.getElementById('div_main_nome').style.width = '330px';
	}
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
			src = '/./images/stories/eventos/avatar/' + id + '/' + nome_img;
		}
		else
		{
			src = '/./administrator/components/com_p22evento/images/no_picture.jpg';
		}
	}
	else
	{
		src = './administrator/components/com_p22evento/images/no_picture.jpg';
	}
	img += ' src="'+ src +'" alt="" width="180px" />';
	document.getElementById('campo_img_avatar').innerHTML = img;
}
function showHideLinkAvatar( act )
{
	var campo = document.getElementById('link_avatar');
	switch( act )
	{
		case 'hide':
			campo.style.visibility = 'hidden';
			break;
		case 'show':
			campo.style.visibility = 'visible';
			break;

	}
}
function registerUser( tp , idevento , id)
{
	var token	= document.getElementById('token').value;

	buttonText(tp , 'Aguarde...' );

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&token=' + token;
		dados	+= '&'+token+'=1';
		dados	+= '&acao=register_user_profile';
		dados	+= '&tp=' + tp;
		dados	+= '&idevento=' + idevento;
		dados	+= '&user=' + id;

	ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
}
function buttonText(tp,text)
{
	var children = document.getElementById('button_' + tp ).getChildren();

	children[0].innerHTML = text;
}
Window.onDomReady(function() {
	showIcons();
});
</script>
<div id="campo_profile">
	<table cellspacing="4" border="0" align="center" width="92%">
		<tr>
			<td id="td_avatar" width="15%" valign="top" rowspan="<?php echo ( !JRequest::getVar('tmpl') ) ? 3 : 1; ?>" class="perfil_td_avatar" style="height:180px" onmouseover="showHideLinkAvatar('show')" onmouseout="showHideLinkAvatar('hide')">
				<div style="float:left" id="campo_img_avatar">
					<?php if( $this->registro->avatar_img && is_file( $this->registro->avatar_img ) ) : ?>
						<img src="./<?php echo $this->registro->avatar_img; ?>" alt="" width="180px" />
					<?php else: ?>
						<img src="./administrator/components/com_p22evento/images/no_picture.jpg" alt="" />
					<?php endif; ?>
				</div>
				<?php if ( !$this->user->guest && $this->user->id == $this->registro->id_user ) : ?>
				<div class="link_alterar_avatar" id="link_avatar">
					<a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_('index2.php?option=com_p22evento&controller=participante&task=avatar&pid=' . intval( $this->registro->id ) ); ?>">
						Alterar imagem
					</a>
				</div>
				<?php else: ?>
				<div id="link_avatar"></div>
				<?php endif; ?>
				<div style="clear:both"></div>
			</td>
			<td height="100px" class="perfil_td">
				<div style="margin-left:5px;float:left;padding:5px">
					<div style="width:493px;margin-bottom:10px" id="div_main_nome">
						<div style="float:left;margin-top:10px">
							<h1 style="color:#24B8FF;font-size:22px" id="first_name_field"><?php echo $first_name[0]; ?></h1>
						</div>
						<?php if ( !$this->user->guest && $this->user->id == $this->registro->id_user ) : ?>
						<div style="float:left;margin-top:15px;margin-left:5px">
							[ <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_('index2.php?option=com_p22evento&controller=participante&task=edit'); ?>">Editar</a> ]
						</div>
						<?php endif; ?>
						<div style="clear:both"></div>
					</div>
					<div>
						<strong>Cadastrado realizado em:</strong> <?php echo $dataReg; ?> às <?php echo $horaReg; ?>
					</div>
					<div>
						<strong>Link do perfil:</strong> <?php echo JRoute::_( $link_profile . '?option=com_p22evento&Itemid='. JRequest::getInt('Itemid') .'&pid=' . (int)$this->registro->id ); ?>
					</div>
					<div>
						<strong>Profissão:</strong> <span id="profissao_field"><?php echo $this->registro->profissao; ?></span>
					</div>
					<div style="margin-top:10px">
						<div id="curriculo_div_field" style="border:1px solid #DDD;background-color:#F7F7F7;float:left;width:490px;margin-left:auto;margin-right:auto;max-height:300px;overflow:hidden;padding:10px;display:<?php echo ( !$this->registro->curriculo ) ? 'none' : ''; ?>">
							<div><strong>Mini-currículo:</strong></div>
							<div style="margin-left:20px" id="curriculo_field"><?php echo nl2br( $this->registro->curriculo ); ?></div>
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
				<div style="clear:both"></div>
			</td>
		</tr>
		<?php if( !JRequest::getVar('tmpl') ) : ?>
		<tr id="tr_icons">
			<td class="perfil_none">
				<?php if( $this->registro->orkut ) : ?>
				<a href="<?php echo $this->registro->orkut; ?>" target="_blank"><div id="icon_orkut">&nbsp;</div></a>
				<?php else: ?>
				<a href="javascript:void(0)"><div id="icon_orkut" style="<?php echo ( !$this->registro->curriculo ) ? 'margin-top: -2px !important' : ''; ?>">&nbsp;</div></a>
				<?php endif; ?>
				<?php if( $this->registro->twitter ) : ?>
				<a href="http://twitter.com/<?php echo $this->registro->twitter; ?>" target="_blank"><div id="icon_twitter">&nbsp;</div></a>
				<?php else: ?>
				<a href="javascript:void(0)"<div id="icon_twitter" style="<?php echo ( !$this->registro->curriculo ) ? 'margin-top: -2px !important' : ''; ?>">&nbsp;</div></a>
				<?php endif; ?>
				<?php if( $this->registro->youtube ) : ?>
				<a href="<?php echo $this->registro->youtube; ?>" target="_blank"><div id="icon_youtube">&nbsp;</div></a>
				<?php else: ?>
				<a href="javascript:void(0)"<div id="icon_youtube" style="<?php echo ( !$this->registro->curriculo ) ? 'margin-top: -2px !important' : ''; ?>">&nbsp;</div></a>
				<?php endif; ?>
				<?php if( $this->registro->site ) : ?>
				<a href="<?php echo $this->registro->site; ?>" target="_blank"><div id="icon_blog">&nbsp;</div></a>
				<?php else: ?>
				<a href="javascript:void(0)"<div id="icon_blog" style="<?php echo ( !$this->registro->curriculo ) ? 'margin-top: -2px !important' : ''; ?>">&nbsp;</div></a>
				<?php endif; ?>
				<br />
			</td>
		</tr>
		<tr>
			<td class="perfil_icons"></td>
		</tr>
		<?php endif; ?>
		<?php if ( ( !$this->user->guest && $this->user->id == $this->registro->id_user ) && count( $this->registro->avaliar ) ) : ?>
		<tr>
			<td colspan="2" class="perfil_td_avaliador">
				<div class="perfil_titulo_avaliador">
					<div style="float:left">
						Avaliações Pendentes de Palestras / Mini-cursos
					</div>
					<div class="clr"></div>
				</div>
				<div class="perfil_infos_avaliador" style="padding:5px">
				<?php
				foreach( $this->registro->avaliar AS $evento => $palestras )
				{
				?>
				<div style="margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #CCC">
					<div style="font-weight: bold;font-size: 16px;margin-bottom:10px">
						<?php echo $evento; ?>
					</div>
					<table width="100%" class="adminlist">
					<?php
					$k = 0;
					$j = 1;
					for ($i=0, $n=count( $palestras ); $i < $n; $i++)
					{
						$row = &$palestras[ $i ];
						?>
						<tr class="<?php echo "row$k"; ?>" id="aval_palestra_<?php echo $row->id; ?>">
							<td width="3%" align="center" title="Período::<?php echo $row->periodo; ?>" class="hasTip">
								<?php echo $j; ?>
							</td>
							<td>
								<a id="link_avalpalestra_<?php echo $row->id; ?>" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_( 'index2.php?option=com_p22evento&task=avalpalestra&idevento='. intval( $row->id_evento ) .'&palestraid='. intval( $row->id ) .'&Itemid=' . JRequest::getInt('Itemid') ); ?>">
								<?php if ( $row->avaliado ) : ?>
									<span style="color:gray"><?php echo $row->nome; ?> <span style="font-size:10px">[ avaliado ]</span></span>
								<?php else: ?>
									<?php echo $row->nome; ?>
								<?php endif; ?>
								</a>
							</td>
							<td>
								<?php echo ( $row->tipo == 1 ) ? 'Mini-curso' : 'Palestra'; ?>
							</td>
						</tr>
						<?php
						$k = 1 - $k;
						$j++;
					}
					?>
					</table>
				</div>
				<?php
				}
				?>
				</div>
				<div class="clr"></div>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ( $this->participacao && ( count( $this->participacao_eventos['Participante'] ) || count( $this->participacao_eventos['Colaborador'] ) ) ) : ?>
		<tr>
			<td colspan="2" class="perfil_td">
				<div id="atuacao_profissional" class="perfil_titulo">
					<div style="float:left">
						Participação em Eventos
					</div>
					<div class="clr"></div>
				</div>
				<div class="perfil_infos" id="campo_atuacao_profissional" style="padding:5px">
				<?php
				if ( count( $this->participacao_eventos ) )
				{
					foreach( $this->participacao_eventos AS $tipo => $evento )
					{
						if ( $tipo == 'Palestrante' ) continue;
						if ( !$this->participacao[ $tipo ] ) continue;
					?>
					<div style="margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #CCC">
						<div style="font-weight: bold;font-size: 16px;margin-bottom:10px">
							<?php echo $tipo; ?>
						</div>
						<table width="100%" class="adminlist">
						<?php
						$k = 0;
						for ($i=0, $n=count( $evento ); $i < $n; $i++)
						{
							$row = &$evento[ $i ];
							if ( !$row->published ) continue;

							?>
							<tr class="<?php echo "row$k"; ?>">
								<td width="8%" align="center" title="Período::<?php echo $row->periodo; ?>" class="hasTip">
									<span style="font-weight: bold;color:#0000FF"><?php echo $row->ano; ?></span>
								</td>
								<td>
									<div>
										<span style="font-weight: bold"><?php echo nl2br( $row->evento ); ?></span>
									</div>
									<?php if ( $row->descricao ) : ?>
									<div style="margin-left:20px;font-size: 11px">
										<?php echo nl2br( $row->descricao ); ?>
									</div>
									<?php endif; ?>
								</td>
								<?php if ( !$this->user->guest && $this->user->id == $this->registro->id_user ) : ?>
								<td width="10%" align="center">
									<?php if ( $row->certificados ) : ?>
										<a href="<?php echo JRoute::_('index2.php?option=com_p22evento&task=certificado&cert=' . $row->tp_reg . '&idevento=' . intval( $row->id_evento ) ); ?>" target="_blank">Certificado</a>
									<?php endif; ?>
								</td>
								<?php endif; ?>
							</tr>
							<?php
							$k = 1 - $k;
							$m++;
						}
						?>
						</table>
					</div>
					<?php
					}
				}
				?>
				</div>
				<div class="clr"></div>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ( count( $this->palestras ) ) : ?>
		<tr>
			<td colspan="2" class="perfil_td">
				<div id="atuacao_profissional" class="perfil_titulo">
					<div style="float:left">
						Palestras / Mini-cursos em Eventos
					</div>
					<div class="clr"></div>
				</div>
				<div class="perfil_infos" id="campo_atuacao_profissional" style="padding:5px">
					<table width="100%" class="adminlist">
					<?php
					$k = 0;
					for ($i=0, $n=count( $this->palestras ); $i < $n; $i++)
					{
						$row = &$this->palestras[ $i ];
						?>
						<tr class="<?php echo "row$k"; ?>">
							<td width="8%" align="center" title="Período::<?php echo $row->periodo; ?>" class="hasTip">
								<span style="font-weight: bold;color:green"><?php echo $row->ano; ?></span>
							</td>
							<td>
								<div>
									<span style="font-weight: bold"><?php echo nl2br( $row->evento ); ?></span>
								</div>
								<?php if ( $row->descricao ) : ?>
								<div style="margin-left:20px;font-size: 11px">
									<?php echo nl2br( $row->descricao ); ?>
								</div>
								<?php endif; ?>
							</td>
							<td width="50%">
								<a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_( 'index2.php?option=com_p22evento&task=avaliacao&idevento='. intval( $row->id_evento ) .'&palestraid='. intval( $row->id ) .'&direct=1&Itemid=' . JRequest::getInt('Itemid') ); ?>">
									<?php echo $row->nome; ?>
								</a>
							</td>
							<?php if ( !$this->user->guest && $this->user->id == $this->registro->id_user ) : ?>
							<td width="10%" align="center">
								<?php if ( $row->certificados ) : ?>
									<a href="<?php echo JRoute::_('index2.php?option=com_p22evento&task=certificado&cert=2&palestraid='. intval( $row->id ) .'&idevento=' . intval( $row->id_evento ) ); ?>" target="_blank">Certificado</a>
								<?php endif; ?>
							</td>
							<?php endif; ?>
						</tr>
						<?php
						$k = 1 - $k;
						$m++;
					}
					?>
					</table>
				</div>
				<div class="clr"></div>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ( ( !$this->user->guest && $this->user->id == $this->registro->id_user ) && count( $this->eventos ) ) : ?>
		<tr>
			<td colspan="2" class="perfil_td">
				<div id="atuacao_profissional" class="perfil_titulo">
					<div style="float:left">
						Eventos Disponíveis
					</div>
					<div class="clr"></div>
				</div>
				<div class="perfil_infos" id="campo_atuacao_profissional" style="padding:5px">
					<table width="100%" class="adminlist">
					<?php
					$k = 0;
					for ($i=0, $n=count( $this->eventos ); $i < $n; $i++)
					{
						$row = &$this->eventos[ $i ];
						?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<div>
									<span style="font-weight: bold"><?php echo nl2br( $row->evento ); ?></span> <a href="<?php echo JRoute::_('index.php?option=com_p22evento&task=selevento&idevento='. $row->id .'&Itemid=' . JRequest::getInt('Itemid')); ?>">[ detalhes]</a>
								</div>
								<?php if ( $row->descricao ) : ?>
								<div style="margin-left:20px;font-size: 11px">
									<?php echo nl2br( $row->descricao ); ?>
								</div>
								<?php endif; ?>
							</td>
							<td width="30%" align="center">
								<span style="font-weight: bold;color:green"><?php echo $row->periodo; ?></span>
							</td>
							<td width="25%" align="center">
								<?php if ( count( $this->inscritoEventos ) ) : ?>
									<?php if ( ( $row->inscricoes && !in_array( 0 , $this->inscritoEventos[ $row->id ] ) && !in_array( 1 , $this->inscritoEventos[ $row->id ] ) && !in_array( 2 , $this->inscritoEventos[ $row->id ] ) ) || ( $row->colaboradores && !in_array( 1 , $this->inscritoEventos[ $row->id ] ) ) || ( $row->palestras || $row->minicursos ) ) : ?>
										<div style="font-weight:bold;margin-bottom:5px" align="left">
											Cadastrar-se:
										</div>
										<?php if ( $row->inscricoes && !in_array( 0 , $this->inscritoEventos[ $row->id ] ) && !in_array( 1 , $this->inscritoEventos[ $row->id ] ) ) : ?>
										<div id="button_0">
											<button style="width:165px" class="button" onclick="registerUser(0 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Participante</button>
											<br /><br />
										</div>
										<?php endif; ?>
										<?php if ( $row->colaboradores && !in_array( 1 , $this->inscritoEventos[ $row->id ] ) ) : ?>
										<div id="button_1">
											<button style="width:165px" class="button" onclick="registerUser(1 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Colaborador</button>
											<br /><br />
										</div>
										<?php endif; ?>
										<?php if ( ( $row->palestras || $row->minicursos ) && !in_array( 2 , $this->inscritoEventos[ $row->id ] ) ) : ?>
										<div id="button_2">
											<button style="width:165px" class="button" onclick="registerUser(2 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Palestrante</button>
										</div>
										<?php endif; ?>
										<a id="link_2" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_('index2.php?option=com_p22evento&task=mykeynotes&idevento='. $row->id .'&Itemid=' . JRequest::getInt('Itemid') ); ?>"style="width:165px;display:<?php echo ( !in_array( 2 , $this->inscritoEventos[ $row->id ] ) ) ? 'none': ''; ?>">
											Palestras / Minicursos
										</a>
									<?php endif; ?>
								<?php else: ?>
									<div style="font-weight:bold;margin-bottom:5px" align="left">
										Cadastrar-se:
									</div>
									<div id="button_0">
										<button style="width:165px" class="button" onclick="registerUser(0 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Participante</button>
										<br /><br />
									</div>
									<div id="button_1">
										<button style="width:165px" id="button_1" class="button" onclick="registerUser(1 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Colaborador</button>
										<br /><br />
									</div>
									<div id="button_2">
										<button style="width:165px" id="button_2" class="button" onclick="registerUser(2 , <?php echo $row->id; ?> , <?php echo $this->registro->id; ?>)">Como Palestrante</button>
									</div>
									<a id="link_2" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 480}}" href="<?php echo JRoute::_('index2.php?option=com_p22evento&task=mykeynotes&idevento='. $row->id .'&Itemid=' . JRequest::getInt('Itemid') ); ?>"style="width:165px;display:<?php echo ( !in_array( 2 , $this->inscritoEventos[ $row->id ] ) ) ? 'none': ''; ?>">
										Palestras / Minicursos
									</a>
								<?php endif; ?>
							</td>
						</tr>
						<?php
						$k = 1 - $k;
						$m++;
					}
					?>
					</table>
				</div>
				<div class="clr"></div>
			</td>
		</tr>
		<?php endif; ?>
	</table>
	<?php if( !JRequest::getVar('tmpl') ) : ?>
	<form action="index.php" method="post" name="adminForm">
		<input type="hidden" name="option" value="com_p22articumprofissional" />
		<input type="hidden" name="task" value="" />
	</form>
	<?php else: ?>
	<div class="profile_buttons" style="text-align:center;width:913px;margin:0px auto">
		<button type="button" onclick="javascript:print()">Imprimir</button>
	</div>
	<?php endif; ?>
</div>
<form>
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
</form>