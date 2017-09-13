<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;

class Setor Extends BaseTable {

    public function getSetores($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            
            $select->join(
                    array('a' => 'tb_area'),
                    'a.id = area',
                    array('nome_area' => 'nome'),
                    'INNER'
                );

            if($params){
            	if(!empty($params['nome'])){
                	$select->where->like('tb_setor.nome', '%'.$params['nome'].'%');
            	}

                if(!empty($params['area'])){
                    $select->where(array('area' => $params['area']));
                }
            }
            
            $select->order('a.nome, tb_setor.nome');
        }); 
    }

}
