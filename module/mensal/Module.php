<?php
namespace Mensal;

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

    public function getServiceConfig() {
        return array(
            'factories' => array(
                /* My Tables  */
                'Ferias' => function($sm) {
                    $tableGateway = new TableGateway('tb_ferias', $sm->get('db_adapter_main'));
                    $updates = new Model\Ferias($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Nps' => function($sm) {
                    $tableGateway = new TableGateway('tb_nps', $sm->get('db_adapter_main'));
                    $updates = new Model\Nps($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Evolucao' => function($sm) {
                    $tableGateway = new TableGateway('tb_evolucao', $sm->get('db_adapter_main'));
                    $updates = new Model\Evolucao($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Tme' => function($sm) {
                    $tableGateway = new TableGateway('tb_tme', $sm->get('db_adapter_main'));
                    $updates = new Model\Tme($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Qmatic' => function($sm) {
                    $tableGateway = new TableGateway('tb_qmatic', $sm->get('db_adapter_main'));
                    $updates = new Model\Qmatic($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Tma' => function($sm) {
                    $tableGateway = new TableGateway('tb_tma', $sm->get('db_adapter_main'));
                    $updates = new Model\Tma($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'TmaAndar' => function($sm) {
                    $tableGateway = new TableGateway('tb_tma_andar', $sm->get('db_adapter_main'));
                    $updates = new BaseTable($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Equipe' => function($sm) {
                    $tableGateway = new TableGateway('tb_equipes', $sm->get('db_adapter_main'));
                    $updates = new Model\Equipe($tableGateway);
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
