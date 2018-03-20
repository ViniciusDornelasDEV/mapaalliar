<?php
return array(
    'router' => array(
        'routes' => array(
            //EMPRESA
            'listarEmpresa' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/empresas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Empresa',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoEmpresa' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/empresa/novo',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Empresa',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarEmpresa' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/empresa/alterar[/:id][/:unidade]',
                    'constraints' => array(
                        'id'        => '[0-9]+',
                        'unidade'   => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Empresa',
                        'action'     => 'alterar',
                    ),
                ),
            ),

            //ÁREA
            'listarArea' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/areas[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Area',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoArea' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/area/novo',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Area',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarArea' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/area/alterar[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Area',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //SETOR
            'listarSetor' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/setores[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Setor',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoSetor' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/setor/novo',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Setor',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarSetor' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/setor/alterar[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Setor',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            //funcao
            'listarFuncao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcoes[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcao',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoFuncao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcao/novo',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcao',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarFuncao' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcao/alterar[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcao',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            'carregarSetor' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/setor/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcao',
                        'action'     => 'carregarsetor',
                    ),
                ),
            ),            
            //funcionario
            'listarFuncionario' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionarios[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'index',
                    ),
                ),
            ),
            'novoFuncionario' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcionario/novo',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'novo',
                    ),
                ),
            ),
            'alterarFuncionario' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionario/alterar[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'alterar',
                    ),
                ),
            ),
            'importarFuncionario' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcionario/importar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'importarfuncionarios',
                    ),
                ),
            ),
            'deletarGestor' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionario/gestor/deletar[/:idGestor][/:idFuncionario]',
                    'constraints' => array(
                        'idGestor'     => '[0-9]+',
                        'idFuncionario'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'deletargestor',
                    ),
                ),
            ),
            'trocarGestor' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcionario/gestor/mudar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'trocargestor',
                    ),
                ),
            ),

            'carregarFuncao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcao/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregarfuncao',
                    ),
                ),
            ),
            'carregarArea' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/area/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregararea',
                    ),
                ),
            ),
            'carregarUnidade' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/unidade/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregarunidade',
                    ),
                ),
            ),
            'carregarUnidadeTi' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/unidade/ti/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregarunidadeti',
                    ),
                ),
            ),
            'carregarLider' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/lider/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregarlider',
                    ),
                ),
            ),

            'carregarTrocaGestor' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/lider/troca/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregartrocagestor',
                    ),
                ),
            ),

            'carregarSubstituicao' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/substituicao/carregar',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'carregarsubstituicao',
                    ),
                ),
            ),


            //TI
             'listarFuncionarioTi' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionarios/ti[/:page]',
                    'constraints' => array(
                        'page'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'indexti',
                    ),
                ),
            ),
            'novoFuncionarioTi' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/funcionario/novo/ti',
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'novoti',
                    ),
                ),
            ),
            'alterarFuncionarioTi' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionario/alterar/ti[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'alterarti',
                    ),
                ),
            ),
            'deletarGestorTi' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/funcionario/gestor/deletar/ti[/:idGestor][/:idFuncionario]',
                    'constraints' => array(
                        'idGestor'     => '[0-9]+',
                        'idFuncionario'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Cadastros\Controller\Funcionario',
                        'action'     => 'deletargestorti',
                    ),
                ),
            ),


        ),
    ),
	'controllers' => array(
        'invokables' => array(
            'Cadastros\Controller\Empresa' => 'Cadastros\Controller\EmpresaController',
            'Cadastros\Controller\Area' => 'Cadastros\Controller\AreaController',
            'Cadastros\Controller\Funcao' => 'Cadastros\Controller\FuncaoController',
            'Cadastros\Controller\Funcionario' => 'Cadastros\Controller\FuncionarioController',
            'Cadastros\Controller\Setor' => 'Cadastros\Controller\SetorController',
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