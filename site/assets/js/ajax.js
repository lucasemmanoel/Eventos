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
			ajax.open('POST','index2.php', true);
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
		case 'getCidades':
			document.getElementById('loader_cidades').style.visibility = 'hidden';
			window.setTimeout( 'document.getElementById("id_cidade").focus()' , 500 );
		break;
		case 'verifyuseremail':

			vr = param.split( '+++' );

			if ( parseInt( vr[0] ) > 0 )
			{
				alert( vr[1] );
				globalForm.email.focus();
				enableDisableFields( false , 'cpf' , false , false , false );
				document.getElementById('email_link').style.display = '';
			}
			else
			{
				enableDisableFields( true , 'cpf' , true , false , false );
				globalForm.cpf.focus();
			}

			document.getElementById('loader_email').style.visibility ='hidden';

		break;
		case 'verifyusercpf':

			vr = param.split( '+++' );
			
			if ( parseInt( vr[0] ) > 0 )
			{
				alert( vr[2] );
				globalForm.cpf.focus();
				enableDisableFields( false , vr[1] , true , false , false );
			}
			else
			{
				var profLink = ( vr[1] == 'uf,id_cidade,id_profissao' ) ? true : false;
				enableDisableFields( true , vr[1] , false , true , profLink );
			}
			
			document.getElementById('loader_cpf').style.visibility ='hidden';
		break;
		case 'verifyuserusername':

			vr = param.split( '+++' );

			if ( parseInt( vr[0] ) > 0 )
			{
				alert( vr[1] );
				document.getElementById('username').focus();
				enableDisableFields( false , 'password,password2,uf,id_cidade,id_profissao' , false , true , false );
			}
			else
			{
				enableDisableFields( true , 'password,password2,uf,id_cidade,id_profissao' , false , false , true );
				document.getElementById('password').focus();
			}

			document.getElementById('loader_username').style.visibility ='hidden';
		break;
		case 'subscribe_user':
			document.getElementById('logged_user_box_form').style.display = 'none';
			document.getElementById('logged_user_box_subscribe').style.display = 'none';
			document.getElementById('logged_user_box_registered').style.display = 'none';
			document.getElementById('logged_user_box_msg').style.display = '';
		break;
		case 'subscribe_palestrante':
			document.getElementById('logged_user_box_form').style.display = 'none';
			document.getElementById('logged_user_box_subscribe').style.display = 'none';
			document.getElementById('logged_user_box_palestra').style.display = '';
		break;
		case 'register_user_profile':

			vr = param.split( '+++' );

			switch ( vr[1] )
			{
				case '2':
					if ( vr[0] == 0 )
					{
						buttonText( vr[1] , 'Como Palestrante' );
					}
					else
					{
						document.getElementById('button_' + vr[1] ).style.display = 'none';
						document.getElementById('link_' + vr[1] ).style.display = '';
					}
					break;
				case '1':
					if ( vr[0] == 0 )
					{
						buttonText( vr[1] , 'Como Colaborador' );
					}
					else
					{
						document.getElementById('button_0' ).style.display = 'none';
						document.getElementById('button_' + vr[1] ).style.display = 'none';
					}
					break;
				default:
					if ( vr[0] == 0 )
					{
						buttonText( vr[1] , 'Como Participante' );
					}
					else
					{
						document.getElementById('button_' + vr[1] ).style.display = 'none';
					}
					break;
			}
			alert( vr[2] );
		break;
		case 'check_certificado':
			vr = param.split( '+++' );

			document.getElementById('result_field').style.display	= ( vr[0] == '0' ) ? 'none' : '';
			document.getElementById('result_field2').style.display	= ( vr[0] == '0' ) ? '' : 'none';
			document.getElementById('td_tipo').innerHTML			= vr[1];
			document.getElementById('td_nome').innerHTML			= vr[2];
			document.getElementById('td_cpf').innerHTML				= vr[3];
			document.getElementById('tr_palestra').style.display	= ( vr[4].length > 0 ) ? '' : 'none';
			document.getElementById('td_palestra').innerHTML		= vr[4];
			document.getElementById('td_tp_palestra').innerHTML		= vr[5];
			document.getElementById('td_evento').innerHTML			= vr[6];
			document.getElementById('td_periodo').innerHTML			= vr[7];
			document.getElementById('cert_num').readOnly			= false;
			buttonText( 'button_send' , 'Enviar' , false );

		break;
		case 'palestra_resumo':
			document.getElementById('dados_box_loader').style.display	= 'none';
			document.getElementById('dados_box_desc').style.display		= '';
		break;
		default:
			alert('callSubFuncaoAjax não possui parâmetros.');
		break;
	}
}
/*----------------------------------------------------------------------------*/