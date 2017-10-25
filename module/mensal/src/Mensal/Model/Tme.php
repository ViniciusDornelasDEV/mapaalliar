<?php

namespace Mensal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;

class Tme Extends BaseTable {

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

    public function getDado($idTme){
        return $this->getTableGateway()->select(function($select) use ($idTme) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = unidade',
                    array('empresa')
                );

                $select->where(array('tb_tme.id' => $idTme));
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
                            'caminho_imagem'    => $dados['caminho_imagem']
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
                                'caminho_imagem'    => $dados['caminho_imagem']
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
