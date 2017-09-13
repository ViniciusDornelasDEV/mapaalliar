<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;
use Application\Model\BaseTable;

class TextoSite Extends BaseTable {

    public function getPaginas($termo){
        return $this->getTableGateway()->select(function($select) use ($termo) {
            $select->where->like('texto', '%'.$termo.'%');

        }); 
    }


}
