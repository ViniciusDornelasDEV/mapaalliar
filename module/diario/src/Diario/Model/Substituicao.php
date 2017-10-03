<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Substituicao Extends BaseTable {

    public function getSubstituicoes($params = false, $idGestor = false){
        return $this->getTableGateway()->select(function($select) use ($params, $idGestor) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula')
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

                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('data_desligamento', $params['inicio'], $params['fim']);
                }

                if(!empty($params['vaga_rh']) && !empty($params['vaga_rh'])){
                    $select->where(array('vaga_rh' => $params['vaga_rh']));
                }

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }

                if(!empty($params['unidade'])){
                    $select->where(array('u.id' => $params['unidade']));
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
                    array('nome_funcionario' => 'nome', 'matricula', 'funcao', 'unidade')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'f.unidade = u.id',
                    array('nome_unidade' => 'nome', 'empresa')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
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
