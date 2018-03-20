<?php
namespace Cadastros;

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
                'Empresa' => function($sm) {
                    $tableGateway = new TableGateway('tb_empresa', $sm->get('db_adapter_main'));
                    $updates = new Model\Empresa($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Unidade' => function($sm) {
                    $tableGateway = new TableGateway('tb_empresa_unidade', $sm->get('db_adapter_main'));
                     $updates = new Model\Unidade($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Area' => function($sm) {
                    $tableGateway = new TableGateway('tb_area', $sm->get('db_adapter_main'));
                    $updates = new Model\Area($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Setor' => function($sm) {
                    $tableGateway = new TableGateway('tb_setor', $sm->get('db_adapter_main'));
                    $updates = new Model\Setor($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Funcao' => function($sm) {
                    $tableGateway = new TableGateway('tb_funcao', $sm->get('db_adapter_main'));
                    $updates = new Model\Funcao($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Funcionario' => function($sm) {
                    $tableGateway = new TableGateway('tb_funcionario', $sm->get('db_adapter_main'));
                    $updates = new Model\Funcionario($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'FuncionarioGestor' => function($sm) {
                    $tableGateway = new TableGateway('tb_funcionario_gestor', $sm->get('db_adapter_main'));
                    $updates = new Model\FuncionarioGestor($tableGateway);
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
