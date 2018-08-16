<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;

class VincularConta Extends BaseTable {

    public function getVinculados($idGestor){
        return $this->getTableGateway()->select(function($select) use ($idGestor) {
            
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome'),
                    'INNER'
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = f.unidade',
                    array('nome_unidade' => 'nome'),
                    'INNER'
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome'),
                    'INNER'
                );

            $select->where(array('funcionario_principal' => $idGestor));
           
        }); 
    }

}
