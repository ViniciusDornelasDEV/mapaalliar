<?php
namespace Avaliacoes;

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
                'PilhaAvaliacoes' => function($sm) {
                    $tableGateway = new TableGateway('tb_pilha_avaliacoes', $sm->get('db_adapter_main'));
                    $updates = new Model\PilhaAvaliacoes($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'PilhaAvaliacoesReferencia' => function($sm) {
                    $tableGateway = new TableGateway('tb_pilha_avaliacoes_referencia', $sm->get('db_adapter_main'));
                    $updates = new BaseTable($tableGateway);
                    $updates->setServiceLocator($sm);
                    return $updates;
                },
                'Avaliacao' => function($sm) {
                    $tableGateway = new TableGateway('tb_avaliacao', $sm->get('db_adapter_main'));
                    $updates = new Model\Avaliacao($tableGateway);
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
