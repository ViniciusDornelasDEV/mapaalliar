<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;
use Application\Model\BaseTable;

class AnotacoesDashboard Extends BaseTable {

    public function getAnotacoes($inicio, $termino, $tipo, $unidade){
        return $this->getTableGateway()->select(function($select) use ($inicio, $termino, $tipo, $unidade) {
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

             $select->where->between('data', $inicio, $termino);
             $select->where(array('tipo' => $tipo, 'unidade' => $unidade));

        }); 
    }


}
