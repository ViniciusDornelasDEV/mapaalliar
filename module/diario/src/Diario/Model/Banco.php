<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Banco Extends BaseTable {

    public function getBancos($idUnidade = false, $params = false){
        return $this->getTableGateway()->select(function($select) use ($idUnidade, $params) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

            if($idUnidade){
                $select->where(array('unidade' => $idUnidade));
            }

            if($params){

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }

                if(!empty($params['unidade'])){
                    $select->where(array('u.id' => $params['unidade']));
                }

            }

            $select->order('inicio DESC');
        }); 
    }


}
