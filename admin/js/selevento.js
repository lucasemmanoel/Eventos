function publisEventAction( id_evento , tp , element )
{
	var token	= document.getElementById('token').value;
	var checked	= element.checked;
	document.getElementById('loader_' + tp ).style.visibility = 'visible';

	var dados	= 'option=com_p22evento&format=raw&task=ajax';
		dados	+= '&'+ token +'=1';
		dados	+= '&idevento='+ id_evento;
		dados	+= '&acao=publish_event_actions';
		dados	+= '&funcao=' + tp;
		dados	+= '&value='+ checked;

	ajaxCall( 'post' , dados , '' , '', '' , tp , 's' , 'registra_grade_globals' );
}