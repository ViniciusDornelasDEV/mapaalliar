<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;

class Area Extends BaseTable {

    public function getAreas($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            if($params){
            	if(!empty($params['nome'])){
                	$select->where->like('nome', '%'.$params['nome'].'%');
            	}
            }
            
            $select->order('nome');
        }); 
    }
}
