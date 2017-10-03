<?php
return array(
    'router' => array(
        'routes' => array(
            //LIBERAR AVALIACAO
            'listarPeriodo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/periodos[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'indexperiodo',
                    ),
                ),
            ),
            'novoPeriodo' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/avaliacoes/perido/novo',
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'novoperiodo',
                    ),
                ),
            ),
            'alterarPeriodo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/periodo/alterar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'alterarperiodo',
                    ),
                ),
            ),

            //AVALIAÇÕES
           'listarAvaliacoesResponder' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/responder[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'indexavaliacaoresponder',
                    ),
                ),
            ),
           'listarAvaliacoesRespondidas' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/respondidas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'indexavaliacaorespondida',
                    ),
                ),
            ),
           'novoAvaliacao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/novo[/:funcionario][/:referencia]',
                    'constraints' => array(
                        'funcionario'        => '[0-9]+',
                        'referencia'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'novoavaliacao',
                    ),
                ),
            ),
           'visualizarAvaliacao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/avaliacoes/visualizar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'visualizaravaliacao',
                    ),
                ),
            ),
           //admin
           'listarAvaliacoesRespondidasAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/avaliacoes/respondidas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'indexavaliacaorespondidaadmin',
                    ),
                ),
            ),
           'visualizarAvaliacaoAdmin' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/avaliacoes/visualizar[/:id]',
                    'constraints' => array(
                        'id'        => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Avaliacoes\Controller\Avaliacao',
                        'action'     => 'visualizaravaliacaoadmin',
                    ),
                ),
            ),

           


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Avaliacoes\Controller\Avaliacao' => 'Avaliacoes\Controller\AvaliacaoController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'form/avaliacao'              => __DIR__ . '/../view/partials/avaliacao.phtml',
        ),
    ),
);