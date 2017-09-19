<?php
return array(
    'router' => array(
        'routes' => array(
            //CONTROLE DE AUSENCIA
            'listarAusencia' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ausencias[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoAusencia' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ausencias/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarAusencia' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ausencias/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //CONTROLE DE AJUDAS
            'listarAjuda' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ajudas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoAjuda' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ajudas/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarAjuda' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ajudas/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            'carregarFuncionarioUnidade' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ajudas/carregarfuncionario',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'carregarfuncionario',
                    ),
                ),
            ),
            //SUBSTITUIÇÃO PROGRAMADA
            'listarSubstituicao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/substituicoes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoSubstituicao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/substituicoes/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarSubstituicao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/substituicoes/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'alterar',
                    ),
                ),
            ),
           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Diario\Controller\Ausencia' => 'Diario\Controller\AusenciaController',
            'Diario\Controller\Ajuda' => 'Diario\Controller\AjudaController',
            'Diario\Controller\Substituicao' => 'Diario\Controller\SubstituicaoController',
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