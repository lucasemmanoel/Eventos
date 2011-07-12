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

class P22Eventos
{
	public $db;

	/**
	 * Seta chamada de Classe para acesso ao Banco de Dados
	 * @param object $db: informando na instanciação desta classe
	 */
	public function setDBO( $db )
	{
		$this->db =& $db;
	}

	/**
	 * Verifica se o ID informando é realmente de um evento.
	 * @return boolean
	 */
	public function checkEvento()
	{
		if ( !$this->countDados( 'p22eventos' , ' WHERE p.id=' . JRequest::getInt( 'idevento' ) ) )
		{
			return false;
		}
		return true;
	}

	/**
	 * Configura data e hora de acordo com os padrões do Framework
	 * @param boolean $local: define se o retorno será no horário local.
	 * @param $datetime: define se a exibição será de uma data e hora específicas.
	 * @return string $now: retorna string de data e hora.
	 */
	public function now( $local = false , $datetime = NULL )
	{
		$config	=& JFactory::getConfig();
		$jnow	=& JFactory::getDate( $datetime );
		$jnow->setOffset( $config->getValue('config.offset') );
		$now	= $jnow->toMySQL( $local );

		return $now;
	}

	/**
	 * Formata a data de forma mais dinâmica.
	 * @param string $value: data ou data e hora informado.
	 * @param word $type: tipo de formatação a ser executado.
	 * @return string $format: data e hora.
	 */
	public function dateFormat( $value , $type = 'date' )
	{
		switch( $type )
		{
			case 'date':
				$format = implode( '/' , array_reverse( explode( '-' , $value ) ) );
				break;
			case 'datetime':
				$exp = explode( ' ' , $value );
				$format = implode( '/' , array_reverse( explode( '-' , $exp[0] ) ) ) . ' ' . $exp[1];
				break;
		}
		return $format;
	}

	/**
	 * Recupera a quantidade de registros de um tabela
	 * de acordo com o filtro.
	 * @param word $table
	 * @param string $where
	 * @return int
	 * @author Hugo Seabra (03/09/2009)
	 */
	public function countDados( $table , $where , $prefix = 'p' )
	{
		// Conexão com a base de dados.
		$this->_db =& JFactory::getDBO();
        // seleciona os registros para exibição.
        $query = 'SELECT COUNT(*) '
        . ' FROM '. $this->db->nameQuote( '#__' . $table ) . ' AS ' . $prefix
		. ' ' . $where
        ;
        $this->db->setQuery( $query );
        $res	= $this->db->loadResult();
		$error	= $this->db->getErrorMsg();
		if( $error ) echo JUtility::dump( $error );
        return $res;
	}

	/**
	 * Recupera dados para consulta rápida.
	 * @access public
	 * @param word $table		Tabela a ser consultada
	 * @param word $getCampo	Campos a serem buscados na consulta
	 * @param string $where		Condição da consulta
	 * @param word $tpResult	Tipo de Resultado a ser gerado
	 * @return void
	 * @author					Hugo Seabra (14/07/2009)
	 */
	public function getDados( $table , $getCampo , $where , $orderby , $tpResult , $pageNav = false )
	{
         // seleciona os registros para exibição.
        $query = 'SELECT ' . $getCampo
        . ' FROM ' . $this->db->nameQuote( '#__' . $table ) . ' AS p '
		. $where . ' '
		. $orderby;

		$offset = ( $pageNav->limitstart ) ? $pageNav->limitstart : 0;
		$limit	= ( $pageNav->limit ) ? $pageNav->limit : 0;
		
		$this->db->setQuery( $query , $offset , $limit );
        $res = $this->db->$tpResult();
		$error = $this->db->getErrorMsg();
		if( $error ) echo JUtility::dump( $error );
		return $res;
	}

	/**
	 * Consulta rápida e customizada do Banco de Dados.
	 * @param string $sql
	 * @param string $tpResult
	 * @return array
	 */
	public function getRegistrosCustom( $sql , $tpResult = 'loadObjectList' , $pageNav = false )
	{
		$offset = ( $pageNav->limitstart ) ? $pageNav->limitstart : 0;
		$limit	= ( $pageNav->limit ) ? $pageNav->limit : 0;

		$this->db->setQuery( $sql , $offset , $limit );
  
		$return = $this->db->$tpResult();
		$error = $this->db->getErrorMsg();
		if( $error ) echo JUtility::dump( $error );
        return $return;
	}

	/**
	 * Pega o nome do evento de acordo com o ID informado.
	 * @return string $nome: nome do evento.
	 */
	public function eventName()
	{
		$idevento = JRequest::getInt( 'idevento' );

		$name = $this->getDados( 'p22eventos' , 'p.nome' , ' WHERE p.id=' . intval( $idevento ) , '' , 'loadResult' );

		return $name;
	}

	/**
	 * Formata uma caixa de seleção dos Estados da federação.
	 * @param word $estado: estado selecionado.
	 * @param string $attribs: atributos gerais.
	 * @param string $name: nome da caixa de seleção
	 * @param string $intro: texto inicial da caixa de seleção.
	 * @return html $select: HTML da caixa de seleção.
	 */
	public function getEstado( $estado = null , $attribs = '' , $name = 'uf' , $intro = '- Estado -' )
	{
		$estado = strtoupper( trim($estado) );
		unset($campo);
		$campo = array();

		$campo[] = JHTML::_('select.option','', $intro );
		$campo[] = JHTML::_('select.option', 'AC','Acre');
		$campo[] = JHTML::_('select.option', 'AL','Alagoas');
		$campo[] = JHTML::_('select.option', 'AP','Amapá');
		$campo[] = JHTML::_('select.option', 'AM','Amazonas');
		$campo[] = JHTML::_('select.option', 'BA','Bahia');
		$campo[] = JHTML::_('select.option', 'CE','Ceará');
		$campo[] = JHTML::_('select.option', 'DF','Distrito Federal');
		$campo[] = JHTML::_('select.option', 'ES','Espírito Santo');
		$campo[] = JHTML::_('select.option', 'GO','Goiás');
		$campo[] = JHTML::_('select.option', 'MA','Maranhão');
		$campo[] = JHTML::_('select.option', 'MT','Mato Grosso');
		$campo[] = JHTML::_('select.option', 'MS','Mato Grosso do Sul');
		$campo[] = JHTML::_('select.option', 'MG','Minas Gerais');
		$campo[] = JHTML::_('select.option', 'PA','Pará');
		$campo[] = JHTML::_('select.option', 'PB','Paraíba');
		$campo[] = JHTML::_('select.option', 'PR','Paraná');
		$campo[] = JHTML::_('select.option', 'PE','Pernambuco');
		$campo[] = JHTML::_('select.option', 'PI','Piauí');
		$campo[] = JHTML::_('select.option', 'RJ','Rio de Janeiro');
		$campo[] = JHTML::_('select.option', 'RN','Rio Grande do Norte');
		$campo[] = JHTML::_('select.option', 'RS','Rio Grande do Sul');
		$campo[] = JHTML::_('select.option', 'RO','Rondônia');
		$campo[] = JHTML::_('select.option', 'RR','Rorâima');
		$campo[] = JHTML::_('select.option', 'SC','Santa Catarina');
		$campo[] = JHTML::_('select.option', 'SP','São Paulo');
		$campo[] = JHTML::_('select.option', 'SE','Sergipe');
		$campo[] = JHTML::_('select.option', 'TO','Tocantins');

		$estado = JHTML::_(
							'select.genericlist',
							$campo,
							$name,
							'class="inputbox required" size="1" ' . $attribs,
							'value',
							'text',
							(string)$estado
						);
		return $estado;
	}

	/**
	 * Formata um determinado valor de acordo com a máscara desejada.
	 * @param string $expr: expressão a ser formatada.
	 * @param word $mask: máscara
	 * @return string $ret: expressão retornada com a máscara aplicada.
	 */
	public function setMask($expr,$mask)
	{
		$ret="";
		$j=0;
		for ($i = 0; $i < strlen($expr); $i ++)
		{
			if ( ( $mask[$j]!="9" ) and ( $mask[$j]!="X" ) )
			{
				$ret.=$mask[$j];
				$j++;
			}
			$ret.=$expr[$i];
			$j++;
		}
		return $ret;
	}

	/**
	 * Pega registros de profissões no Banco de Dados.
	 * @return array $profissoes
	 */
	function getProfissoes()
	{
		$profissoes = $this->getDados( 'p22eventos_profissoes' , 'p.id,p.nome' , ' WHERE p.published=1' , ' ORDER BY p.nome' , 'loadObjectList' );

		return $profissoes;
	}

	/**
	 * Pega o nome do Mês de acordo com o número informado.
	 * @param integer $numMes: número do mês informado.
	 * @return string $array
	 */
	function getMesNome( $numMes )
	{
		$numMes	= intval( $numMes );
		$array	= array(
						1 => 'Janeiro',
						2 => 'Fevereiro',
						3 => 'Março',
						4 => 'Abril',
						5 => 'Maio',
						6 => 'Junho',
						7 => 'Julho',
						8 => 'Agosto',
						9 => 'Setembro',
						10 => 'Outubro',
						11 => 'Novembro',
						12 => 'Dezembro'
					);
		
		return $array[ $numMes ];
	}

	/**
	 * Verifica se o usuário possui algum cargo ou relação direta com evento
	 * e que tipo de relação.
	 * @param integer $id_evento: ID do evento
	 * @param integer $id_participante: ID do participante do evento
	 * @param integer $tp_reg: 0 ou 1
	 * @return boolean
	 */
	public function registraRelacaoEventoUser( $id_evento , $id_participante , $tp_reg )
	{
		$chkReg = $this->countDados( 'p22eventos_inscritos' , ' WHERE p.id_evento='.intval( $id_evento ) . ' AND p.id_participante='.intval( $id_participante ) . ' AND p.tp_reg=' . intval( $tp_reg ) );

		if ( !$chkReg )
		{
			$now	= $this->now();
			$query	= 'INSERT INTO ' . $this->db->nameQuote( '#__p22eventos_inscritos' ) . ' VALUES ( '. intval( $id_participante ) .' , '. intval( $id_evento ) .', 0, "'. $now .'", "'. intval( $tp_reg ) .'")';

			$this->db->setQuery( $query );

			if( !$this->db->query() )
			{
				JError::raiseWarning( 100, 'Erro ao vincular participante ao evento: ' . $this->db->getErrorMsg() );
				return false;
			}
		}

		return true;
	}

	/**
	 * Retorna uma lista de colaboradores de evento que estão cadastrados como avaliadores.
	 * @param integer $id_evento
	 * @param array $colaboradores: IDs de colaboradores.
	 * @return boolean
	 */
	public function isAvaliador( $id_evento , $colaboradores )
	{
		if( !count( $colaboradores ) ) return;

		$avaliadores = $this->getDados( 'p22eventos_avaliadores' , 'p.id_participante' , ' WHERE p.id_evento='. intval( $id_evento ) .' AND p.id_participante IN ('. implode( ',' , $colaboradores ) .')' , '' , 'loadResultArray' );
		
		$result = array();
		foreach( $colaboradores AS $id )
		{
			if( in_array( $id , $avaliadores ) )
			{
				$result[ $id ] = true;
			}
		}
		
		return $result;
	}

	/**
	 * Verifica uma tabela específica possui registros específicos em um determinado campo.
	 * @param integer $id_evento
	 * @param word $table
	 * @param word $field
	 * @param array $ids
	 * @param string $wherefield: definição de parâmetros para consulta
	 * @param boolean $verify: verifica dados para o retorno de um array com chaves de IDs
	 * @return array $result: array com respostas se a tabela possui registros de campos.
	 */
	public function hasData( $id_evento , $table , $field , $ids , $wherefield = null , $verify = false )
	{
		if( !count( $ids ) ) return;

		$where = ( !$wherefield ) ? $field : $wherefield;

		$data = $this->getDados( $table , 'p.' . $field , ' WHERE p.id_evento='. intval( $id_evento ) .' AND p.' . $where . ' IN ('. implode( ',' , $ids ) .')' , '' , 'loadResultArray' );

		if( count( $data ) )
		{
			$data = array_unique( $data );
		}

		$result = array();
		if ( $verify )
		{
			if ( count( $data ) )
			{
				foreach( $data AS $value )
				{
					foreach( $ids AS $id )
					{
						$result[ $id ] = $value;
					}
				}
			}
		}
		else
		{
			foreach( $ids AS $id )
			{
				if( in_array( $id , $data ) )
				{
					$result[ $id ] = true;
				}
			}
		}
		
		return $result;
	}

	/**
	 * O nome ficou horrível.
	 * Busca e formata uma string de período do evento.
	 * @param integer $idevento
	 * @param boolean $returnArray: define se o retorno será de um array de períodos
	 * de vários eventos ou de um evento específico. Se 'true', o $id_evento é irrelevante.
	 * @return string/array: retorna o(s) período(s)
	 */
	public function getEventDetailString( $idevento = null , $returnArray = false )
	{
		if ( !$returnArray && !$idevento ) return;
		
		$query = 'SELECT e.id, e.data_inicio, e.data_fim, e.ano'
		. ' FROM #__p22eventos AS e'
		;

		if( !$returnArray )
		{
			$query .= ' WHERE e.id = ' . intval( $idevento );
		}
		$load	= ( $returnArray ) ? 'loadObjectList' : 'loadObject';
		$evento = $this->getRegistrosCustom( $query , $load );

		if ( !$returnArray )
		{
			$expDI		= explode( '-' , $evento->data_inicio );
			$expDF		= explode( '-' , $evento->data_fim );
			$dia_inicio	= $expDI[ 2 ];
			$dia_fim	= $expDF[ 2 ];
			$mes_inicio	= $this->getMesNome( $expDI [ 1 ] );
			$mes_fim	= $this->getMesNome( $expDF [ 1 ] );
			$mesmoMes	= ( $mes_inicio == $mes_fim ) ? true : false;

			if ( $mesmoMes )
			{
				$periodo = ( $data_inicio == $data_fim ) ? "{$dia_inicio} de {$mes_inicio} de {$evento->ano}" : "{$dia_inicio} a {$dia_fim} de {$mes_inicio} de {$evento->ano}";
			}
			else
			{
				$periodo = "{$dia_inicio} de {$mes_inicio} a {$dia_fim} de {$mes_fim} de {$evento->ano}";
			}
		}
		else
		{
			for( $c = 0 ; $c < count( $evento ) ; $c++ )
			{
				$row = &$evento[ $c ];

				$expDI		= explode( '-' , $row->data_inicio );
				$expDF		= explode( '-' , $row->data_fim );
				$dia_inicio	= $expDI[ 2 ];
				$dia_fim	= $expDF[ 2 ];
				$mes_inicio	= $this->getMesNome( $expDI [ 1 ] );
				$mes_fim	= $this->getMesNome( $expDF [ 1 ] );
				$mesmoMes	= ( $mes_inicio == $mes_fim ) ? true : false;

				if ( $mesmoMes )
				{
					$periodo[ $row->id ] = ( $data_inicio == $data_fim ) ? "{$dia_inicio} de {$mes_inicio} de {$row->ano}" : "{$dia_inicio} a {$dia_fim} de {$mes_inicio} de {$row->ano}";
				}
				else
				{
					$periodo[ $row->id ] = "{$dia_inicio} de {$mes_inicio} a {$dia_fim} de {$mes_fim} de {$row->ano}";
				}
			}
		}

		return $periodo;
	}

	/**
	 * Verifica se uma aplicação ou funcionalidade do evento está como 0 ou 1.
	 * @param integer $idevento
	 * @param word $field: campo da tabela de eventos.
	 * @return boolean
	 */
	public function isPublished( $idevento , $field )
	{
		if ( !$idevento || !$field ) return false;

		$published = $this->getDados( 'p22eventos' , 'p.' . $field , ' WHERE p.id=' . intval( $idevento ) , '' , 'loadResult' );
		$published = intval( $published );

		return ( $published ) ? true : false;
	}
}