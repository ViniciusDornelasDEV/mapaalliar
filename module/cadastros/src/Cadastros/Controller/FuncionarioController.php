<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Cadastros\Form\PesquisarFuncionario as formPesquisa;
use Cadastros\Form\Funcionario as formFuncionario;
use Cadastros\Form\VincularGestor as formGestor;

class FuncionarioController extends BaseController
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
            'Líder imediato'        =>  'nome_lider',
            'Líder'                 =>  'lider',
            'Email'                 =>  'email',
            'Data de nascimento'    =>  'data_nascimento',
            'Data de saída'         =>  'data_saida'
        );

    public function indexAction(){
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        
        $formPesquisa = new formPesquisa('frmFuncionario', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $funcionarios = $serviceFuncionario->getFuncionarios($this->sessao->parametros)->toArray();
        
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
        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formFuncionario->setData($this->getRequest()->getPost());
            if($formFuncionario->isValid()){
                $idFuncionario = $this->getServiceLocator()->get('Funcionario')->insert($formFuncionario->getData());
                $this->flashMessenger()->addSuccessMessage('Funcionário incluído com sucesso!');
                return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
            }
        }
        return new ViewModel(array('formFuncionario' => $formFuncionario));
    }

    public function alterarAction(){
        $idFuncionario = $this->params()->fromRoute('id');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $funcionario = $serviceFuncionario->getFuncionario($idFuncionario);

        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarFuncionario');
        }

        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        $formFuncionario->setData($funcionario);
        $serviceGestor = $this->getServiceLocator()->get('FuncionarioGestor');
        $formGestor = new formGestor('frmGestor', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['gestor'])){
                //salvar gestor
                $formGestor->setData($dados);
                if($formGestor->isValid()){
                    $dados = $formGestor->getData();
                    $dados['funcionario'] = $idFuncionario;
                    $serviceGestor->insert($dados);
                    $this->flashMessenger()->addSuccessMessage('Gestor vinculado com sucesso!');   
                }
            }else{
                //alterar funcionario
                $formFuncionario->setData($dados);
                if($formFuncionario->isValid()){
                    $serviceFuncionario->update($formFuncionario->getData(), array('id' => $idFuncionario));
                    $this->flashMessenger()->addSuccessMessage('Funcionário alterado com sucesso!');
                }
            }
            return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
        }

        //pesquisar gestores vinculados
        $gestores = $serviceGestor->getGestoresByFuncionario($idFuncionario);
        
        return new ViewModel(array(
            'formFuncionario'   => $formFuncionario,
            'formGestor'        => $formGestor,
            'gestores'          => $gestores,
            'funcionario'       => $funcionario
            ));
    }

    public function deletargestorAction(){
        $idGestor = $this->params()->fromRoute('idGestor');
        $idFuncionario = $this->params()->fromRoute('idFuncionario');

        if($this->getServiceLocator()->get('FuncionarioGestor')->delete(array('id' => $idGestor))){
            $this->flashMessenger()->addSuccessMessage('Gestor desvinculado com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao desvincular gestor, por favor tente novamente!');
        }
        return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
    }

    public function carregarfuncaoAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisa('frmFuncionario', $this->getServiceLocator());
        }
        $funcao = $formFuncionario->setFuncaoBySetor($params->setor);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('funcao' => $funcao));
        return $view;
    }

    public function carregarunidadeAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisa('frmFuncionario', $this->getServiceLocator());
        }
        $unidade = $formFuncionario->setUnidadeByEmpresa($params->empresa);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('unidade' => $unidade));
        return $view;
    }

    public function carregarliderAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        $lideres = $formFuncionario->setLiderByUnidade($params->unidade);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('lider' => $lideres));
        return $view;
    }


}

