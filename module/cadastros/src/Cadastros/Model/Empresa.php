<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;

class Empresa Extends BaseTable {

    public function getEmpresas($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            if($params){
            	if(!empty($params['nome'])){
                	$select->where->like('nome', '%'.$params['nome'].'%');
            	}
            }
            
            $select->order('nome');
        }); 
    }

    public function getEmpresasAndUnidades($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'tb_empresa.id = empresa',
                    array('nome_unidade' =>  'nome', 'nome_responsavel' => 'responsavel')
                );

            if($params){
                if(!empty($params['nome'])){
                    $select->where->like('tb_empresa.nome', '%'.$params['nome'].'%');
                }
            }
            
            $select->order('tb_empresa.nome, u.nome');
        }); 
    }

}
