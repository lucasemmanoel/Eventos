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

function registra_avaliadores()
{
	document.getElementById('add_button').disabled = true;
	document.getElementById('remove_button').disabled = true;
	allSelected(document.adminForm['colaboradores'] , true);

	var token		= document.getElementById('token').value;
	var idevento	= document.getElementById('idevento').value;

	var ids = allSelected(document.adminForm['avaliadores'] , true);

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&token=' + token;
		dados	+= '&'+ token +'=1';
		dados	+= '&tp=avaliadores';
		dados	+= '&acao=registra_avaliadores';
		dados	+= '&idevento='+ idevento;
		dados	+= '&ids='+ ids;

	ajaxCall( 'post' , dados , '' , '', '' , '' , 's' , 'registra_avaliadores' );
}

function moveOptions(from,to)
{
	// Move them over
	for (var i=0; i<from.options.length; i++) {
		var o = from.options[i];
		if (o.selected) {
			to.options[to.options.length] = new Option( o.text, o.value, false, false);
		}
	}

	// Delete them from original
	for (var i=(from.options.length-1); i>=0; i--) {
		var o = from.options[i];
		if (o.selected) {
			from.options[i] = null;
		}
	}
	from.selectedIndex = -1;
	to.selectedIndex = -1;
	registra_avaliadores();
	return true;
}

//	tem que ter esse comando antes de enviar para o servidor senâo num sobe ninguém....
//	allSelected( document.adminForm['responsaveis'] );
//			submitform( task );
function allSelected( element , act )
{
	var ids = '';
	var count_items = '';
	for (var i=0; i<element.options.length; i++) {
		var o = element.options[i];
		o.disabled = act;
		if( o.value > 0 )
		{
			ids += o.value + ',';
			count_items++;
		}
	}
	document.getElementById('num_avaliadores').value = count_items;
	return ids;
}

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


function expandeCampo( tp )
{
	var campo_avaliadores	= document.getElementById( 'campo_avaliadores' );
	var campo_avaliacoes	= document.getElementById( 'campo_avaliacoes' );
	var link_avaliadores	= document.getElementById( 'expandir_avaliadores' );
	var link_avaliacoes		= document.getElementById( 'expandir_avaliacoes' );
	var abre_avaliadores;
	var abre_avaliacoes;
	
	switch( tp )
	{
		case 'campo_avaliadores':
			abre_avaliadores					= '';
			abre_avaliacoes					= 'none';
			link_avaliadores.style.visibility	= 'hidden';
			link_avaliacoes.style.visibility= 'visible';
			break;
		case 'campo_avaliacoes':
			abre_avaliadores					= 'none';
			abre_avaliacoes					= '';
			link_avaliadores.style.visibility	= 'visible';
			link_avaliacoes.style.visibility= 'hidden';
			break;
	}
	campo_avaliadores.style.display = abre_avaliadores;
	campo_avaliacoes.style.display = abre_avaliacoes;
}

function atualizaavaliadores()
{
	if( document.getElementById( 'campo_avaliacoes' ).style.display == '' ||
		document.getElementById('chk_atualizar').value == 1 )
	{
		document.getElementById('expandir_avaliacoes').style.color = '#0000ff';
		document.getElementById('chk_atualizar').value = 0;
		document.getElementById('link_atualizar').style.color = '#0000ff';
		document.getElementById('link_atualizar').style.textDecoration = '';
		document.getElementById('frameavaliacoes').src = 'index.php?option=com_p22getecanalise&task=user_permitions&tmpl=component';
		expandeCampo( 'campo_avaliacoes' );
	}
	return true;
}

function abreOpcoesavaliacoes( item )
{
	var num_avaliadores = window.top.document.getElementById('num_avaliadores');

	for( var a = 0 ; a < num_avaliadores.value ; a++ )
	{
		if( a != item )
		{
			document.getElementById( 'campo_permissao_' + a ).style.display = 'none';
		}
	}
	
	var campo = document.getElementById( 'campo_permissao_' + item );
	if( campo.style.display == 'none' )
		campo.style.display = '';
	else
		campo.style.display = 'none';
	
	return true;
}

function habilitaCategorias( idAnalista, idSecao , idLoop )
{
	var num_cats	= document.getElementById( 'count_cat_' + idSecao ).value;
	var action;
	var checked;

	if( document.getElementById( 'secao_' + idAnalista + '_' + idLoop ).checked == true )
	{
		action	= false;
		checked = true;
	}
	else
	{
		action = true;
		checked = false;
	}

	for( var b = 0 ; b < num_cats ; b++ )
	{
		document.getElementById( 'cat_' + idAnalista + '_' + idSecao + '_' + b ).checked	= checked;
		document.getElementById( 'cat_' + idAnalista + '_' + idSecao + '_' + b ).disabled	= action;
	}
	return true;
}

function registraavaliacoes( idAnalista )
{
	var cats		= '';
	var num_sec		= document.getElementById( 'num_secoes' ).value;
	var button		= document.getElementById( 'button_' + idAnalista );
	button.innerHTML	= 'Aguarde...';
	button.disabled		= true;

	for( var b = 0 ; b < num_sec ; b++ )
	{
		if( document.getElementById( 'secao_' + idAnalista + '_' + b ).checked == true )
		{
			var idSec		= document.getElementById( 'secao_' + idAnalista + '_' + b ).value;
			var num_cats	= document.getElementById( 'count_cat_' + idSec ).value;

			for( var c = 0 ; c < num_cats ; c++ )
			{
				if( document.getElementById( 'cat_' + idAnalista + '_' + idSec + '_' + c ).checked == true )
				{
					cats += idSec + '_' + document.getElementById( 'cat_' + idAnalista + '_' + idSec + '_' + c ).value + ',';
				}
			}
		}
	}

	var dados	= 'option=com_p22getecanalise&format=raw&task=ajax&acao=registra_avaliacoes';
		dados	+= '&id_analista='+ idAnalista;
		dados	+= '&categorias=' + cats;

	ajaxCall( 'post' , dados , '' , '', '' , idAnalista , 's' , 'registra_avaliacoes' );
	return true;
}

function filtroTrilhas( tp )
{
	var header		= document.getElementById('trilhas_header');
	var items		= document.getElementById('trilhas_items');
	var palestras	= document.getElementById('div_palestras');
	var abre		= document.getElementById('link_abre_filtros');
	var fecha		= document.getElementById('link_fecha_filtros');
	var arrow		= document.getElementById('div_link_filtro_trilhas');

	palestras.style.width	= ( tp == 1 ) ? '72%' : '96%';
	abre.style.display		= ( tp == 1 ) ? 'none' : '';
	fecha.style.display		= ( tp == 1 ) ? '' : 'none';
	header.style.display	= ( tp == 1 ) ? '' : 'none';
	items.style.display		= ( tp == 1 ) ? '' : 'none';
	arrow.style.marginLeft	= ( tp == 1 ) ? '0px' : '-3px';

	var tpF					= ( tp == 1 ) ? 0 : 1;

	arrow.setAttribute( 'onclick' , 'javascript:filtroTrilhas(' + tpF + ')')
}
function setFiltroTrilha( trilha_id )
{
	document.adminForm.filter_trilha.value = trilha_id;
	document.adminForm.submit();
}