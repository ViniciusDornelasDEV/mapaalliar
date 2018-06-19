<?php

namespace Diario\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Ajuda Extends BaseTable {

    public function getAjudas($params = false){
        
        return $this->getTableGateway()->select(function($select) use ($params) {

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'tb_ajuda.unidade = u.id',
                    array('nome_unidade_solicitante' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa_solicitante' => 'nome')
                );

            $select->join(
                    array('ud' => 'tb_empresa_unidade'),
                    'tb_ajuda.unidade_destino = ud.id',
                    array('nome_unidade_apoio' => 'nome')
                );

            $select->join(
                    array('ed' => 'tb_empresa'),
                    'ed.id = ud.empresa',
                    array('nome_empresa_apoio' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_ajuda.setor',
                    array('nome_setor' => 'nome', 'setor' => 'id', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );



            if($params){
                if(isset($params['unidade']) && !empty($params['unidade'])){
                    $select->where(array('tb_ajuda.unidade' => $params['unidade']));
                }

                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }    

                if(!empty($params['empresa_apoio'])){
                    $select->where(array('ed.id' => $params['empresa_apoio']));
                }      

                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('tb_ajuda.data_inicio', $params['inicio'], $params['fim']);
                }

                if(isset($params['unidade_destino']) && !empty($params['unidade_destino'])){
                    $select->where(array('unidade_destino' => $params['unidade_destino']));
                }

                if(isset($params['id']) && !empty($params['id'])){
                    $select->where(array('tb_ajuda.id' => $params['id']));
                }

            }
            
            $select->order('data_inicio DESC');
        }); 
    }



    public function getAjuda($idAjuda, $params = false){
        return $this->getTableGateway()->select(function($select) use ($idAjuda, $params) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'tb_ajuda.unidade = u.id',
                    array('nome_unidade' => 'nome', 'empresa')
                );

            //destino
            $select->join(
                    array('ua' => 'tb_empresa_unidade'),
                    'tb_ajuda.unidade_destino = ua.id',
                    array('nome_unidade_destino' => 'nome', 'empresa_apoio' => 'empresa')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = tb_ajuda.setor',
                    array('nome_setor' => 'nome', 'setor' => 'id', 'area')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    's.area = a.id',
                    array('nome_area' => 'nome')
                );

            if($params){
                if(!empty($params['empresa_apoio'])){
                    $select->where(array('ed.id' => $params['empresa_apoio']));
                }      


                if(isset($params['unidade_destino']) && !empty($params['unidade_destino'])){
                    $select->where(array('unidade_destino' => $params['unidade_destino']));
                }
            }

            $select->where(array('tb_ajuda.id' => $idAjuda));
        })->current(); 
    }


}
