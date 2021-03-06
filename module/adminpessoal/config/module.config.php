<?php
return array(
    'router' => array(
        'routes' => array(
            //AÇÕES DISCIPLINARES
            'listarAcoesDisciplinares' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/acoesdisciplinares[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoAcoesDisciplinares' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/acoesdisciplinares/novo',
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarAcoesDisciplinares' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/acoesdisciplinares/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //admin
            'listarAcoesDisciplinaresAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/acoesdisciplinares[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoAcoesDisciplinaresAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/acoesdisciplinares/novo',
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarAcoesDisciplinaresAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/acoesdisciplinares/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Acoes',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),

            //AVALIAÇÃO DE DESEMPENHO
            'listarAvaliacoesDesempenho' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoesdesempenho[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoAvaliacoesDesempenho' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/avaliacoesdesempenho/novo',
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarAvaliacoesDesempenho' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoesdesempenho/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //admin
            'listarAvaliacoesDesempenhoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/avaliacoesdesempenho[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoAvaliacoesDesempenhoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/avaliacoesdesempenho/novo',
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarAvaliacoesDesempenhoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/avaliacoesdesempenho/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Adminpessoal\Controller\Desempenho',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),

           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Adminpessoal\Controller\Acoes' => 'Adminpessoal\Controller\AcoesController',
            'Adminpessoal\Controller\Desempenho' => 'Adminpessoal\Controller\DesempenhoController'
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