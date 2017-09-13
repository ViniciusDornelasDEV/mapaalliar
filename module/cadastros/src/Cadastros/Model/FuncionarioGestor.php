<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;
use Zend\Db\Sql\Predicate\Expression;

class FuncionarioGestor Extends BaseTable {

    public function getGestoresByFuncionario($idFuncionario){
        return $this->getTableGateway()->select(function($select) use ($idFuncionario) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = gestor',
                    array('nome_gestor' => 'nome')
                );

            $select->where(array('funcionario' => $idFuncionario));

            $select->order('f.nome');
        }); 
    }

    public function getAvaliacoesAbertas($idPeriodo, $idGestor){
        return $this->getTableGateway()->select(function($select) use ($idPeriodo, $idGestor) {
            
            $select->join(
                    array('ap' => 'tb_avaliacao_potencial'),
                    new Expression('ap.funcionario = tb_funcionario_gestor.funcionario AND ap.periodo = ?', $idPeriodo),
                    array(),
                    'LEFT'
                );

            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = tb_funcionario_gestor.funcionario',
                    array('nome_funcionario' => 'nome')
                );

            $select->where(array('tb_funcionario_gestor.gestor' => $idGestor));

            $select->order('f.nome');
            $select->where->isNull('ap.id');
        }); 
    }

}
