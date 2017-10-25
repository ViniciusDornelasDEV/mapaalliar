<?php
namespace Adminpessoal;

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
                'AcaoDisciplinar' => function($sm) {
                    $tableGateway = new TableGateway('tb_acoes_disciplinares', $sm->get('db_adapter_main'));
                    $updates = new Model\Acoes($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'AcaoDisciplinarTipo' => function($sm) {
                    $tableGateway = new TableGateway('tb_acoes_disciplinares_tipo', $sm->get('db_adapter_main'));
                    $updates = new BaseTable($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'AvaliacaoDesempenho' => function($sm) {
                    $tableGateway = new TableGateway('tb_avaliacao_desempenho', $sm->get('db_adapter_main'));
                    $updates = new Model\Desempenho($tableGateway);
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
