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

    public function getAreaUnidade($idUnidade = false){
        return $this->getTableGateway()->select(function($select) use ($idUnidade) {
            
            if($idUnidade){
                //join com setor
                $select->join(
                    array('s' => 'tb_setor'),
                    's.area = tb_area.id',
                    array()
                );
                //join com funcao
                $select->join(
                    array('f' => 'tb_funcao'),
                    'f.setor = s.id',
                    array()
                );
                //join com funcionarios
                $select->join(
                    array('fu' => 'tb_funcionario'),
                    'fu.funcao = f.id',
                    array()
                );
                //quando funcionario for da unidade
                $select->where(array('fu.unidade' => $idUnidade));
            }

            $select->order('tb_area.nome');
            
        }); 
    }
}
