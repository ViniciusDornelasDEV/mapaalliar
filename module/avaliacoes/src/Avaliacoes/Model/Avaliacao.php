<?php

namespace Avaliacoes\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Avaliacao Extends BaseTable {

    public function getAvaliacoes($params, $idGestor){
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
                    array('pa' => 'tb_pilha_avaliacoes'),
                    'pa.id = tb_avaliacao.periodo',
                    array('data_inicio', 'data_fim', 'referencia_inicio', 'referencia_fim')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = pa.setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    'a.id = s.area',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('par' => 'tb_pilha_avaliacoes_referencia'),
                    'par.id = pa.referencia',
                    array('nome_referencia' => 'nome')
                );

            $select->where
                    ->nest
                        ->equalTo('f.lider_imediato', $idGestor)
                        ->or
                        ->equalTo('fg.gestor', $idGestor)
                    ->unnest;

            $select->order('pa.referencia_inicio DESC');

            if($params){
                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('pa.referencia_inicio', $params['inicio'], $params['fim']);
                }

                if(!empty($params['referencia'])){
                    $select->where(array('pa.referencia' => $params['referencia']));
                }

                if(!empty($params['area'])){
                    $select->where(array('a.id' => $params['area']));
                }

                if(!empty($params['setor'])){
                    $select->where(array('s.id' => $params['setor']));
                }

                if(!empty($params['matricula'])){
                    $select->where->like('f.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome'])){
                    $select->where->like('f.nome', '%'.$params['nome'].'%');
                }
            }
        }); 
    }

    public function getAvaliacao($idAvaliacao){
        return $this->getTableGateway()->select(function($select) use ($idAvaliacao) {
            
            $select->join(
                    array('pa' => 'tb_pilha_avaliacoes'),
                    'pa.id = tb_avaliacao.periodo',
                    array('data_inicio', 'data_fim', 'referencia_inicio', 'referencia_fim')
                );

            $select->join(
                    array('par' => 'tb_pilha_avaliacoes_referencia'),
                    'par.id = pa.referencia',
                    array('nome_referencia' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = pa.setor',
                    array('nome_setor' => 'nome')
                );

            $select->where(array('tb_avaliacao.id' => $idAvaliacao));
        })->current(); 
    }


}
