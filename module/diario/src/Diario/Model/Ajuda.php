<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Ajuda Extends BaseTable {

    public function getAjudas($params = false, $idGestor = false){
        return $this->getTableGateway()->select(function($select) use ($params, $idGestor) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula', 'unidade')
                );
            
            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    'fg.funcionario = f.id',
                    array(),
                    'LEFT'
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'f.unidade = u.id',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
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

            if($idGestor){
                $select->where
                        ->nest
                            ->equalTo('f.lider_imediato', $idGestor)
                            ->or
                            ->equalTo('fg.gestor', $idGestor)
                        ->unnest;
            }


            if($params){
            	if(!empty($params['matricula'])){
                    $select->where->like('f.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome_funcionario'])){
                    $select->where->like('f.nome', '%'.$params['nome_funcionario'].'%');
                }

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
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
                    array('nome_unidade' => 'nome', 'empresa')
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
