<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Funcionario Extends BaseTable {

    public function getFuncionarios($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'unidade = u.id',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

            $select->join(
                    array('f' => 'tb_funcao'),
                    'f.id = funcao',
                    array('nome_funcao' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = f.setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('l' => 'tb_funcionario'),
                    'l.id = tb_funcionario.lider_imediato',
                    array('nome_lider' => 'nome'),
                    'LEFT'
                );



            if($params){
            	if(!empty($params['matricula'])){
                    $select->where->like('tb_funcionario.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome'])){
                	$select->where->like('tb_funcionario.nome', '%'.$params['nome'].'%');
            	}

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }

                if(!empty($params['unidade'])){
                    $select->where(array('u.id' => $params['unidade']));
                }

                if(!empty($params['area'])){
                    $select->where(array('a.id' => $params['area']));
                }

                if(!empty($params['setor'])){
                    $select->where(array('s.id' => $params['setor']));
                }

                if(!empty($params['funcao'])){
                    $select->where(array('f.id' => $params['funcao']));
                }

                if(!empty($params['lider'])){
                    $select->where(array('tb_funcionario.lider' => $params['lider']));
                }


            }
            
            $select->order('e.nome, u.nome, tb_funcionario.nome');
        }); 
    }

    public function getFuncionario($idFuncionario){
        return $this->getTableGateway()->select(function($select) use ($idFuncionario) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'tb_funcionario.unidade = u.id',
                    array('nome_unidade' => 'nome', 'empresa')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

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

            $select->where(array('tb_funcionario.id' => $idFuncionario));
        })->current(); 
    }

    public function getGestores($funcionario){
        return $this->getTableGateway()->select(function($select) use ($funcionario) {

            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    new Expression('fg.gestor = tb_funcionario.id AND fg.funcionario = ?', $funcionario['id']),
                    array(),
                    'LEFT'
                );

            $select->where(array('unidade' => $funcionario['unidade'], 'lider' => 'S'));
            $select->where('funcionario IS NULL');


        });
    }


}
