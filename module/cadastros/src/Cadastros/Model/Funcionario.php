<?php

namespace Cadastros\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class Funcionario Extends BaseTable {

    public function getFuncionarios($params = false, $idGestor = false){
        return $this->getTableGateway()->select(function($select) use ($params, $idGestor) {
            $select->join(
                    array('u' => 'tb_empresa_unidade'),
                    'unidade = u.id',
                    array('nome_unidade' => 'nome', 'id_unidade' => 'id')
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

                if(!empty($params['lider_imediato'])){
                    $select->where(array('tb_funcionario.lider_imediato' => $params['lider_imediato']));
                }

                //não exibir este funcionario
                if(!empty($params['funcionario'])){
                    $select->where->notEqualTo('tb_funcionario.id', $params['funcionario']);
                }

                if(!empty($params['ativo'])){
                    $select->where(array('tb_funcionario.ativo' => $params['ativo']));
                }

            }
            
            $select->order('e.nome, u.nome, tb_funcionario.nome');

            $select->group('tb_funcionario.id');
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
                    array('nome_empresa' => 'nome', 'id_empresa' => 'id')
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

    public function getFuncionariosAvaliacaoAberta($periodo, $idGestor){
        return $this->getTableGateway()->select(function($select) use ($periodo, $idGestor) {
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
                    array('av' => 'tb_avaliacao'),
                    new Expression('av.funcionario = tb_funcionario.id AND av.periodo = ?', $periodo['id']),
                    array(),
                    'LEFT'
                );

            $select->where
                    ->nest
                        ->equalTo('tb_funcionario.lider_imediato', $idGestor)
                        ->or
                        ->equalTo('fg.gestor', $idGestor)
                    ->unnest;

            $select->where
                    ->nest
                        ->isNull('av.id')
                        ->or
                        ->equalTo('av.enviado', 'N')
                    ->unnest;

            //$select->where('av.id IS NULL');
            $select->where(array('f.setor' => $periodo['setor'], 'tb_funcionario.ativo = "S"'));

        });
    }

    public function getFuncionarioGestor($idFuncionario, $idGestor){
        return $this->getTableGateway()->select(function($select) use ($idFuncionario, $idGestor) {

            $select->join(
                    array('fg' => 'tb_funcionario_gestor'),
                    'fg.funcionario = tb_funcionario.id',
                    array(),
                    'LEFT'
                );

            $select->where
                    ->nest
                        ->equalTo('tb_funcionario.lider_imediato', $idGestor)
                        ->or
                        ->equalTo('fg.gestor', $idGestor)
                    ->unnest;

            $select->where(array('tb_funcionario.id' => $idFuncionario));

        })->current();
    }

    public function getFuncionariosSetor($idSetor, $idUnidade){
        return $this->getTableGateway()->select(function($select) use ($idSetor, $idUnidade) {
            $select->columns(array(
                    'total' => new Expression('COUNT(tb_funcionario.id)')
                ));

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

            $select->where(array('s.id' => $idSetor, 'unidade' => $idUnidade));

        })->current();
    }

    public function importar($objExcel, $maiorLinha, $dados){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $tbArea = new TableGateway('tb_area', $adapter);
            $tbSetor = new TableGateway('tb_setor', $adapter);
            $tbFuncao = new TableGateway('tb_funcao', $adapter);
            $tbGerente = new TableGateway('tb_funcionario_gestor', $adapter);
            for ($row = 2; $row <= $maiorLinha; $row++){ 
                $rowData = $objExcel->rangeToArray('A'.$row.':'.'X'.$row,
                                                NULL,
                                                true,
                                                true,
                                                false);


                $rowData = $rowData[0];
                $rowData[1] = str_replace(' ', '', $rowData[1]);

                if(empty($rowData[2])){
                    continue;
                }
                
                //pesquisar área
                $area = $tbArea->select(array('nome' => $rowData[16]))->current();
                
                if(!$area){
                    $tbArea->insert(array('nome' => $rowData[16], 'responsavel' => 'N/A'));
                    $idArea = $tbArea->getLastInsertValue();
                }else{
                    $idArea = $area['id'];
                }
                //pesquisar setor
                $setor = $tbSetor->select(array('nome' => $rowData[17]))->current();
                if(!$setor){
                    $tbSetor->insert(array('nome' => $rowData[17], 'area' => $idArea));
                    $idSetor = $tbSetor->getLastInsertValue();
                }else{
                    $idSetor = $setor['id'];
                }

                //pesquisar funcao
                $funcao = $tbFuncao->select(array('nome' => $rowData[4]))->current();
                if(!$funcao){
                    $tbFuncao->insert(array('nome' => $rowData[4], 'setor' => $idSetor));
                    $idFuncao = $tbFuncao->getLastInsertValue();
                }else{
                    $idFuncao = $funcao['id'];
                }

                //pesquisar lider imediato
                $lider = $this->getRecordFromArray(array('nome' => $rowData[15], 'unidade' => $dados['unidade']));
                $idLider = '';
                if($lider){
                    $idLider = $lider['id'];
                }
                //pesquisar funcionário da unidade por matricula
                $rowData[1] = $this->retirarZero($rowData[1]);
                $rowData[1] = str_replace(' ', '', $rowData[1]);
                
                $dadosFuncionario = array(
                        'matricula'         => $rowData[1],
                        'nome'              => $rowData[2],
                        'unidade'           => $dados['unidade'],
                        'funcao'            => $idFuncao,
                        'tipo_contratacao'  => 'Interna',
                        'tipo_contrato'     => str_replace(' ', '', $rowData[22]),
                        'data_inicio'       => $this->ConverteData($rowData[5]),
                        'periodo_trabalho'  => $rowData[19],
                        'inicio_turno'      => $rowData[20],
                        'fim_turno'         => $rowData[21],
                        'lider_imediato'    => $idLider,
                        'lider'             => 'N',
                        'ccusto'            => $rowData[6],
                        'desc_ccusto'       => $rowData[7],
                        'horario'           => $rowData[14]
                    );

                
                $funcionario = $this->getRecord($rowData[1], 'matricula');
                
                if($funcionario){
                    //update
                    $this->update($dadosFuncionario, array('id' => $funcionario['id']));
                    $idFuncionario = $funcionario['id'];
                }else{
                    //insert
                    $idFuncionario = $this->insert($dadosFuncionario);
                }

                //pesquisar e inserir gerente
                $gerente = $this->getRecordFromArray(array('nome' => $rowData[13], 'unidade' => $dados['unidade']));
                $idGerente = '';
                if($gerente){
                    $idGerente = $gerente['id'];
                    $funcionarioGerente = $tbGerente->select(array('gestor' => $idGerente, 'funcionario' => $idFuncionario))->current();
                    
                    if(!$funcionarioGerente){
                        $tbGerente->insert(array('gestor' => $idGerente, 'funcionario' => $idFuncionario));
                        $idArea = $tbArea->getLastInsertValue();
                    }
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

    public function trocarGestor($dados){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $this->update(array('lider_imediato' => $dados['novo_lider']), array('lider_imediato' => $dados['lider_imediato']));

            $tbGestor = new TableGateway('tb_funcionario_gestor', $adapter);
            $tbGestor->update(array('gestor' => $dados['novo_lider']), array('gestor' => $dados['lider_imediato']));

            $connection->commit();
            return true;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
        $connection->rollback();
        return false;
    }

    private function ConverteData($Data){
        @$TipoData = stristr($Data, "/");
        if($TipoData != false){
            $Texto = explode("/",$Data);
            return $Texto[2]."-".$Texto[1]."-".$Texto[0];
        }else{
            $Texto = explode("-",$Data);
            return $Texto[2]."/".$Texto[1]."/".$Texto[0];
         }
    }

    private function retirarZero($matricula){
        if(strcasecmp($matricula[0], "0") == 0){
            $matricula = substr($matricula, 1);
            return $this->retirarZero($matricula);
        }
        return $matricula;
    }


}
