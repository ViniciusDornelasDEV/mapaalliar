<?php

namespace Avaliacoes\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Avaliacao Extends BaseTable {

    public function getAvaliacoes($params, $idGestor = false){
    	return $this->getTableGateway()->select(function($select) use ($params, $idGestor) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = f.unidade',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    'fg.funcionario = f.id',
                    array(),
                    'LEFT'
                );

            $select->join(
                    array('pa' => 'tb_pilha_avaliacoes'),
                    'pa.id = tb_avaliacao.periodo',
                    array('data_inicio', 'data_fim', 'referencia_inicio', 'referencia_fim')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = pa.setor',
                    array('nome_setor' => 'nome')
                );

            $select->join(
                    array('a' => 'tb_area'),
                    'a.id = s.area',
                    array('nome_area' => 'nome')
                );

            $select->join(
                    array('par' => 'tb_pilha_avaliacoes_referencia'),
                    'par.id = pa.referencia',
                    array('nome_referencia' => 'nome')
                );

            if($idGestor){
                $select->where
                        ->nest
                            ->equalTo('f.lider_imediato', $idGestor)
                            ->or
                            ->equalTo('fg.gestor', $idGestor)
                        ->unnest;
            }

            $select->where(array('enviado' => 'S'));
            $select->where('avaliacao_pai IS NULL');

            $select->order('pa.referencia_inicio DESC');

            if($params){
                if(!empty($params['inicio']) && !empty($params['fim'])){
                    $select->where->between('pa.referencia_inicio', $params['inicio'], $params['fim']);
                }

                if(!empty($params['referencia'])){
                    $select->where(array('pa.referencia' => $params['referencia']));
                }

                if(!empty($params['area'])){
                    $select->where(array('a.id' => $params['area']));
                }

                if(!empty($params['setor'])){
                    $select->where(array('s.id' => $params['setor']));
                }

                if(!empty($params['matricula'])){
                    $select->where->like('f.matricula', '%'.$params['matricula'].'%');
                }

                if(!empty($params['nome'])){
                    $select->where->like('f.nome', '%'.$params['nome'].'%');
                }
                
                if(!empty($params['empresa'])){
                    $select->where(array('e.id' => $params['empresa']));
                }

                if(!empty($params['unidade'])){
                    $select->where(array('u.id' => $params['unidade']));
                }
            }
        }); 
    }

    public function getAvaliacao($idAvaliacao){
        return $this->getTableGateway()->select(function($select) use ($idAvaliacao) {
            $select->join(
                    array('f' => 'tb_funcionario'),
                    'f.id = funcionario',
                    array('nome_funcionario' => 'nome', 'matricula')
                );

            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'u.id = f.unidade',
                    array('nome_unidade' => 'nome')
                );

            $select->join(
                    array('e' => 'tb_empresa'),
                    'e.id = u.empresa',
                    array('nome_empresa' => 'nome')
                );

            $select->join(
                    array('pa' => 'tb_pilha_avaliacoes'),
                    'pa.id = tb_avaliacao.periodo',
                    array('data_inicio', 'data_fim', 'referencia_inicio', 'referencia_fim')
                );

            $select->join(
                    array('par' => 'tb_pilha_avaliacoes_referencia'),
                    'par.id = pa.referencia',
                    array('nome_referencia' => 'nome')
                );

            $select->join(
                    array('s' => 'tb_setor'),
                    's.id = pa.setor',
                    array('nome_setor' => 'nome')
                );

            $select->where(array('tb_avaliacao.id' => $idAvaliacao));
        })->current(); 
    }

    public function atualizar($dados, $idAvaliacao){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            parent::update($dados, array('id' => $idAvaliacao));
            unset($dados['periodo']);
            parent::update($dados, array('avaliacao_pai' => $idAvaliacao));

            $connection->commit();
            return true;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
        $connection->rollback();
        return false;
    }

    public function inserir($dados, $referencia){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            $idPai = parent::insert($dados);
            $tbReferencia = new TableGateway('tb_pilha_avaliacoes', $adapter);
            $dadosPesquisa = array(
                'referencia_inicio' => $referencia['referencia_inicio'],
                'referencia_fim'    => $referencia['referencia_fim'],
                'setor'             => $referencia['setor']
            );
            $dadosReplicar = $dados;
            $dadosReplicar['avaliacao_pai'] = $idPai;
            if($referencia['referencia'] == 1){
                //pesquisar 2 e 3
                $dadosPesquisa['referencia'] = 2;
                $referencia2 = $tbReferencia->select($dadosPesquisa)->current();
                if($referencia2){
                    $dadosReplicar['periodo'] = $referencia2['id'];
                    parent::insert($dadosReplicar);
                }

                $dadosPesquisa['referencia'] = 3;
                $referencia3 = $tbReferencia->select($dadosPesquisa)->current();
                if($referencia3){
                    $dadosReplicar['periodo'] = $referencia3['id'];
                    parent::insert($dadosReplicar);
                }

            }else{
                if($referencia['referencia'] == 2){
                    //pesquisar 3
                    $dadosPesquisa['referencia'] = 3;
                    $referencia3 = $tbReferencia->select($dadosPesquisa)->current();
                    if($referencia3){
                        $dadosReplicar['periodo'] = $referencia3['id'];
                        parent::insert($dadosReplicar);
                    }
                }
            }

            $connection->commit();
            return $idPai;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
        $connection->rollback();
        return false;
    }


}
