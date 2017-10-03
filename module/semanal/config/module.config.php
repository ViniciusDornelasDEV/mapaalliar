<?php
return array(
    'router' => array(
        'routes' => array(
            //ESCALAS
            'listarEscalas' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/escalas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Escala',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoEscala' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/escalas/novo[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Escala',
                        'action'     => 'novo',
                    ),
                ),
            ),

            //ORGANIZAÇÃO DE EQUIPES
            'pesquisarEquipes' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/organizacao-equipes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Organizacaoequipes',
                        'action'     => 'pesquisar',
                    ),
                ),
            ),
            'visualizarEquipes' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/organizacao-equipes/novo[/:mes][/:ano]',
                    'constraints' => array(
                        'mes'     => '[0-9]+',
                        'ano'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Organizacaoequipes',
                        'action'     => 'visualizar',
                    ),
                ),
            ),

           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Semanal\Controller\Escala' => 'Semanal\Controller\EscalaController',
            'Semanal\Controller\Organizacaoequipes' => 'Semanal\Controller\OrganizacaoequipesController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(

        ),
    ),
);