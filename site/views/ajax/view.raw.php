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

// Recursos Joomla! para trabalhar com views.
jimport( 'joomla.application.component.view' );

class P22eventosViewAjax extends JView
{
	function display( $tpl = null )
	{
		global $mainframe;

		$post		= JRequest::get( 'post' );
		$acao		= JRequest::getVar( 'acao' , '' , 'post', 'string' );
		$idEvento	= JRequest::getVar( 'idevento' , 0 , 'post', 'int' );

		/*====================================================================*/
		if( $acao == 'addProfissao' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			JRequest::setVar( 'idevento' , intval( $post['idevento'] ) );

			/**
			 * Fake para acessar o model. Usuário não logados normalmente não podem acessá-lo.
			 * Mas como o cadastro de profissões passa pelo cadastro de usuário, forçamos a entrada.
			 */

			jimport( 'joomla.application.component.model' );
			$model	= &JModel::getInstance( 'participante' , 'P22eventosModel' , 1 );

			$row				= &$model->getTable( 'profissoes' );
			$db					= &JFactory::getDBO();
			$post['published']	= 1;

			// Bind the form fields to the hello table
			if ( !$row->bind( $post ) )
			{
				echo 'notok__' . $db->getErrorMsg();
				$mainframe->close();
			}

			// Make sure the hello record is valid
			if ( !$row->check() )
			{
				echo 'notok__' . $db->getErrorMsg();
				$mainframe->close();
			}

			// Store the web link table to the database
			if ( !$row->store() )
			{
				echo 'notok__' . $db->getErrorMsg();
				$mainframe->close();
			}

			$combo		= array();
			$combo[]	= JHTML::_( 'select.option',  '', '-Informe o Profissão-' , 'id', 'nome' );
			$profissoes	= $model->getProfissoes();
			$combo		= ( count( $profissoes ) ) ? array_merge( $combo , $profissoes ) : $combo;
			$select		= JHTML::_(
								'select.genericlist', // Chamada o arquivo select, método genericlist
								$combo, // array de dados
								'id_profissao', // name da select gerada
								'class="inputbox required"', // outros atributos html da tag
								'id', // nome do campo de valor do select
								'nome', // nome do campo de texto do select
								$row->id // valor default do select
							);

			echo 'ok__addProfissao-=-'. $post['campo'] . '+++' . $select . '+++' . $post['loader'];
			$mainframe->close();
		}
		/*====================================================================*/
		if( $acao == "getCidades" )
		{
			$uf = (string)$post['uf'];

			$disabled	= ( !$uf ) ? 'disabled' : '';
			$db			= &JFactory::getDBO();

			$query = 'SELECT id , nome '
			. ' FROM #__p22cidades '
			. ' WHERE uf=' . $db->Quote( $uf );
			;
			$db->setQuery( $query );
			$cidades = $db->loadObjectList();

			if ( count( $cidades ) )
			{
				echo $this->_getSelectHtml( $cidades , 'id_cidade' , intval( $post['cidade'] ) , 'class="inputbox required"' , 'id' , 'nome' );
			}
			else
			{
				echo '<select name="id_cidade" class="inputbox required" disabled="true"><option value="">-Selecione-</option></select>';
			}

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'verifyuseremail' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			$query = 'SELECT u.id FROM ' . $db->nameQuote( '#__users' ) . ' AS u WHERE u.email=' . $db->Quote( $post['email'] );
			$db->setQuery( $query );
			$num = $db->loadResult();
			
			echo 'ok__verifyuseremail-=-' . $num . '+++' . JText::_('Já existe um cadastro com este e-mail.');

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'verifyusercpf' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			$cpf	= str_replace( '-' , '' , $post['cpf'] );
			$cpf	= str_replace( '.' , '' , $cpf );

			$query = 'SELECT p.id FROM ' . $db->nameQuote( '#__p22eventos_participantes' ) . ' AS p WHERE p.cpf=' . intval( $cpf );
			$db->setQuery( $query );
			$num = $db->loadResult();

			echo 'ok__verifyusercpf-=-' . $num . '+++' . $post['fields'] . '+++' . JText::_('Já existe um cadastro com este CPF no sistema.');

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'verifyuserusername' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			$query = 'SELECT u.id FROM ' . $db->nameQuote( '#__users' ) . ' AS u WHERE u.username=' . $db->Quote( $post['username'] );
			$db->setQuery( $query );
			$num = $db->loadResult();

			echo 'ok__verifyuserusername-=-' . $num . '+++' . JText::_('Já existe um nome do usuário igual a este cadastrado no sistema');

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'subscribe_user' || $acao == 'subscribe_palestrante' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
			$lib = new P22Eventos();
			
			$id_participante	= intval( $post['user'] );
			$id_evento			= intval( $post['evento'] );
			$now				= $lib->now();
			$tp_reg				= intval( $post['tp_reg'] );

			$ins = "INSERT INTO " . $db->nameQuote( '#__p22eventos_inscritos' ). " VALUES ({$id_participante},{$id_evento},0,'{$now}',{$tp_reg})";
			$db->setQuery($ins);

			if( !$db->query() )
			{
				echo 'notok__Erro ao regisrar inscrição';
//				echo 'notok__Erro ao regisrar inscrição: ' . $db->getErrorMsg();
				$mainframe->close();
			}

			echo 'ok__' . $acao;

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'register_user_profile' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
			$lib = new P22Eventos();
			$lib->setDBO( $db );
			
			$id_participante	= intval( $post['user'] );
			$id_evento			= intval( $post['idevento'] );
			$now				= $lib->now();
			$tp_reg				= intval( $post['tp'] );
			$evento				= $lib->eventName();
			$palestras			= $lib->isPublished( $id_evento , 'palestras' );
			$minucursos			= $lib->isPublished( $id_evento , 'minicursos' );

			switch( $tp_reg )
			{
				case '1':
					$tipo = 'Colaborador';
					break;
				case '2':
					$tipo = 'Palestrante';
					break;
				default:
					$tipo = 'Participante';
					break;
			}

			$ins = "INSERT INTO " . $db->nameQuote( '#__p22eventos_inscritos' ). " VALUES ({$id_participante},{$id_evento},0,'{$now}',{$tp_reg})";
			$db->setQuery($ins);

			if( !$db->query() )
			{
				$msg = 'Erro ao regisrar inscrição de ' . $tipo;
//				$msg = 'Erro ao regisrar inscrição' . $tipo . ': ' . $db->getErrorMsg();
				$result = 0;
			}
			else
			{
				$msg = 'Você está inscrito no evento ' . $evento . ' como ' . $tipo . '. Seja bem-vind@ =)';

				if ( $tp_reg == '2' )
				{
					$msg .= ' Agora você pode cadastrar ';

					if ( $palestras ) $msg .= 'Palestras';
					if ( $palestras && $minucursos ) $msg .= ' e ';
					if ( $minucursos ) $msg .= 'Mini-cursos';
				}

				$result = 1;
			}
			echo 'ok__register_user_profile-=-'. $result .'+++' . $tp_reg . '+++' . $msg;
			
			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'check_certificado' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
			$lib = new P22Eventos();
			$lib->setDBO( $db );

			$idevento = intval( $post['idevento'] );

			$where = ' WHERE c.id=' . intval( $post['cert_num'] );

			$query  = 'SELECT c.nome,c.cpf,c.tp_reg,c.id_evento,'
			. ' e.nome AS evento,e.data_inicio,e.data_fim,'
			. ' ( SELECT p.nome FROM #__p22eventos_palestras AS p WHERE p.id = c.id_palestra ) AS palestra,'
			. ' ( SELECT p.tipo FROM #__p22eventos_palestras AS p WHERE p.id = c.id_palestra ) AS tp_palestra'
			. ' FROM #__p22eventos_certificados_data AS c'
			. ' INNER JOIN #__p22eventos AS e ON e.id = c.id_evento'
			. $where
			;
			$obj			= $lib->getRegistrosCustom( $query , 'loadObject' );
			$obj->cpf		= str_pad( $obj->cpf , 11 , '0' , STR_PAD_LEFT );
			$obj->cpf		= $lib->setMask( $obj->cpf , '999.999.999-99' );
			$tp_palestra	= ( $obj->tp_palestra ) ? 'Mini-curso:' : 'Palestra:';
			$show			= ( $obj->nome ) ? 1 : 0;

			$cpf	= 'x';
			$cpf	.= JString::substr( $obj->cpf , 1 , 1 );
			$cpf	.= 'x';
			$cpf	.= '.x';
			$cpf	.= JString::substr( $obj->cpf , 5 , 4 );
			$cpf	.= 'xx-';
			$cpf	.= JString::substr( $obj->cpf , 12 , 14 );
			
			switch( $obj->tp_reg )
			{
				default:
					$obj->tipo = 'Participante';
					break;
				case 1:
					$obj->tipo = 'Colaborador';
					break;
				case 2:
					$obj->tipo = 'Palestrante';
					break;
			}

			$periodo = $lib->getEventDetailString( $obj->id_evento );

			echo 'ok__check_certificado-=-' . $show . '+++' . $obj->tipo . '+++' . $obj->nome . '+++' . $cpf . '+++' . $obj->palestra . '+++' . $tp_palestra . '+++' . $obj->evento . '+++' . $periodo;
			
			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'palestra_resumo' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db = &JFactory::getDBO();

			require_once( JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_p22evento' . DS . 'classes' . DS . 'eventos.php' );
			$lib = new P22Eventos();
			$lib->setDBO( $db );
			
			$query = 'SELECT g.id AS id_grade,g.dia, g.hora , g.id_sala, s.nome AS sala ,'
			. ' p.nome AS palestra, p.tipo, p.nivel,p.resumo,pp.curriculo,'
			. ' ( SELECT u.name FROM '. $db->nameQuote('#__users') .' AS u WHERE u.id = pp.id_user ) AS palestrante,'
			. ' t.nome AS trilha'
			. ' FROM ' . $db->nameQuote( '#__p22eventos_grades' ) . ' AS g'
			. ' INNER JOIN ' . $db->nameQuote('#__p22eventos_salas') . ' AS s ON s.id = g.id_sala'
			. ' INNER JOIN ' . $db->nameQuote('#__p22eventos_palestras') . ' AS p ON p.id = g.id_palestra'
			. ' INNER JOIN ' . $db->nameQuote('#__p22eventos_participantes') . ' AS pp ON pp.id = p.id_palestrante'
			. ' INNER JOIN ' . $db->nameQuote('#__p22eventos_trilhas') . ' AS t ON t.id = p.id_trilha'
			. ' WHERE g.published=1 AND g.id=' . intval( $post['palestraid'] )
			;
			$this->palestra = $lib->getRegistrosCustom( $query , 'loadObject' );
		}
		/*====================================================================*/
		
		// Carrega o template.
		// O nome do template carregado é o mesmo de $ação
		parent::display( $tpl );
    }

	private function _getSelectHtml( $combo , $selectname , $selected = null , $onchange = null , $value = 'id' , $text = 'name' , $intro = '- Selecione -' )
	{
		$options	= array();
		$options[]	= JHTML::_( 'select.option',  '', $intro , $value , $text );
		$options	= ( count( $combo ) ) ? array_merge( $options , $combo ) : $options;
		$select		= JHTML::_(
							'select.genericlist', // Chamada o arquivo select, método genericlist
							$options, // array de dados
							$selectname , // name da select gerada
							$onchange, // outros atributos html da tag
							$value, // nome do campo de valor do select
							$text , // nome do campo de texto do select
							$selected // valor default do select
						);
		return $select;
	}
}