/*----------------------------------------------------------------------------*/
function showHideCampo( tp )
{
	var fields = new Array( 'id' , 'nome' , 'cpf' );

	for( var c = 0 ; c < fields.length ; c++ )
	{
		document.getElementById( 'tr_' + fields[ c ] ).style.display = ( fields[ c ] == tp ) ? '' : 'none';
	}
	document.getElementById( tp ).focus();
}
/*----------------------------------------------------------------------------*/
function searchInscritos( tp , funcao )
{
	var token		= document.getElementById('token').value;
	var funcaoValue;

	if( funcao.length > 0 )
	{
		funcaoValue	= document.getElementById( funcao ).value;

		if ( funcao == 'cpf' && funcaoValue.length < 14 ) return;


		document.getElementById( 'loader_' + funcao ).style.visibility = 'visible';
	}
	else
	{
		funcao	= 'global';
		document.getElementById( 'global_datauser_update' ).style.visibility = 'visible';
		var button = document.getElementById('global_datauser_button');
			button.innerHTML	= 'Aguarde...';
			button.disabled		= true;
	}

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&acao=busca_inscritos';
		dados	+= '&token=' + token;
		dados	+= '&'+ token +'=1';
		dados	+= '&tp=' + tp;		
		dados	+= '&idevento=' + document.getElementById('idevento').value;
		dados	+= '&funcao=' + funcao;
		dados	+= '&value=' + funcaoValue;
	
	ajaxCall( 'post' , dados , 's' , 'td_profs_result' , 'busca_inscritos' , funcao , '' , '' );
}
/*----------------------------------------------------------------------------*/
function confirmUser( id , published )
{
	var token		= document.getElementById('token').value;

	document.getElementById( 'user_confirmed_' + id ).style.display		= 'none';
	document.getElementById( 'loader_confirmed_' + id ).style.display	= '';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&acao=confirmar_inscrito';
		dados	+= '&token=' + token;
		dados	+= '&'+ token +'=1';
		dados	+= '&idevento=' + document.getElementById('idevento').value;
		dados	+= '&id=' + id;
		dados	+= '&published=' + published;

	ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
}
/*----------------------------------------------------------------------------*/
function FormataCpf(campo, teclapres)
{
	var tecla = teclapres.keyCode;
	var vr = new String(campo.value);
	vr = vr.replace(".", "");
	vr = vr.replace("/", "");
	vr = vr.replace("-", "");
	tam = vr.length + 1;
	if (tecla != 14)
	{
		if (tam == 4)
			campo.value = vr.substr(0, 3) + '.';
		if (tam == 7)
			campo.value = vr.substr(0, 3) + '.' + vr.substr(3, 6) + '.';
		if (tam == 11)
			campo.value = vr.substr(0, 3) + '.' + vr.substr(3, 3) + '.' + vr.substr(7, 3) + '-' + vr.substr(11, 2);
	}
}
/*----------------------------------------------------------------------------*/