<?php
return array(
    'router' => array(
        'routes' => array(
            //FÉRIAS
            'listarFerias' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ferias[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoFerias' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ferias/novo',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarFerias' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ferias/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'alterar',
                    ),
                ),
            ),

           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Mensal\Controller\Ferias' => 'Mensal\Controller\FeriasController'
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