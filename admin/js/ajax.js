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
function ajaxAccept()
{
    var ajax;

    // Mozilla, Safari,...
    if(window.XMLHttpRequest)
    {
		ajax = new XMLHttpRequest();
	} else if (window.ActiveXObject){	// IE
		ajax = new ActiveXObject("Msxml2.XMLHTTP");
		if (!ajax) {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
    }
	else {
        alert("Seu navegador não possui suporte a essa aplicação!");
	}
	return ajax;
}
/*----------------------------------------------------------------------------*/
function ajaxCall( method , dados , insertAlvo , campoAlvo , subFuncao , paramSubFuncao , paramResult , paramFuncao )
{
	/*
	 * METHOD			: post ou get
	 * DADOS			: parametros que serão inseridos pela chamada do XMLHttpRequest
	 * INSERTALVO		: verifica se o resultado da aplicação retornal em algum campo. Parâmetros: s ou n
	 * CAMPO ALVO		: campo onde iremos inserir o resultado da aplicação
	 * SUBFUNÇÃO		: função que será chamada no final da aplicação após a chegada do resultado. Se vazio, não chamar subfunção.
	 * PARAMSUBFUNCAO	: parâmetros da subFuncao.
	 * OBS[0]: subFunção trará um número inteiro que chamará a aplicação que estará dentro de subfuncao(),
	 * conforme a necessidade da função ou aplicação que chama o ajaxCall().
	 * PARAMRESULT		: parâmetro de resultado é usado para definir um comportamento depois de dado o resultado de retorno do XMLHttpRequest
	 * PARAMFUNCAO		: função que será chamada quando um comportamento de resultado no retorno de XMLHttpRequest é definido.
	 */

	if(method == 'post')
	{
		ajax=ajaxAccept();
		if (ajax)
		{
			ajax.open('POST','index3.php', true);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
			ajax.onreadystatechange=function()
			{
			   if(ajax.readyState==4)
			   {				   
				   if(ajax.status==200)
				   {
					   var res = ajax.responseText;
					   if(insertAlvo == 's')
					   {
						   document.getElementById( campoAlvo ).innerHTML=res;
					   }
					   if(paramResult == 's')
					   {
						   var vr = res.split("__");
						   if( vr[0] == 'ok' )
						   {
							   if( vr[1].length > 0 )
								{
									var vr2 = vr[1].split( '-=-' );
									callSubFuncaoAjax( vr2[0] , vr2[1] );
								}
								else
								{
									callSubFuncaoAjax( paramFuncao , paramSubFuncao );
								}
						   }
						   if( vr[0] == 'notok' )
						   {
							   //Exibe a mensagem personalizada de erro.
							   alert( vr[1] );
						   }
					   }
					   if(subFuncao.length != 0)
					   {
							//chama sub-função do ajax.
							callSubFuncaoAjax( subFuncao , paramSubFuncao );
					   }
				   }
			   }
			}
		}
		ajax.setRequestHeader("Content-length", dados.length);
		ajax.send(dados);
	}
}
/*----------------------------------------------------------------------------*/
function callSubFuncaoAjax( act , param )
{
	var vr;
	switch(act)
	{
		case 'addLocal':
		case 'addProfissao':
			vr = param.split( '+++' );
			
			document.getElementById( vr[0] ).innerHTML = vr[1];

			document.getElementById( vr[2] ).style.visibility = 'hidden';

		break;
		case 'loadTrilhas':
			document.getElementById('div_trilhas_select').innerHTML = param;
			loadSelect();
		break;
		case 'registra_avaliadores':

			allSelected(document.adminForm['colaboradores'] , false);
			allSelected(document.adminForm['avaliadores'] , false);
			document.getElementById('add_button').disabled		= false;
			document.getElementById('remove_button').disabled	= false;

			document.getElementById('expandir_avaliacoes').style.visibility = 'hidden';
			document.getElementById('expandir_loadpage').style.display = '';
		break;
		case 'grade_infos':
			vr = param.split( '+++' );

			var select_value;
			for( var a = 0 ; a < vr.length ; a++ )
			{
				if( vr[a] )
				{					
					if ( a == 0 )
					{
						document.getElementById('loader_' + vr[a] ).style.visibility = 'hidden';
					}
					else
					{
						select_value = vr[ a ].split( '++' );

						document.getElementById( select_value[ 0 ] ).innerHTML = select_value[ 1 ];
					}
				}
			}
			
		break;
		case 'registra_grade_globals':
			document.getElementById('loader_' + param ).style.visibility = 'hidden';
		break;
		case 'getCidades':
			document.getElementById('loader_cidades').style.visibility = 'hidden';
			window.setTimeout( 'document.getElementById("id_cidade").focus()' , 500 );
		break;
		case 'getCidadesNewsletters':
			document.getElementById('loader_cidades').style.visibility = 'hidden';
		break;
		case 'busca_inscritos':
			if ( param == 'global' )
			{
				document.getElementById( 'global_datauser_update' ).style.visibility = 'hidden';
				var button = document.getElementById('global_datauser_button');
					button.innerHTML	= 'Atualizar Relação de Inscritos';
					button.disabled		= false;
			}
			else
			{
				document.getElementById( 'loader_' + param ).style.visibility = 'hidden';
			}
		break;
		case 'confirmar_inscrito':
			vr = param.split( '+++' );
			
			document.getElementById( 'user_confirmed_' + vr[0] ).style.display		= '';
			document.getElementById( 'user_publish_' + vr[0] ).style.display	= vr[1];
			document.getElementById( 'user_unpublish_' + vr[0] ).style.display	= vr[2];
			document.getElementById( 'loader_confirmed_' + vr[0] ).style.display	= 'none';
			document.getElementById( 'nome' ).value = '';
			document.getElementById( 'cpf' ).value = '';
			document.getElementById( 'id' ).value = '';

			if( document.getElementById('tipo_buscaID').checked == true ) document.getElementById( 'id' ).focus();
			if( document.getElementById('tipo_buscaNOME').checked == true ) document.getElementById( 'nome' ).focus();
			if( document.getElementById('tipo_buscaCPF').checked == true ) document.getElementById( 'cpf' ).focus();

		break;
		case 'carrega_dados_usuarios':
			vr = param.split( '+++' );
			
			enableFields( false );
			
			var fields	= new Array(
								document.getElementById('id'),
								document.getElementById('cpf'),
								document.getElementById('uf'),
								document.getElementById('id_profissao')
							);
	
			for( var b = 0 ; b < fields.length ; b++ )
			{				
				fields[ b ].value = vr[ b ];
			}

			carregaCidade( document.getElementById('uf').value , 'getCidades' , 'td_cidades' , vr[ 4 ] );

			document.getElementById('users_loader').style.visibility = 'hidden';

		break;
		case 'publish_event_actions':
			document.getElementById('loader_' + param ).style.visibility = 'hidden';
		break;
		default:
			alert('callSubFuncaoAjax não possui parâmetros.');
		break;
	}
}
/*----------------------------------------------------------------------------*/