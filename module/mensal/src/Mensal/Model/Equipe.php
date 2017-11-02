<?php

namespace Mensal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;

class Equipe Extends BaseTable {

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

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_equipes.setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
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

    public function getDado($idEquipe){
        return $this->getTableGateway()->select(function($select) use ($idEquipe) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'tb_equipes.unidade = u.id',
                    array('nome_unidade' => 'nome', 'empresa')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome', 'id_empresa' => 'id')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_equipes.setor',
                    array('nome_setor' => 'nome', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

                $select->where(array('tb_equipes.id' => $idEquipe));
        })->current(); 
    }

   

}
