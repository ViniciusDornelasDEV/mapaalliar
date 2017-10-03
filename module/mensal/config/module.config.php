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
            'deletarFerias' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ferias/deletar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'deletarferias',
                    ),
                ),
            ),
            'carregarFuncionario' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/carregar/funcionario',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'carregarfuncionario',
                    ),
                ),
            ),
            //ADMIN
            'listarFeriasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ferias[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoFeriasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/ferias/novo',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarFeriasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ferias/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Ferias',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),
            //PREMISSAS
            'menuPremissas' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/menu',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'menu',
                    ),
                ),
            ),
            'menuPremissasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/premissas/menu',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'menuadmin',
                    ),
                ),
            ),
            'listarNps' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/nps/listar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'listarnps',
                    ),
                ),
            ),
            'cadastrarNps' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/nps/cadastrar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'cadastrarnps',
                    ),
                ),
            ),
            'visualizarNps' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/nps/visualizar',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'visualizarnps',
                    ),
                ),
            ),

            'listarTma' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/tma/listar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'listartma',
                    ),
                ),
            ),
            'cadastrarTma' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/tma/cadastrar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'cadastrartma',
                    ),
                ),
            ),
            'visualizarTma' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/tma/visualizar',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'visualizartma',
                    ),
                ),
            ),  

            'listarEvolucao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/evolucao/listar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'listarevolucao',
                    ),
                ),
            ),
            'cadastrarEvolucao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/evolucao/cadastrar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'cadastrarevolucao',
                    ),
                ),
            ),
            'visualizarEvolucao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/evolucao/visualizar',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'visualizarevolucao',
                    ),
                ),
            ),  

            'listarTme' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/tme/listar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'listartme',
                    ),
                ),
            ),
            'cadastrarTme' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/tme/cadastrar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'cadastrartme',
                    ),
                ),
            ),
            'visualizarTme' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/tme/visualizar',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'visualizartme',
                    ),
                ),
            ), 

            'listarQmatic' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/qmatic/listar[/:page]',
                    'constraints' => array(
                        'page'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'listarqmatic',
                    ),
                ),
            ),
            'cadastrarQmatic' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/premissas/qmatic/cadastrar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'cadastrarqmatic',
                    ),
                ),
            ),
            'visualizarQmatic' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/premissas/qmatic/visualizar',
                    'defaults' => array(
                        'controller' => 'Mensal\Controller\Premissas',
                        'action'     => 'visualizarqmatic',
                    ),
                ),
            ),  

           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Mensal\Controller\Ferias' => 'Mensal\Controller\FeriasController',
            'Mensal\Controller\Premissas' => 'Mensal\Controller\PremissasController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/gestor'           => __DIR__ . '/../view/layout/layoutGestor.phtml',
        ),
    ),
);