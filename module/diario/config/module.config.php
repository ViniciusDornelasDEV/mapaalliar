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
            //CONTRATACAO
            'listarContratacao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/contratacoes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Contratacao',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoContratacao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/contratacoes/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Contratacao',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarContratacao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/contratacoes/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Contratacao',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //Banco de horas
            'listarBancoHoras' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/bancohoras[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoBancoHoras' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/bancohoras/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'deletarBancoHoras' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/bancohoras/deletar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'deletar',
                    ),
                ),
            ),


            //ADMIN
            //CONTROLE DE AUSENCIA
            'listarAusenciaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ausencias[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoAusenciaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/ausencias/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarAusenciaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ausencias/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ausencia',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),
            //CONTROLE DE AJUDAS
            'listarAjudaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ajudas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoAjudaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/ajudas/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarAjudaAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/ajudas/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Ajuda',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),
            //SUBSTITUIÇÃO PROGRAMADA
            'listarSubstituicaoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/substituicoes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoSubstituicaoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/substituicoes/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'alterarSubstituicaoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/substituicoes/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Substituicao',
                        'action'     => 'alteraradmin',
                    ),
                ),
            ),
            //Banco de horas
            'listarBancoHorasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/bancohoras[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'indexadmin',
                    ),
                ),
            ),
            'novoBancoHorasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/bancohoras/novo',
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'novoadmin',
                    ),
                ),
            ),
            'deletarBancoHorasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/bancohoras/deletar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Diario\Controller\Banco',
                        'action'     => 'deletaradmin',
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
            'Diario\Controller\Contratacao' => 'Diario\Controller\ContratacaoController',
            'Diario\Controller\Banco' => 'Diario\Controller\BancoController'
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