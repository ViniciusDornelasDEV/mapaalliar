<?php

namespace Avaliacoes\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class PilhaAvaliacoes Extends BaseTable {

    public function getAvaliacoes($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('r' => 'tb_pilha_avaliacoes_referencia'),
                    'r.id = referencia',
                    array('nome_referencia' => 'nome')
                );




            if($params){
            	
                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('data_inicio', $params['inicio'], $params['fim']);
                }

                if(!empty($params['referencia'])){
                    $select->where(array('referencia' => $params['referencia']));
                }

            }
            
            $select->order('data_inicio DESC');
        }); 
    }

    public function getPeriodosAbertos(){
        return $this->getTableGateway()->select(function($select) {
            $select->join(
                    array('r' => 'tb_pilha_avaliacoes_referencia'),
                    'r.id = referencia',
                    array('nome_referencia' => 'nome')
                );

            $select->where('"'.date('Y-m-d').'" >= data_inicio');
            $select->where('"'.date('Y-m-d').'" <= data_fim');
               
            
            $select->order('data_inicio');
        }); 
    }

    public function getPeriodo($idPeriodo){
        return $this->getTableGateway()->select(function($select) use ($idPeriodo) {
            $select->join(
                    array('r' => 'tb_pilha_avaliacoes_referencia'),
                    'r.id = referencia',
                    array('nome_referencia' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = setor',
                    array('nome_setor' => 'nome')
                );


            $select->where(array('tb_pilha_avaliacoes.id' => $idPeriodo));
               
        })->current(); 
    }

    public function insert($dados){
        if($dados['referencia'] == 4){
            //transaction
            $adapter = $this->getTableGateway()->getAdapter();
            $connection = $adapter->getDriver()->getConnection();
            $connection->beginTransaction();
            try {
                $dados['referencia'] = 1;
                parent::insert($dados);

                $dados['referencia'] = 2;
                parent::insert($dados);

                $dados['referencia'] = 3;
                parent::insert($dados);    
                $connection->commit();
                return true;
            }catch (Exception $e) {
                $connection->rollback();
                return false;
            }
        }else{
            return parent::insert($dados);
        }
    }


}
