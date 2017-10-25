<?php

namespace Diario\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Diario\Form\PesquisarFuncionario as formPesquisa;
use Diario\Form\Funcionario as formFuncionario;
use Diario\Form\AlterarFuncionario as formAlterarFuncionario;

class ContratacaoController extends BaseController
{
    private $campos = array(
            'Matrícula'             => 'matricula',
            'Nome'                  => 'nome',
            'Empresa'               => 'nome_empresa',
            'Unidade'               => 'nome_unidade',
            'Área'                  => 'nome_area',
            'Setor'                 => 'nome_setor',
            'Função'                => 'nome_funcao',
            'Tipo de contratação'   => 'tipo_contratacao',
            'Tipo de contrato'      => 'tipo_contrato',
            'Data de início'        => 'data_inicio',
            'Período de trabalho'   => 'periodo_trabalho',
            'Centro de custo'       =>  'ccusto',
            'Descrição c. custo'    =>  'desc_ccusto',
            'Horário'               =>  'horario',
            'Líder imediato'        =>  'nome_lider',
            'Líder'                 =>  'lider',
            'Email'                 =>  'email',
            'Data de nascimento'    =>  'data_nascimento',
            'Data de saída'         =>  'data_saida'
        );

    

   public function indexAction(){
        $this->layout('layout/gestor');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        
        $formPesquisa = new formPesquisa('frmFuncionario', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        
        if(!$this->sessao->parametros){
            $this->sessao->parametros = array();
        }

        $funcionarios = $serviceFuncionario->getFuncionarios($this->sessao->parametros, $usuario['funcionario'])->toArray();
        
        foreach ($funcionarios as $key => $funcionario) {
            $funcionarios[$key]['data_inicio'] = $formPesquisa->converterData($funcionario['data_inicio']);
            $funcionarios[$key]['data_nascimento'] = $formPesquisa->converterData($funcionario['data_nascimento']);
            $funcionarios[$key]['data_saida'] = $formPesquisa->converterData($funcionario['data_saida']);
            $funcionarios[$key]['lider'] = $formPesquisa->simNao($funcionario['lider']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $funcionarios, 'Funciónários');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($funcionarios));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'funcionarios'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());


        if($this->getRequest()->isPost()){
            $formFuncionario->setData($this->getRequest()->getPost());
            if($formFuncionario->isValid()){
                $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');

                $usuario = $this->getServiceLocator()->get('session')->read();
                $gestor = $serviceFuncionario->getRecord($usuario['funcionario']);
                $dados = $formFuncionario->getData();
                $dados['unidade'] = $gestor['unidade'];
                $dados['lider_imediato'] = $usuario['funcionario'];
                
                $idFuncionario = $serviceFuncionario->insert($dados);
                $this->flashMessenger()->addSuccessMessage('Contratação incluída com sucesso!');
                return $this->redirect()->toRoute('alterarContratacao', array('id' => $idFuncionario));
            }
        }
        return new ViewModel(array('formFuncionario' => $formFuncionario));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idFuncionario = $this->params()->fromRoute('id');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $funcionario = $serviceFuncionario->getFuncionario($idFuncionario);

        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Contratação não encontrada!');
            return $this->redirect()->toRoute('listarContratacao');
        }

        $formFuncionario = new formAlterarFuncionario('frmFuncionario', $this->getServiceLocator(), $funcionario);

        $formFuncionario->setData($funcionario);

        if($this->getRequest()->isPost()){
            //alterar funcionario
            $formFuncionario->setData($this->getRequest()->getPost());
            if($formFuncionario->isValid()){
                $dados = $formFuncionario->getData();
                if(!empty($dados['data_saida'])){
                    $dados['ativo'] = 'N';
                }else{
                    $dados['ativo'] = 'S';
                }
                
                $serviceFuncionario->update($dados, array('id' => $idFuncionario));
                $this->flashMessenger()->addSuccessMessage('Contratação alterada com sucesso!');
            }
            return $this->redirect()->toRoute('alterarContratacao', array('id' => $idFuncionario));
        }
        
        return new ViewModel(array(
            'formFuncionario'   => $formFuncionario
            ));
    }


}

