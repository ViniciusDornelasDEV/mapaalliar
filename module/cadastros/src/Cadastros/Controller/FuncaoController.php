<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Cadastros\Form\PesquisarFuncao as formPesquisa;
use Cadastros\Form\Funcao as formFuncao;


class FuncaoController extends BaseController
{
    private $campos = array(
            'Nome da área'           => 'nome_area',
            'Nome do setor'          => 'nome_setor',
            'Nome da função'         => 'nome'
        );

    public function indexAction(){
        $serviceFuncao = $this->getServiceLocator()->get('Funcao');
        
        $formPesquisa = new formPesquisa('frmFuncao', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $funcoes = $serviceFuncao->getFuncoes($this->sessao->parametros[$rota])->toArray();
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $funcoes, 'Funções');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($funcoes));
        $paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'funcoes'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $formFuncao = new formFuncao('frmFuncao', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formFuncao->setData($this->getRequest()->getPost());
            if($formFuncao->isValid()){
                $idFuncao = $this->getServiceLocator()->get('Funcao')->insert($formFuncao->getData());
                $this->flashMessenger()->addSuccessMessage('Função incluída com sucesso!');
                return $this->redirect()->toRoute('alterarFuncao', array('id' => $idFuncao));
            }
        }
        return new ViewModel(array('formFuncao' => $formFuncao));
    }

    public function alterarAction(){
        $idFuncao = $this->params()->fromRoute('id');
        $serviceFuncao = $this->getServiceLocator()->get('Funcao');
        $funcao = $serviceFuncao->getFuncao($idFuncao);

        if(!$funcao){
            $this->flashMessenger()->addWarningMessage('Função não encontrada!');
            return $this->redirect()->toRoute('listarFuncao');
        }

        $formFuncao = new formFuncao('frmFuncao', $this->getServiceLocator());
        $formFuncao->setData($funcao);
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            
            $formFuncao->setData($dados);
            if($formFuncao->isValid()){
                $serviceFuncao->update($formFuncao->getData(), array('id' => $idFuncao));
                $this->flashMessenger()->addSuccessMessage('Função alterada com sucesso!');
                return $this->redirect()->toRoute('alterarFuncao', array('id' => $idFuncao));
            }
        }
        
        return new ViewModel(array(
            'formFuncao'   => $formFuncao,
            'funcao'       => $funcao
            ));
    }

    public function carregarsetorAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncao = new formFuncao('frmFuncao', $this->getServiceLocator());
        }else{
            $formFuncao = new formPesquisa('frmFuncao', $this->getServiceLocator());
        }

        if(isset($params->todos) && $params->todos == 'S'){
            $setor = $formFuncao->setSetorByArea($params->area, 'S', $params->unidade);
        }else{
            $setor = $formFuncao->setSetorByArea($params->area, 'N', $params->unidade);
        }
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('setor' => $setor));
        return $view;
    }


}

