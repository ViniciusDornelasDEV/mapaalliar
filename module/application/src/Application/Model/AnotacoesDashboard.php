<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;
use Application\Model\BaseTable;

class AnotacoesDashboard Extends BaseTable {

    public function getAnotacoes($inicio, $termino, $tipo){
        return $this->getTableGateway()->select(function($select) use ($inicio, $termino, $tipo) {
             $select->where->between('data', $inicio, $termino);
             $select->where(array('tipo' => $tipo));

        }); 
    }


}
