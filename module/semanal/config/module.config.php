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

            'listarEscalasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/escalas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Escala',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoEscalaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/escalas/novo[/:id][/:unidade]',
                    'constraints' => array(
                        'id'        => '[0-9]+',
                        'unidade'   => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Escala',
                        'action'     => 'novoadmin',
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

            'pesquisarEquipesAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/organizacao-equipes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Organizacaoequipes',
                        'action'     => 'pesquisaradmin',
                    ),
                ),
            ),
            'visualizarEquipesAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/organizacao-equipes/novo[/:mes][/:ano][/:unidade]',
                    'constraints' => array(
                        'unidade'     => '[0-9]+', 
                    ),
                    'defaults' => array(
                        'controller' => 'Semanal\Controller\Organizacaoequipes',
                        'action'     => 'visualizaradmin',
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