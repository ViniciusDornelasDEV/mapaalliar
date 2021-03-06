<?php
namespace Semanal;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\BaseTable;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    // Add this method:
    public function getServiceConfig() {
        return array(
            'factories' => array(
                /* My Tables  */
                'Escala' => function($sm) {
                    $tableGateway = new TableGateway('tb_escala', $sm->get('db_adapter_main'));
                    $updates = new Model\Escala($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'EscalasFuncionario' => function($sm) {
                    $tableGateway = new TableGateway('tb_escala_funcionario', $sm->get('db_adapter_main'));
                    $updates = new Model\EscalaFuncionario($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'FuncionarioEscala' => function($sm) {
                    $tableGateway = new TableGateway('tb_funcionario', $sm->get('db_adapter_main'));
                    $updates = new Model\Funcionario($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
            ),
            'invokables' => array(
                'ImageService' => 'Imagine\Gd\Imagine',
            ),
        );
    }
}
