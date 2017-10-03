<?php

namespace Semanal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Escala Extends BaseTable {
	public function getEscala($idEscala, $idUnidade = false){
        return $this->getTableGateway()->select(function($select) use ($idEscala, $idUnidade) {
            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = setor',
                    array('nome_setor' => 'nome')
                );

            if($idUnidade){
            	$select->where(array('unidade' => $idUnidade));
            }

            $select->where(array('tb_escala.id' => $idEscala));
        })->current(); 
    }

    public function getEscalasEquipes($mes, $ano, $idUnidade){
        return $this->getTableGateway()->select(function($select) use ($mes, $ano, $idUnidade) {
            $select->join(
                    array('ef' => 'tb_escala_funcionario'),
                    'ef.escala = tb_escala.id',
                    array()
                );

            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = ef.funcionario',
                    array('id_funcionario' => 'id', 'periodo_trabalho')
                );

            $select->join(
                    array('func' => 'tb_funcao'),
                    'func.id = f.funcao',
                    array('nome_funcao' => 'nome', 'id_funcao' => 'id')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = func.setor',
                    array('nome_setor' => 'nome', 'id_setor' => 'id')
                );

            $select->where(array('tb_escala.unidade' => $idUnidade, 'mes' => $mes, 'ano' => $ano));
            $select->group('f.id');
            $select->order('s.nome, func.nome'); 

        }); 
    }

}
