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
<script type="text/javascript">
function showResumo( id )
{
	var desc_box_id = document.getElementById('desc_box_id').value;
	document.getElementById('dados_box_loader').style.display	= ( desc_box_id == id ) ? 'none' : '';
	document.getElementById('dados_box_desc').style.display		= ( desc_box_id == id ) ? '' : 'none';

	if  ( desc_box_id == id ) return;

	var desc_pop = document.getElementById('desc_pop');

	var token = document.getElementById('token').value;

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&' + token + '=1';
		dados	+= '&acao=palestra_resumo';
		dados	+= '&palestraid=' + id;

	ajaxCall( 'post' , dados , 's' , 'dados_box_desc' , 'palestra_resumo' , '' , '' , '' );

}
function showTrilha(element,id)
{
	var display = ( element.checked == true ) ? '' : 'none';
	var elems	= document.getElementsByTagName('div');
	
	for( var i = 0 ; i < elems.length; i++ )
	{
		var classN = elems[i];
		if( classN.getAttribute('type') == 'trilha_' + id )
		{
			elems[i].style.display = display;
		}
	}
}
window.addEvent('domready', function()	{
	$("desc_pop").setStyles({left: (window.getScrollLeft() + window.getWidth()/2)+'px'});
});
</script>
<style type="text/css">
div.field_palestra {
	opacity: 0.7;
}

div.field_palestra:hover {
	opacity: 1.0;
}

#desc_pop{
	position:fixed;
	padding:10px;
	z-index:1000000;
}
</style>
<div id="desc_pop" style="display:none;background-color: #EEF9AE;border:5px solid #E0F279;float:right;width:400px;position:fixed;right:10px;top:10px;z-index:10">
	<div id="dados_box_desc" style="display:none"></div>
	<div id="dados_box_loader" style="height:280px" align="center">
		<img src="./components/com_p22evento/assets/images/loader_green.gif" alt="" style="margin-top:100px" />
		<br />
		Aguarde o carregamento...
	</div>
</div>
<div style="padding:10px;font-size:12px" id="top">
	<div style="padding:10px">
		Salas, dias e horários estão sujeitos a alterações, de acordo com os critérios da organização do <strong><em><?php echo $this->eventName; ?></em></strong>
		<br /><br />
		Vejam também a versão para <a href="#"><strong>impressão.</strong></a>
	</div>
	<hr />
	<div>
		<div style="float:left;margin-top:4px">
			<strong>ÍCONES:</strong>
		</div>
		<div style="float:left;margin-left:25px">
			<img src="./components/com_p22evento/assets/images/palestras.png" alt="" />
		</div>
		<div style="float:left;font-weight:bold;margin-left:5px;margin-top:4px">
			<a href="#div_tipo_<?php echo $this->tipo[0]; ?>" style="text-decoration: none">
				Palestras
			</a>
		</div>
		<div style="float:left;margin-left:25px">
			<img src="./components/com_p22evento/assets/images/minicursos.gif" alt="" />
		</div>
		<div style="float:left;font-weight:bold;margin-left:5px;margin-top:4px">
			<a href="#div_tipo_<?php echo $this->tipo[1]; ?>" style="text-decoration: none">
				Mini-cursos
			</a>
		</div>
		<div style="clear:both"></div>
	</div>

	<hr />
	<div>
		<div style="padding:10px">
			<strong>Filtro por Trilhas:</strong> Desmarque/Marque as trilhas para escondê-las/exibí-las.
		</div>
		<div style="padding:10px">
			<?php
			for( $a = 0 ; $a < count( $this->trilhas ) ; $a++ )
			{
				$row = &$this->trilhas[ $a ];
			?>
			<div style="min-width:200px;max-width:400px;background-color:<?php echo $row->cor; ?>;float:left;padding:5px;margin-right:3px">
				<div style="float:left">
					<input type="checkbox" onchange="showTrilha(this,<?php echo $row->id; ?>)" checked="true" name="trilha_<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" id="trilha_<?php echo $row->id; ?>" />
				</div>
				<div style="float:left;margin-left:3px;margin-top:2px">
					<label for="trilha_<?php echo $row->id; ?>"><strong><?php echo $row->nome; ?></strong></label>
				</div>
			</div>
			<?php } ?>
			<div style="clear:both"></div>
		</div>
	</div>
	<hr />
	<div>
		<ul>
			<?php
			for( $b = 0 ; $b < count( $this->diasEvento ) ; $b++ ) :
				$dia = &$this->diasEvento[ $b ];
			?>
			<li><a href="#grid_<?php echo $dia->id; ?>" style="text-decoration: none"><?php echo $dia->nome; ?></a></li>
			<?php endfor; ?>
		</ul>
		<?php
		if ( count( $this->palestras ) )
		{
			foreach( $this->palestras AS $tipo => $palestras )
			{
				$img = ( $tipo == 1 ) ? 'minicursos.gif' : 'palestras.png' ;
			?>
			<div id="div_tipo_<?php echo $this->tipo[ $tipo ]; ?>">
				<div>
					<div style="float:left;margin-top:14px">
						<img src="./components/com_p22evento/assets/images/<?php echo $img; ?>" alt="" />
					</div>
					<div style="float:left;margin-left:10px">
						<h2>
							<?php echo $this->tipo[ $tipo ]; ?>
						</h2>
					</div>
					<div style="clear:both"></div>
				</div>

				<?php
				if( count( $palestras ) )
				{
					foreach( $palestras AS $dia => $array_dias )
					{
					?>
					<div id="grid_<?php echo $dia; ?>">
						<div>
							<div style="float:left">
								<h3><?php echo implode( '/' , array_reverse( explode( '-' , $dia ) ) ); ?></h3>
							</div>
							<div style="float:left;margin-top:18px;margin-left:5px">
								<a href="#top" style="text-decoration: none">Voltar ao Topo</a>
							</div>
							<div style="clear:both"></div>
						</div>
						<table width="100%" cellspacing="0" cellpadding="0" style="background-color:#DDD;border:1px solid #EAEAEA" border="1">
						<?php
						if ( count( $array_dias ) )
						{
							$column = 0;
							foreach( $array_dias AS $hora => $array_salas )
							{
							?>
							<tr>
							<?php
							if ( $column == 0 )
							{
								?>
								</tr>
								<tr>
									<td width="8%">&nbsp;</td>
									<?php
									if ( count( $this->salas ) )
									{
										foreach( $this->salas AS $sala )
										{
										?>
										<td style="font-weight: bold;font-size: 12px;padding:5px"><?php echo $sala; ?></td>
										<?php
										}
									}
									?>
								</tr>
								<tr>
								<?php
							}
							?>
								<td align="center" style="font-weight: bold">
									<?php echo JString::substr( $hora , 0 , 2 ); ?><?php echo JString::substr( $hora , 2 , 3 ); ?>
									/
									<?php echo JString::substr( $hora , 0 , 2 ) + 1; ?><?php echo JString::substr( $hora , 2 , 3 ); ?>
								</td>
								<?php
								if ( count( $this->salas ) )
								{
									foreach( $this->salas AS $id_sala => $sala )
									{
									?>
									<td style="background-color:#f7f7f7">
										<?php
										if ( $array_salas[ $id_sala ] )
										{
											$v = &$array_salas[ $id_sala ];
										?>
										<div type="trilha_<?php echo $v->id_trilha; ?>" style="background-color: <?php echo $v->cor; ?>;padding-bottom:4px;padding-top:4px;cursor:<?php echo ( $this->showDesc ) ? 'pointer' : 'default'; ?>" class="field_palestra" onclick="<?php echo ( $this->showDesc ) ? "showResumo('{$v->id_grade}');this.blur();showThem('desc_pop','{$v->id_grade}');return false;" : ''; ?>">
											<div align="center" style="font-weight: bold;"><?php echo $v->trilha; ?></div>
											<div align="center">Nível: <em><?php echo $this->nivel[ $v->nivel ]; ?></em></div>
											<div align="center" style="margin-top:8px;font-size: 14px;font-weight: bold"><?php echo $v->nome; ?></div>
											<div style="font-weight: bold;font-size: 12px;margin-top:10px;margin-left:5px"><?php echo $v->palestrante; ?></div>
										</div>
										<?php
										}
										?>
									</td>
									<?php
									}
								}
								?>
							</tr>
							<?php
							$column++;
							}
						}
						?>
						</table>
					</div>
					<?php
					}
				}
				?>
			</div>
			<?php
			}
		}
		?>
	</div>
</div>
<form>
	<input type="hidden" name="token" id="token" value="<?php echo JUtility::getToken(); ?>" />
	<input type="hidden" name="desc_box_id" id="desc_box_id" value="" />
</form>