<?php

namespace Usuario\Model;
use Application\Model\BaseTable;

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
}
