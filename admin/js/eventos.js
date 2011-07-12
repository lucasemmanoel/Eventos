/*----------------------------------------------------------------------------*/
function addLocal()
{
	var token	= document.getElementById('token').value;
	var campo	= 'p22SelectLocais';
	var loader	= 'p22loader';

	var nome = prompt('Adicionar Local:');

	if( nome != null )
	{
		if( nome.length > 0 )
		{
			document.getElementById( loader ).style.visibility = 'visible';
			var dados	= 'option=com_p22evento&format=raw&task=ajax';
				dados	+= '&token=' + token;
				dados	+= '&'+token+'=1';
				dados	+= '&tp=locais';
				dados	+= '&acao=addLocal';
				dados	+= '&campo=' + campo;
				dados	+= '&loader=' + loader;
				dados	+= '&nome=' + nome;

			ajaxCall( 'post' , dados , '' , '' , '' , '' , 's' , '' );
		}
	}
}
/*----------------------------------------------------------------------------*/
function FiltraCampo(id_campo)
{
    var campo   = document.getElementById(id_campo);
    var s       = "";
        vr      = campo.value;
        tam     = vr.length;

        for (i = 0; i < tam ; i++)
        {
            if (vr.substring(i,i + 1) != "/" && vr.substring(i,i + 1) != "-" && vr.substring(i,i + 1) != "."  && vr.substring(i,i + 1) != "," )
            {
                s = s + vr.substring(i,i + 1);
            }
        }
        campo.value = s;

    return s;
}
/*----------------------------------------------------------------------------*/
function FormataData(id_campo)
{
    var campo       = document.getElementById(id_campo);
        campo.value = FiltraCampo(id_campo);
		vr          = campo.value;
		tam         = vr.length;
    if (tam == 0)
        return;

    if ( tam > 2 && tam < 5 )
        campo.value = vr.substr( 0, tam - 2  ) + '/' + vr.substr( tam - 2, tam );

    if ( tam >= 5 && tam <= 10 )
        campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 );
}
/*----------------------------------------------------------------------------*/
function checkDuracao()
{
	var horaInicio		= document.getElementById('horaInicio');
	var duracao			= document.getElementById('duracao');
	var campoHoraFim	= document.getElementById('campoHoraFim');
	var horaFimHidden	= document.getElementById('horaFim');
	
	if(horaInicio.value.length == 0)
	{
		alert('Informe a Hora Inicial da Palestra.');
		duracao.value = '';
		horaInicio.focus();
		return false;
	}
	var sumHora;
	var minutos;

	var vr	= horaInicio.value.split(':');
	var vr2 = duracao.value.split('.');

	sumHora = (vr[0]  * 1) + (vr2[0] * 1);
	if( vr2[1] == '5' )
		minutos = (30 * 1) + (vr[1] * 1);
	else
		minutos = vr[1];

	var horaFim = sumHora + ':' + minutos;

	campoHoraFim.innerHTML = horaFim;
	horaFimHidden.value = horaFim;

	return true;
}

function chkDadosHora(tp)
{
	var horaInicio		= document.getElementById('horaInicio');
	var campoHoraFim	= document.getElementById('campoHoraFim');
	var horaFimHidden	= document.getElementById('horaFim');
	var duracao			= document.getElementById('duracao');

	if(tp != 2)
	{
		horaInicio.value = '';
	}
	campoHoraFim.innerHTML = '';
	horaFimHidden.value = '';
	duracao.value = '';
}

function verificaDadosPalestrante(idEvento)
{
	var button		= document.getElementById('buttonProcessa');
	var loader		= document.getElementById('loaderField');
	var data		= document.getElementById('data');
	var horaInicio	= document.getElementById('horaInicio');
	var horaFim		= document.getElementById('horaFim');
	var select		= document.getElementById('palestrante');
	var selectOption= document.getElementById('blankPalestrante');

	if( data.value.length == 0)
	{
		alert('Informe a data da palestra.');
		data.focus();
		return false;
	}
	if( horaInicio.value.length == 0)
	{
		alert('Informe a data da palestra.');
		horaInicio.focus();
		return false;
	}
	if( document.getElementById('duracao').value.length == 0)
	{
		alert('Informe a duração da palestra.');
		document.getElementById('duracao').focus();
		return false;
	}

	button.disabled			= true;
	loader.style.display	= 'block';
	select.disabled			= false;
	selectOption.innerHTML	= 'Carregando...';

	var dados;
	dados = 'option=com_evento&format=raw&controller=ajax&task=verificaPalestra&acao=palestrante&funcao=&idevento=' + idEvento + '&data=' + data.value + '&horaInicio=' + horaInicio.value + '&horaFim=' + horaFim.value;

	ajaxCall( 'post' , dados , 's' , 'palestrante' , 'verificaSala' , idEvento , '' , '' );

}

function verificaDadosSala(idEvento)
{
	var data			= document.getElementById('data');
	var horaInicio		= document.getElementById('horaInicio');
	var horaFim			= document.getElementById('horaFim');
	var select			= document.getElementById('sala');
	var selectOption	= document.getElementById('blankSala');

	select.disabled	= false;
	selectOption.innerHTML	= 'Carregando...';

	var dados;
	dados = 'option=com_evento&format=raw&controller=ajax&task=verificaPalestra&acao=sala&funcao=&idevento=' + idEvento + '&data=' + data.value + '&horaInicio=' + horaInicio.value + '&horaFim=' + horaFim.value;

	ajaxCall( 'post' , dados , 's' , 'sala' , '' , '' , '' , '' );
}