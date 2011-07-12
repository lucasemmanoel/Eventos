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

// Recursos Joomla! para trabalhar com controllers.
jimport( 'joomla.application.component.controller' );

class P22eventosController extends JController
{
	private $_idevento;

	public $task;
	
	function __construct()
	{
		parent::__construct();

		$this->_idevento	= JRequest::getInt('idevento');
		$this->task			= JRequest::getCmd('task');
		$tasks				= $this->_getEventTasks();

		if( !in_array( $this->task , $this->_getExceptionTasks() ) )
		{
			if( count( $tasks ) )
			{
				if( in_array( $this->task , $tasks ) )
				{
					JSubMenuHelper::addEntry( '&crarr; Voltar' , $this->_getBackLinks( $this->task ) , false );

					foreach( $tasks AS $taskName => $eventTask )
					{
						if( $eventTask == 'evento' || $eventTask == 'locais' ) continue;

						$checked = ( $eventTask == $this->task ) ? true : false;

						JSubMenuHelper::addEntry( $taskName , 'index.php?option=com_p22evento&task=' . $eventTask . '&idevento=' . intval( $this->_idevento ) , $checked );
					}
				}
			}
		}
	}

	public function ajax()
	{
		//Tipo de aplicação
		$tp = JRequest::getCmd('acao');

        JRequest::setVar( 'view', 'ajax' );
        JRequest::setVar( 'layout', $tp );
        parent::display();
	}

	private function _getEventTasks()
	{
		$array = array(
						0 => 'evento',
						'Certificados' => 'certificados',
						'Inscrições' => 'inscritos',
						'Programação' => 'grades',
						'Avaliação' => 'avaliacao',
						'Mini-cursos' => 'minicursos',
						'Palestras' => 'palestras',
						'Palestrantes' => 'palestrantes',
						'Salas' => 'salas',
						'Colaboradores' => 'colaboradores',
					);
		return $array;
	}

	private function _getExceptionTasks()
	{
		$array = array(
						'inscritos',
						'certificados'
					);
		return $array;
	}

	private function _getBackLinks( $task )
	{
		$array = array(
						'evento' => 'index.php?option=com_p22evento',
						'certificados' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'grades' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'avaliacao' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'minicursos' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'palestras' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'palestrantes' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'salas' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
						'colaboradores' => 'index.php?option=com_p22evento&task=evento&idevento=' . intval( $this->_idevento ),
					);
		return $array[ $task ];
	}

	// Método para exibir a view.
	function display()
	{
		$checked1 = ( $this->task == 'locais' ) ? false : true;
		$checked2 = ( $this->task == 'locais' ) ? true : false;
		
		JSubMenuHelper::addEntry( 'Eventos' , 'index.php?option=com_p22evento' , $checked1 );
		JSubMenuHelper::addEntry( 'Locais' , 'index.php?option=com_p22evento&task=locais' , $checked2 );

		parent::display();
	}

	function evento()
	{
		JRequest::setVar( 'view', 'selevento' );
		parent::display();
	}

	function salas()
	{
		JRequest::setVar( 'view', 'salas' );
		parent::display();
	}

	function colaboradores()
	{
		JRequest::setVar( 'view', 'colaboradores' );
		parent::display();
	}

	function palestrantes()
	{
		JRequest::setVar( 'view', 'palestrantes' );
		parent::display();
	}

	function palestras()
	{
		JRequest::setVar( 'view', 'palestras' );
		parent::display();
	}

	function minicursos()
	{
		JRequest::setVar( 'view', 'minicursos' );
		parent::display();
	}

	function trilhas()
	{
		JRequest::setVar( 'view', 'trilhas' );
		parent::display();
	}

	function avaliacao()
	{
		JRequest::setVar( 'view', 'avaliacaos' );
		parent::display();
	}

	function grades()
	{
		JRequest::setVar( 'view', 'grades' );
		parent::display();
	}

	function locais()
	{
		$checked1 = ( $this->task == 'locais' ) ? false : true;
		$checked2 = ( $this->task == 'locais' ) ? true : false;

		JSubMenuHelper::addEntry( 'Eventos' , 'index.php?option=com_p22evento' , $checked1 );
		JSubMenuHelper::addEntry( 'Locais' , 'index.php?option=com_p22evento&task=locais' , $checked2 );
		
		JRequest::setVar( 'view', 'locais' );
		parent::display();
	}

	function inscritos()
	{
		$controller	= JRequest::getCmd('controller');
		$checked1	= ( $this->task == 'inscritos' ) ? true : false;
		$checked2	= ( $controller == 'inscrito' && $this->task == 'confirmacaoinscritos' ) ? true : false;
		$checked3	= ( $controller == 'inscrito' && $this->task == 'newsletters' ) ? true : false;

		JSubMenuHelper::addEntry( '&crarr; Voltar' , 'index.php?option=com_p22evento&task=evento&idevento='.intval( $this->_idevento ) );
		JSubMenuHelper::addEntry( 'Inscritos' , 'index.php?option=com_p22evento&task=inscritos&idevento='.intval( $this->_idevento ) , $checked1 );
		JSubMenuHelper::addEntry( 'Confirmação de Presença' , 'index.php?option=com_p22evento&controller=inscrito&task=confirmacaoinscritos&idevento='.intval( $this->_idevento ) , $checked2 );
		JSubMenuHelper::addEntry( 'Notificação de Inscritos' , 'index.php?option=com_p22evento&controller=inscrito&task=newsletters&idevento='.intval( $this->_idevento ) , $checked3 );

		JRequest::setVar( 'view', $this->task );
		parent::display();
	}

	function certificados()
	{
		$controller	= JRequest::getCmd('controller');
		$checked1	= ( $this->task == 'certificados' ) ? true : false;
		$checked2	= ( $controller == 'certificado' && $this->task == 'certificadovisual' ) ? true : false;

		JSubMenuHelper::addEntry( '&crarr; Voltar' , 'index.php?option=com_p22evento&task=evento&idevento='.intval( $this->_idevento ) );
		JSubMenuHelper::addEntry( 'Certificados' , 'index.php?option=com_p22evento&task=certificados&idevento='.intval( $this->_idevento ) , $checked1 );
		JSubMenuHelper::addEntry( 'Visualização' , 'index.php?option=com_p22evento&controller=certificado&task=certificadovisual&idevento='.intval( $this->_idevento ) , $checked2 );

		JRequest::setVar( 'view', $this->task );
		parent::display();
	}
}
