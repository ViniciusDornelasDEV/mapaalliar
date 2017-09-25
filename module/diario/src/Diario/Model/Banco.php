<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Banco Extends BaseTable {

    public function getBancos($idUnidade = false){
        return $this->getTableGateway()->select(function($select) use ($idUnidade) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('nome_unidade' => 'nome')
                );

            if($idUnidade){
                $select->where(array('unidade' => $idUnidade));
            }            
            $select->order('inicio DESC');
        }); 
    }


}
