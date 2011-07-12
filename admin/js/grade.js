function habilitaDesabilitaDia( value )
{
	document.getElementById('dia').disabled = ( !value ) ? true : false;
	document.getElementById('dia').value	= '';

	chkInfosGrade( true , 'dias' , '', '' , '');
}

function registraInfo( tp , value )
{
	var token		= document.getElementById('token').value;
	var idevento	= document.getElementById('idevento').value;
	
	document.getElementById('loader_' + tp ).style.visibility = 'visible';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&token=' + token;
		dados	+= '&'+ token +'=1';
		dados	+= '&tp=grade';
		dados	+= '&idevento='+ idevento;
		dados	+= '&acao=registra_grade_globals';
		dados	+= '&funcao=' + tp;
		dados	+= '&value='+ value;

	ajaxCall( 'post' , dados , '' , '', '' , tp , 's' , 'registra_grade_globals' );
}

function chkInfosGrade( edit , tp , valeu_tppalestra , value_palestra , value_sala , value_hora )
{
	var token		= document.getElementById('token').value;
	var idevento	= document.getElementById('idevento').value;

	var tipo		= document.getElementById('tipo');
		tipo		= ( tipo ) ? tipo.value : '';
	var dia			= document.getElementById('dia');
		dia			= ( dia ) ? dia.value : '';
	var palestra	= document.getElementById('id_palestra');
		palestra	= ( palestra ) ? palestra.value : '';
	var sala		= document.getElementById('id_sala');
		sala		= ( sala ) ? sala.value : '';
	var hora		= document.getElementById('hora');
		hora		= ( hora ) ? hora.value : '';

	document.getElementById('loader_' + tp ).style.visibility = 'visible';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&token=' + token;
		dados	+= '&'+ token +'=1';
		dados	+= '&tp=grade';
		dados	+= '&acao=grade_infos';
		dados	+= '&funcao=' + tp;
		dados	+= '&edit=' + edit;
		dados	+= '&idevento='+ idevento;
		dados	+= '&tipo_palestra='+ tipo;
		dados	+= '&valeu_tppalestra='+ valeu_tppalestra;
		dados	+= '&dia='+ dia;
		dados	+= '&palestra='+ palestra;
		dados	+= '&value_palestra='+ value_palestra;
		dados	+= '&sala='+ sala;
		dados	+= '&value_sala='+ value_sala;
		dados	+= '&hora='+ hora;
		dados	+= '&value_hora='+ value_hora;

	ajaxCall( 'post' , dados , '' , '', '' , '' , 's' , '' );
}