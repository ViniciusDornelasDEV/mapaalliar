<?php

namespace Mensal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;

class Tma Extends BaseTable {

    public function getDados($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
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

            if($params){
                if(isset($params['empresa']) && !empty($params['empresa'])){
                    $select->where(array('empresa' => $params['empresa']));
                }

                if(isset($params['unidade']) && !empty($params['unidade'])){
                    $select->where(array('unidade' => $params['unidade']));
                }
            }
        }); 
    }

    public function getDado($idTme){
        return $this->getTableGateway()->select(function($select) use ($idTme) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('empresa')
                );

                $select->where(array('tb_tma.id' => $idTme));
        })->current(); 
    }

    public function getAndares($idUnidade){
        return $this->getTableGateway()->select(function($select) use ($idUnidade) {
            $select->join(
                    array('a' => 'tb_tma_andar'),
                    'a.tma = tb_tma.id',
                    array('descricao', 'quantidade')
                );

            $select->where(array('tb_tma.unidade' => $idUnidade));
        }); 
    }


}
