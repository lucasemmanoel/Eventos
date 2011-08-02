/*----------------------------------------------------------------------------*/
function loadUserData( value )
{
	if( !value )
	{
		enableFields( true );
		return;
	}
	
	var token = document.getElementById('token').value;
		document.getElementById('users_loader').style.visibility = 'visible';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&acao=carrega_dados_usuarios';
		dados	+= '&'+token+'=1';
		dados	+= '&idevento='+ document.getElementById('idevento').value;
		dados	+= '&user='+value;

	ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
}
/*----------------------------------------------------------------------------*/
function carregaCidade( uf , action , campo , id_cidade )
{
	if( !uf ) return;

	document.getElementById('loader_cidades').style.visibility = 'visible';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&acao=' + action;
		dados	+= '&uf='+uf;
		dados	+= '&cidade='+ id_cidade;
	
	ajaxCall( 'post' , dados , 's' , campo , action , '' , '' , '' );
}
/*----------------------------------------------------------------------------*/
function addProfissao()
{
	var token		= document.getElementById('token').value;
	var idevento	= document.getElementById('idevento').value;
	var campo		= 'p22SelectProfissoes';
	var loader		= 'loader_profissao';

	var nome = prompt('Adicionar Profissão:');
	
	if( nome != null )
	{
		if( nome.length > 0 )
		{
			document.getElementById( loader ).style.visibility = 'visible';
			var dados	= 'option=com_p22evento&format=raw&task=ajax';
				dados	+= '&tp=profissao';
				dados	+= '&token=' + token;
				dados	+= '&'+token+'=1';
				dados	+= '&acao=addProfissao';
				dados	+= '&idevento=' + idevento;
				dados	+= '&campo=' + campo;
				dados	+= '&loader=' + loader;
				dados	+= '&nome=' + nome;
			
			ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
		}
	}
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
//valida o CPF digitado
function ValidarCPF(Objcpf)
{
	var cpf = Objcpf.value;
	var exp = /\.|\-/g
	cpf = cpf.toString().replace( exp, "" );
	var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
	var soma1=0, soma2=0;
	var vlr =11;

	for(i=0;i<9;i++){
		soma1+=eval(cpf.charAt(i)*(vlr-1));
		soma2+=eval(cpf.charAt(i)*vlr);
		vlr--;
	}
	soma1 = (((soma1*10)%11)==10 ? 0:((soma1*10)%11));
	soma2=(((soma2+(2*soma1))*10)%11);

	var digitoGerado=(soma1*10)+soma2;

	if(digitoGerado!=digitoDigitado)
	{
		alert('CPF Invalido!');
		return false;
	}
	return true;
}

/*----------------------------------------------------------------------------*/
