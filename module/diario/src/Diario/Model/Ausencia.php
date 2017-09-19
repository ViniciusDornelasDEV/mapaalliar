<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Ausencia Extends BaseTable {

    public function getAusencias($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula')
                );

            $select->join(
                    array('func' => 'tb_funcao'),
                    'func.id = f.funcao',
                    array('nome_funcao' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = func.setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );




            if($params){
            	if(!empty($params['matricula'])){
                    $select->where->like('f.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome_funcionario'])){
                    $select->where->like('f.nome', '%'.$params['nome_funcionario'].'%');
                }

                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('data', $params['inicio'], $params['fim']);
                }


            }
            
            $select->order('data DESC');
        }); 
    }


}
