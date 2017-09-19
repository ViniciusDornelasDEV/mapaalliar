<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Substituicao Extends BaseTable {

    public function getSubstituicoes($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'f.unidade = u.id',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('func' => 'tb_funcao'),
                    'func.id = f.funcao',
                    array('nome_funcao' => 'nome', 'setor')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = func.setor',
                    array('nome_setor' => 'nome', 'area')
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
                    $select->where->between('data_desligamento', $params['inicio'], $params['fim']);
                }

                if(!empty($params['vaga_rh']) && !empty($params['vaga_rh'])){
                    $select->where(array('vaga_rh' => $params['vaga_rh']));
                }


            }
            
            $select->order('data_desligamento DESC');
        }); 
    }

    public function getSubstituicao($idSubstituicao){
        return $this->getTableGateway()->select(function($select) use ($idSubstituicao) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula', 'funcao')
                );

            $select->join(
                    array('func' => 'tb_funcao'),
                    'func.id = f.funcao',
                    array('nome_funcao' => 'nome', 'setor')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = func.setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );




            $select->where(array('tb_substituicao_programada.id' => $idSubstituicao));

        })->current(); 
    }

}
