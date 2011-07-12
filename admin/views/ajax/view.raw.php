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
		if( $acao == 'addLocal' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );
			
			// Conecta-se com o model.
			jimport( 'joomla.application.component.model' );
			$model				= &JModel::getInstance( 'evento' , 'P22eventosModel' );
			$row				= &$model->getTable( 'locais' );
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
			$combo[]	= JHTML::_( 'select.option',  '', 'Informe o Local' , 'id', 'nome' );
			$locais		= $model->getLocais();
			$combo		= ( count( $locais ) ) ? array_merge( $combo , $locais ) : $combo;
			$select		= JHTML::_(
								'select.genericlist', // Chamada o arquivo select, método genericlist
								$combo, // array de dados
								'id_local', // name da select gerada
								'', // outros atributos html da tag
								'id', // nome do campo de valor do select
								'nome', // nome do campo de texto do select
								$row->id // valor default do select
							);

			echo 'ok__addLocal-=-'. $post['campo'] . '+++' . $select . '+++' . $post['loader'];
			$mainframe->close();
		}
		/*====================================================================*/
		if( $acao == 'addProfissao' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			JRequest::setVar( 'idevento' , intval( $post['idevento'] ) );

			// Conecta-se com o model.
			jimport( 'joomla.application.component.model' );
			$model				= &JModel::getInstance( 'inscrito' , 'P22eventosModel' );
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
								'', // outros atributos html da tag
								'id', // nome do campo de valor do select
								'nome', // nome do campo de texto do select
								$row->id // valor default do select
							);

			echo 'ok__addProfissao-=-'. $post['campo'] . '+++' . $select . '+++' . $post['loader'];
			$mainframe->close();
		}
		/*====================================================================*/
		if( $acao == 'loadTrilhas' )
		{
			JRequest::setVar( 'idevento' , intval( $post['idevento'] ) );
			
			// Conecta-se com o model.
			jimport( 'joomla.application.component.model' );
			$model	= &JModel::getInstance( 'palestra' , 'P22eventosModel' );

			$combo		= array();
			$combo[]	= JHTML::_( 'select.option',  '', '-Selecione-' , 'id', 'nome' );
			$values		= $model->getTrilha();
			$combo		= ( count( $values ) ) ? array_merge( $combo , $values ) : $combo;
			$select		= JHTML::_(
									'select.genericlist', // Chamada o arquivo select, método genericlist
									$combo, // array de dados
									'id_trilha', // name da select gerada
									'', // outros atributos html da tag
									'id', // nome do campo de valor do select
									'nome', // nome do campo de texto do select
									'' // valor default do select
								);
			echo 'ok__loadTrilhas-=-' . $select;

			$mainframe->close();
		}
		/*====================================================================*/
		if( $acao == 'registra_avaliadores' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );
		
			$db				= &JFactory::getDBO();
			$ids_exp		= explode( ',' , $post['ids'] );
			$array_ids		= array_filter( $ids_exp );

			// Limpa base de Dados
			$del = 'DELETE FROM #__p22eventos_avaliadores WHERE id_evento=' . intval( $post['idevento'] );
			$db->setQuery( $del );
			
			if( !$db->query() )
			{
				echo 'notok__Erro ao limpar a base de dados: ' . $db->getErrorMsg();
				$mainframe->close();
			}
		
			// Registra ids
			$values = array();
			for( $a = 0 ; $a < count( $array_ids ) ; $a++ )
			{
				$values[] = '('. intval( $post['idevento'] ) .' , ' . intval( $array_ids[ $a ] ) . ')';
			}

			if( count( $values ) )
			{
				$values = implode( ',' , $values );
				
				$ins = "INSERT INTO #__p22eventos_avaliadores VALUES " . $values;
				$db->setQuery($ins);

				if( !$db->query() )
				{
					echo 'notok__Erro ao inserir avaliadores: ' . $db->getErrorMsg();
					$mainframe->close();
				}
			}
			
			echo 'ok__';
			
			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'registra_grade_globals' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db		= &JFactory::getDBO();
			$field	= ( $post['funcao'] == 'palestras' ) ? 'mesma_palestra' : 'show_palestra_description';
			$field	= $db->nameQuote( $field );
			$value	= intval( $post['value'] );

			$query	= "UPDATE #__p22eventos SET {$field}={$value}";

			$db->setQuery( $query );

			if( !$db->query() )
			{
				echo 'notok__Erro ao atualizar dados: ' . $db->getErrorMsg();
			}
			else
			{
				echo 'ok__';
			}

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'grade_infos' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			jimport( 'joomla.application.component.model' );
			$model					= &JModel::getInstance( 'grade' , 'P22eventosModel' );
			$params					= array();
			$params[]				= $post['funcao'];
			$edit					= ( $post['edit'] ) ? 1 : 0;
			$tipo_palestra			= ( $post['tipo_palestra'] == 'M' ) ? 1 : 0;
			$value_tipo_palestra	= ( $post['valeu_tppalestra'] == 'M' ) ? 1 : 0;
			$palestrante			= ( $post['palestra'] ) ? $model->getIDPalestrante( $post['palestra'] ) : null;
			
			switch( $post['funcao'] )
			{
				case 'dias':
					
					if( $post['dia'] )
					{
						$palestras		= $model->getPalestras( $tipo_palestra , intval( $post['value_palestra'] ) );
						$onchange		= 'onchange="chkInfosGrade(\''. $edit .'\',\'palestras\',\''. $value_tipo_palestra .'\',\''. $post['value_palestra'].'\',\''. $post['value_sala'].'\',\''.$post['value_hora'].'\')"';
						$select			= $this->_getSelectHtml( $palestras , 'id_palestra' , intval( $post['value_palestra'] ) , $onchange );
					}
					$td_field = 'td_palestras';
					$params[] = $this->_getParamsForRequest( $td_field , $select );

					if( $post['dia'] && $edit )
					{
						$salas		= $model->getSalas();
						$onchange	= 'onchange="chkInfosGrade(\''. $edit .'\',\'salas\',\''. $value_tipo_palestra .'\',\''. $post['value_palestra'].'\',\''. $post['value_sala'].'\',\''.$post['value_hora'].'\')"';
						$select		= $this->_getSelectHtml( $salas , 'id_sala' , intval( $post['value_sala'] ) , $onchange );			
						$td_field	= 'td_salas';
						$params[]	= $this->_getParamsForRequest( $td_field , $select );

						$palestranteV	= ( $post['value_palestra'] ) ? $model->getIDPalestrante( $post['value_palestra'] ) : null;
						$horas		= $model->getHoras( $post['dia'] , $post['value_sala'] , $post['value_hora'] , $post['value_palestra'] , $palestranteV );
						$select		= $this->_getSelectHtml( $horas , 'hora' , $post['value_hora'] );
						$td_field	= 'td_horas';
						$params[] =	 $this->_getParamsForRequest( $td_field , $select );
					}
					else
					{
						unset( $select );

						$td_field = 'td_salas';
						$params[] = $this->_getParamsForRequest( $td_field , $select );

						$td_field = 'td_horas';
						$params[] = $this->_getParamsForRequest( $td_field , $select );
					}
					
					break;

				case 'palestras':

					if( $post['palestra'] )
					{
						$value_sala	= ( $post['palestra'] == $post['value_palestra'] ) ? $post['value_sala'] : '';
						$salas		= $model->getSalas();
						$onchange	= 'onchange="chkInfosGrade(\''. $edit .'\',\'salas\',\''. $value_tipo_palestra .'\',\''. $post['value_palestra'].'\',\''. $post['value_sala'].'\',\''.$post['value_hora'].'\')"';
						$select		= $this->_getSelectHtml( $salas , 'id_sala' , $value_sala , $onchange );
					}
					$td_field = 'td_salas';
					$params[] = $this->_getParamsForRequest( $td_field , $select );

					if( $post['palestra'] == $post['value_palestra'] && $edit )
					{
						$horas		= $model->getHoras( $post['dia'] , $post['value_sala'] , $post['value_hora'] );
						$select		= $this->_getSelectHtml( $horas , 'hora' , $post['value_hora'] );
						$td_field	= 'td_horas';
						$params[] =	 $this->_getParamsForRequest( $td_field , $select );
					}
					else
					{
						unset( $select );

						$td_field = 'td_horas';
						$params[] = $this->_getParamsForRequest( $td_field , $select );
					}

					break;

				case 'salas':

					if( $post['sala'] )
					{
						$value_hora	= ( $post['sala'] == $post['value_sala'] ) ? $post['value_hora'] : '';
						$horas		= $model->getHoras( $post['dia'] , $post['sala'] , $post['value_hora'] , $post['palestra'] , $palestrante );
						$select		= $this->_getSelectHtml( $horas , 'hora' , $value_hora );
					}
					$td_field = 'td_horas';
					$params[] = $this->_getParamsForRequest( $td_field , $select );					

					break;
			}

			$params = implode( '+++' , $params );

			echo 'ok__grade_infos-=-'. $params;
			
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
				echo $this->_getSelectHtml( $cidades , 'id_cidade' , intval( $post['cidade'] ) , '' , 'id' , 'nome' );
			}
			else
			{
				echo '<select name="id_cidade" disabled="true"><option value="">-Selecione-</option></select>';
			}

			$mainframe->close();
		}
		/*====================================================================*/
		if( $acao == "getCidadesNewsletters" )
		{
			$uf = (string) $post['uf'];

			$disabled	= ( !$uf ) ? 'disabled' : '';
			$db			= &JFactory::getDBO();

			$query = 'SELECT DISTINCT p.id_cidade '
			. ' FROM #__p22eventos_participantes AS p'
			. ' INNER JOIN #__p22eventos_inscritos AS i ON i.id_participante = p.id'
			. ' WHERE i.published=1 AND i.id_evento='. $post['idevento'] .' AND p.uf=' . $db->Quote( $uf );
			;
			$db->setQuery( $query );
			$inscritosCidades = $db->loadResultArray();
			
			if ( count( $inscritosCidades ) ) $inscritosCidades = array_filter( $inscritosCidades );

			$where_inscritos = ( count( $inscritosCidades ) ) ? ' AND id IN (' . implode( ',' , $inscritosCidades ) . ')' : '';
			
			$query = 'SELECT id , nome '
			. ' FROM #__p22cidades '
			. ' WHERE uf=' . $db->Quote( $uf )
			. $where_inscritos
			;
			$db->setQuery( $query );
			$cidades = $db->loadObjectList();

			if ( count( $cidades ) )
			{
				echo $this->_getSelectHtml( $cidades , 'cidades[]' , '' , ' size="10" multiple style="width:220px"' , 'id' , 'nome' , '- Todas as Cidades -' );
			}
			else
			{
				echo '<select size="10" name="cidades[]" disabled="true" style="width:220px"><option>-Selecione o Estado</option></select>';
			}

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'busca_inscritos' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db			= &JFactory::getDBO();
			$context	= 'com_p22evento.confirmacaoinscritos.list.';
			$tp			= ( $post['tp'] == 'true' ) ? true : false;
			$registros	= ( !$tp ) ? $mainframe->getUserState( 'registros' ) : null;

			require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
			$this->lib = new P22Eventos();
			$this->lib->setDBO( $db );
				
			if ( !$tp || !count( $registros ) )
			{
				$orderby	= ' ORDER BY name ';
				$where		= array();
				$where[]	= 'i.id_evento=' . intval( $post['idevento'] );
				$where[]	= 'i.tp_reg=0';

				// Monta a cláusula where.
				if ( count( $where ) )
				{
					$where = implode( ' AND ', $where );
				}
				else
				{
					$where = '';
				}

				$query = 'SELECT p.*, i.published,'
				. ' ( SELECT u.name FROM #__users AS u WHERE u.id = p.id_user ) AS name,'
				. ' pp.nome AS profissao, c.nome AS cidade'
				. ' FROM #__p22eventos_inscritos AS i'
				. ' INNER JOIN #__p22eventos_participantes AS p ON p.id = i.id_participante'
				. ' RIGHT JOIN #__p22cidades AS c ON c.id = p.id_cidade'
				. ' RIGHT JOIN #__p22eventos_profissoes AS pp ON pp.id = p.id_profissao'
				. ' WHERE '
				. $where
				. $orderby
				;
				$registros = $this->lib->getRegistrosCustom( $query );

				$mainframe->setUserState( 'registros', $registros );
			}
			
			if ( $tp || $post['value'] == 'undefined' || empty( $post['value'] ) ) $registros = null;

			$newRegistros = array();

			$funcao = $post['funcao'];

			if( $funcao == 'cpf' )
			{
				$cpf = str_replace( '.' , '' , $post['value'] );
				$cpf = str_replace( '-' , '' , $cpf );
			}

			for( $j = 0 ; $j < count( $registros ) ; $j++ )
			{
				$row		= &$registros[ $j ];
				$row->cpf	= str_pad( $row->cpf , 11 , "0" , STR_PAD_LEFT );
				$showRow	= true;
				
				switch ( $funcao )
				{
					case 'nome':
						if( !preg_match( "/{$post['value']}/i" , $row->name ) ) $showRow = false;
						break;
					case 'cpf':
						$row->$funcao = str_pad( $row->$funcao , 11 , "0" , STR_PAD_LEFT );
						if( $row->$funcao != $cpf ) $showRow = false;;
						break;
					default:
					case 'id':
						if ( $row->$funcao != $post['value'] ) $showRow = false;;
						break;
				}
				
				if( $showRow ) $newRegistros[] = $row;
			}

			$this->assignRef( 'registros', $newRegistros );
		}
		/*====================================================================*/
		if( $acao == 'confirmar_inscrito' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db			= &JFactory::getDBO();
			$idevento	= intval( $post['idevento'] );
			$id			= intval( $post['id'] );
			$published	= intval( $post['published'] );
			$publish	= ( $published == 0 ) ? 'none' : '';
			$unpublish	= ( $published == 1 ) ? 'none' : '';
			$query		= "UPDATE #__p22eventos_inscritos SET published={$published} WHERE tp_reg=0 AND id_evento={$idevento} AND id_participante={$id}";
			
			$db->setQuery( $query );

			if( !$db->query() )
			{
				echo 'notok__Erro ao atualizar dados: ' . $db->getErrorMsg();
			}
			else
			{
				echo 'ok__confirmar_inscrito-=-' . $id . '+++' . $publish . '+++' . $unpublish;
			}

			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'carrega_dados_usuarios' )
		{
			// Check for request forgeries
			JRequest::checkToken() or jexit( 'Invalid Token' );

			$db			= &JFactory::getDBO();
			require_once( JPATH_COMPONENT . DS . 'classes' . DS . 'eventos.php' );
			$this->lib	= new P22Eventos();
			$this->lib->setDBO( $db );
			
			$user = $this->lib->getDados( 'p22eventos_participantes', 'p.id,p.cpf, p.uf, p.id_cidade,p.id_profissao' , ' WHERE p.id_user='.intval( $post['user'] ),'','loadObject' );

			if( $user->cpf )
			{
				$user->cpf	= str_pad( $user->cpf , 11 , "0" , STR_PAD_LEFT );
				$user->cpf	= $this->lib->setMask( $user->cpf , '999.999.999-99' );
			}

			echo "ok__carrega_dados_usuarios-=-{$user->id}+++{$user->cpf}+++{$user->uf}+++{$user->id_profissao}+++{$user->id_cidade}";
			
			$mainframe->close();
		}
		/*====================================================================*/
		if ( $acao == 'publish_event_actions' )
		{
			$db			= &JFactory::getDBO();
			$idevento	= intval( $post['idevento'] );
			$published	= ( $post['value'] == 'true' ) ? 1 : 0;
			$field		= $db->nameQuote( $post['funcao'] );
			$query		= "UPDATE " . $db->nameQuote( '#__p22eventos') . " SET {$field}={$published} WHERE id={$idevento}";

			$db->setQuery( $query );

			if( !$db->query() )
			{
				echo 'notok__Erro ao atualizar dados: ' . $db->getErrorMsg();
			}
			else
			{
				echo 'ok__publish_event_actions-=-' . $post['funcao'];
			}
			$mainframe->close();
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

	private function _getParamsForRequest( $td_field , $select = null )
	{
		$select = ( $select ) ? $select : '<select name="id_palestra" disabled="true"><option value="">-Selecione-</option></select>';

		return $td_field .'++' . $select;;
	}
}
