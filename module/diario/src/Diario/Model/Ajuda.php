<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Ajuda Extends BaseTable {

    public function getAjudas($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula', 'unidade')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'f.unidade = u.id',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_ajuda.setor',
                    array('nome_setor' => 'nome', 'setor' => 'id', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('ud' => 'tb_empresa_unidade'),
                    'ud.id = unidade_destino',
                    array('unidade_destino' => 'nome')
                );




            if($params){
            	if(!empty($params['matricula'])){
                    $select->where->like('f.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome_funcionario'])){
                    $select->where->like('f.nome', '%'.$params['nome_funcionario'].'%');
                }

                if(!empty($params['unidade'])){
                    $select->where(array('unidade' => $params['unidade']));
                }                

                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('tb_ajuda.data_inicio', $params['inicio'], $params['fim']);
                }


            }
            
            $select->order('data_inicio DESC');
        }); 
    }



    public function getAjuda($idAjuda){
        return $this->getTableGateway()->select(function($select) use ($idAjuda) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula', 'unidade')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'f.unidade = u.id',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_ajuda.setor',
                    array('nome_setor' => 'nome', 'setor' => 'id', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );


            $select->where(array('tb_ajuda.id' => $idAjuda));
        })->current(); 
    }


}
