<?php

namespace Usuario\Model;
use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;

class UsuarioUnidade Extends BaseTable {

    public function getUnidadesByUsuario($idUsuario){
        return $this->getTableGateway()->select(function($select) use ($idUsuario) {
            $select->join(array('u' => 'tb_empresa_unidade'), 'u.id = unidade', array('nome_unidade' => 'nome'));
            $select->join(array('e' => 'tb_empresa'), 'e.id = empresa', array('nome_empresa' => 'nome'));
            $select->where(array('usuario' => $idUsuario));
        }); 
    }

    public function getEmpresasTi($idUsuario){
    	return $this->getTableGateway()->select(function($select) use ($idUsuario) {
	    	$select->join(array('u' => 'tb_empresa_unidade'), 'u.id = unidade', array());
	        $select->join(array('e' => 'tb_empresa'), 'e.id = empresa', array('nome_empresa' => 'nome', 'id_empresa' => 'id'));
	        $select->where(array('usuario' => $idUsuario));
	        $select->group('e.id');
	        $select->order('e.nome');
	    }); 
    }

    public function getUnidadesTi($idUsuario, $idEmpresa){
    	return $this->getTableGateway()->select(function($select) use ($idUsuario, $idEmpresa) {
            $select->join(array('u' => 'tb_empresa_unidade'), 'u.id = unidade', array('nome_unidade' => 'nome', 'id_unidade' => 'id'));
            $select->where(array('usuario' => $idUsuario, 'u.empresa' => $idEmpresa));
            $select->order('u.nome');
        }); 
    }

    public function insert($dados){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $tbUnidade = new TableGateway('tb_empresa_unidade', $adapter);
            if($dados['empresa'] == 'T'){
                //todas as unidades do sistema
                $this->delete(array('usuario' => $dados['usuario']));
                $unidades = $tbUnidade->select();
                
                foreach ($unidades as $unidade) {
                    parent::insert(array(
                            'unidade'       => $unidade['id'],
                            'usuario'    => $dados['usuario']
                        ));
                }
            }else{
                if(empty($dados['unidade']) || $dados['unidade'] == 'T'){
                    //todas as unidades da empresa
                    $unidades = $tbUnidade->select(array('empresa' => $dados['empresa']));
                    foreach ($unidades as $unidade) {
                        $this->delete(array('unidade' => $unidade['id'], 'usuario' => $dados['usuario']));
                        parent::insert(array(
                                'unidade'       => $unidade['id'],
                                'usuario'    => $dados['usuario']
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
