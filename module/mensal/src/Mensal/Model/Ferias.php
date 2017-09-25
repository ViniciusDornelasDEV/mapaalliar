<?php

namespace Mensal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Ferias Extends BaseTable {

    public function getFerias($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = f.unidade',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
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

                if(!empty($params['area'])){
                    $select->where(array('a.id' => $params['area']));
                }

                if(!empty($params['setor'])){
                    $select->where(array('s.id' => $params['setor']));
                }

                if(!empty($params['funcao'])){
                    $select->where(array('func.id' => $params['funcao']));
                }

                if(!empty($params['inicio_inicio']) && !empty($params['inicio_fim'])){
                    $select->where->between('data_inicio', $params['inicio_inicio'], $params['inicio_fim']);
                }

                if(!empty($params['fim_inicio']) && !empty($params['fim_fim'])){
                    $select->where->between('data_fim', $params['fim_inicio'], $params['fim_fim']);
                }

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }

                if(!empty($params['unidade'])){
                    $select->where(array('u.id' => $params['unidade']));
                }

            }
            
            $select->order('data_inicio DESC, f.nome');
        }); 
    }


}
