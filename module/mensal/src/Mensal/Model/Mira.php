<?php

namespace Mensal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;

class Mira Extends BaseTable {

    public function getDados($params = false){
        return $this->getTableGateway()->select(function($select) use ($params) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

            if($params){
                if(isset($params['empresa']) && !empty($params['empresa'])){
                    $select->where(array('empresa' => $params['empresa']));
                }

                if(isset($params['unidade']) && !empty($params['unidade'])){
                    $select->where(array('unidade' => $params['unidade']));
                }
            }
        }); 
    }

    public function getDado($idMira){
        return $this->getTableGateway()->select(function($select) use ($idMira) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('empresa')
                );

                $select->where(array('tb_mira.id' => $idMira));
        })->current(); 
    }

    public function insert($dados){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $tbUnidade = new TableGateway('tb_empresa_unidade', $adapter);
            if($dados['empresa'] == 'T'){
                //todas as unidades do sistema
                $this->delete(array('1' => '1'));
                $unidades = $tbUnidade->select();
                
                foreach ($unidades as $unidade) {
                    parent::insert(array(
                            'unidade'       => $unidade['id'],
                            'nome'          => $dados['nome'],
                            'email'         => $dados['email'],
                            'imagem_1'      => $dados['imagem_1'],
                            'imagem_1'      => $dados['imagem_2'],
                            'imagem_1'      => $dados['imagem_3']
                        ));
                }
            }else{
                if(empty($dados['unidade']) || $dados['unidade'] == 'T'){
                    //todas as unidades da empresa
                    $unidades = $tbUnidade->select(array('empresa' => $dados['empresa']));
                    foreach ($unidades as $unidade) {
                        $this->delete(array('unidade' => $unidade['id']));
                        parent::insert(array(
                                'unidade'       => $unidade['id'],
                                'nome'          => $dados['nome'],
                                'email'         => $dados['email'],
                                'imagem_1'      => $dados['imagem_1'],
                                'imagem_1'      => $dados['imagem_2'],
                                'imagem_1'      => $dados['imagem_3']
                            ));
                    }
                }else{
                    parent::insert($dados);
                }
            }
            $connection->commit();
            return true;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
        $connection->rollback();
        return false;
    }


}
