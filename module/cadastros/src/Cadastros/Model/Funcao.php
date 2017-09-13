<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;

class Funcao Extends BaseTable {

    public function getFuncoes($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    'a.id = s.area',
                    array('nome_area' => 'nome')
                );

            if($params){
            	if(!empty($params['nome'])){
                	$select->where->like('tb_funcao.nome', '%'.$params['nome'].'%');
            	}

                if(!empty($params['area'])){
                    $select->where(array('area' => $params['area']));
                }

                if(!empty($params['setor'])){
                    $select->where(array('setor' => $params['setor']));
                }
            }
            
            $select->order('a.nome, s.nome, tb_funcao.nome');
        }); 
    }

    public function getFuncao($idFuncao){
        return $this->getTableGateway()->select(function($select) use ($idFuncao) {
            
            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    'a.id = s.area',
                    array('nome_area' => 'nome')
                );

            $select->where(array('tb_funcao.id' => $idFuncao));
            
        })->current(); 
    }

}
