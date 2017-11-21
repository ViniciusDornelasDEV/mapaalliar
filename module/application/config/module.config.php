<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'homeGestor' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/gestor',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'indexgestor',
                    ),
                ),
            ),
            'download' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/download[/:id][/:service][/:campo]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'download',
                    ),
                ),
            ),
            'downloadByContainer' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/download/container',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'downloadbycontainer',
                    ),
                ),
            ),

            

          

        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'form/generic'              => __DIR__ . '/../view/partials/form.phtml',
            'form/umacoluna'              => __DIR__ . '/../view/partials/formUmaColuna.phtml',
            'view/paginator'              => __DIR__ . '/../view/partials/paginator.phtml',
            'form/pesquisa'              => __DIR__ . '/../view/partials/formPesquisa.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
      'invokables' => array(
         'converterData' => 'Application\Helper\Converterdata',
         'caracterVazio' => 'Application\Helper\Caractervazio',
         'status'        => 'Application\Helper\Status',
         'farol'        => 'Application\Helper\Farol',
         'farolStatus'  => 'Application\Helper\FarolStatus',
      ),
   ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'RH',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'pluginFormatacao' => 'Application\Plugin\Formatacao'    
        )
    ),
);