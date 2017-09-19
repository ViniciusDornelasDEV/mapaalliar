<?php
namespace Diario;

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
                'Ausencia' => function($sm) {
                    $tableGateway = new TableGateway('tb_ausencia', $sm->get('db_adapter_main'));
                    $updates = new Model\Ausencia($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Ajuda' => function($sm) {
                    $tableGateway = new TableGateway('tb_ajuda', $sm->get('db_adapter_main'));
                    $updates = new Model\Ajuda($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'SubstituicaoProgramada' => function($sm) {
                    $tableGateway = new TableGateway('tb_substituicao_programada', $sm->get('db_adapter_main'));
                    $updates = new Model\Substituicao($tableGateway);
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
