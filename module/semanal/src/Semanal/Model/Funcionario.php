<?php

namespace Semanal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Funcionario Extends BaseTable {
	public function getFuncionariosEscala($escala, $idGestor = false){
        return $this->getTableGateway()->select(function($select) use ($escala, $idGestor) {
            $select->join(
                    array('f' => 'tb_funcao'),
                    'f.id = tb_funcionario.funcao',
                    array('nome_funcao' => 'nome', 'setor')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = f.setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    'fg.funcionario = tb_funcionario.id',
                    array(),
                    'LEFT'
                );

            $select->join(
                    array('ef' => 'tb_escala_funcionario'),
                    'ef.funcionario = tb_funcionario.id',
                    array('data_escala' => 'data'),
                    'LEFT'
                );

            if($idGestor){
                $select->where
                        ->nest
                            ->equalTo('tb_funcionario.lider_imediato', $idGestor)
                            ->or
                            ->equalTo('fg.gestor', $idGestor)
                        ->unnest;
                
            }

            $select->where
                    ->nest
                        ->equalTo('ef.escala', $escala['id'])
                        ->or
                        ->isNull('ef.escala')
                    ->unnest;

            $select->where(array('s.id' => $escala['setor'], 'tb_funcionario.unidade' => $escala['unidade'], 'tb_funcionario.ativo' => 'S'));


            $select->order('ef.data, tb_funcionario.nome');

        });
    }

    public function getFuncionariosGestor($idGestor, $idSetor, $unidade = false){
        return $this->getTableGateway()->select(function($select) use ($idGestor, $idSetor, $unidade) {
            $select->join(
                    array('f' => 'tb_funcao'),
                    'f.id = tb_funcionario.funcao',
                    array('nome_funcao' => 'nome', 'setor')
                );
            
            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = f.setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    'fg.funcionario = tb_funcionario.id',
                    array(),
                    'LEFT'
                );
            if($idGestor){
                $select->where
                        ->nest
                            ->equalTo('tb_funcionario.lider_imediato', $idGestor)
                            ->or
                            ->equalTo('fg.gestor', $idGestor)
                        ->unnest;
            }

            if($unidade){
                $select->where(array('tb_funcionario.unidade' => $unidade));
            }

            $select->where(array('s.id' => $idSetor, 'tb_funcionario.ativo' => 'S'));

            $select->order('nome');
        });
    }


}
