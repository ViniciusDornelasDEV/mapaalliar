<?php

namespace Mensal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Mensal\Form\PesquisarPremissas as formPesquisa;
use Mensal\Form\Nps as formNps;
use Mensal\Form\Evolucao as formEvolucao;
use Mensal\Form\Tme as formTme;
use Mensal\Form\Qmatic as formQmatic;

class PremissasController extends BaseController
{
    public function menuAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $qmatic = $this->getServiceLocator()->get('Qmatic')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('qmatic' => $qmatic));
    }

    public function menuadminAction(){

    }

    public function listarnpsAction(){
        $serviceNps = $this->getServiceLocator()->get('Nps');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $nps = $serviceNps->getDados($this->sessao->parametros)->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($nps));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'nps'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarnpsAction(){
        $idNps = $this->params()->fromRoute('id');
        $serviceNps = $this->getServiceLocator()->get('Nps');
        $formNps = new formNps('frmNps', $this->getServiceLocator());
        $nps = false;
        $operacao = 'Inserir';
        if($idNps){
            $nps = $serviceNps->getDado($idNps);
            $formNps->setData($nps);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formNps->setData($this->getRequest()->getPost());
            if($formNps->isValid()){
                if($idNps){
                    //alterar
                    $serviceNps->update($formNps->getData(), array('id' => $idNps));
                    $this->flashMessenger()->addSuccessMessage('NPS alterado com sucesso!');
                }else{
                    //cadastrar
                    $idNps = $serviceNps->insert($formNps->getData());
                    $this->flashMessenger()->addSuccessMessage('NPS inserido com sucesso!');
                }
                return $this->redirect()->toRoute('cadastrarNps', array('id' => $idNps));
            }
        }

        return new ViewModel(array('form' => $formNps, 'operacao' => $operacao));
    }

    public function visualizarnpsAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $nps = $this->getServiceLocator()->get('Nps')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('nps' => $nps));
    }

    public function listartmaAction(){

    }

    public function cadastrartmaAction(){

    }

    public function visualizartmaAction(){

    }

    public function listarevolucaoAction(){
        $serviceEvolucao = $this->getServiceLocator()->get('Evolucao');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $evolucao = $serviceEvolucao->getDados($this->sessao->parametros)->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($evolucao));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'evolucoes'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarevolucaoAction(){
        $idEvolucao = $this->params()->fromRoute('id');
        $serviceEvolucao = $this->getServiceLocator()->get('Evolucao');
        $formEvolucao = new formEvolucao('frmEvolucao', $this->getServiceLocator());
        $evolucao = false;
        $operacao = 'Inserir';
        if($idEvolucao){
            $evolucao = $serviceEvolucao->getDado($idEvolucao);
            $formEvolucao->setData($evolucao);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formEvolucao->setData($this->getRequest()->getPost());
            if($formEvolucao->isValid()){
                if($idEvolucao){
                    //alterar
                    $serviceEvolucao->update($formEvolucao->getData(), array('id' => $idEvolucao));
                    $this->flashMessenger()->addSuccessMessage('Evolução alterada com sucesso!');
                }else{
                    //cadastrar
                    $idEvolucao = $serviceEvolucao->insert($formEvolucao->getData());
                    $this->flashMessenger()->addSuccessMessage('Evolução inserida com sucesso!');
                }
                return $this->redirect()->toRoute('cadastrarEvolucao', array('id' => $idEvolucao));
            }
        }

        return new ViewModel(array('form' => $formEvolucao, 'operacao' => $operacao));
    }

    public function visualizarevolucaoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $evolucao = $this->getServiceLocator()->get('Evolucao')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('evolucao' => $evolucao));
    }

    public function listartmeAction(){
        $serviceTme = $this->getServiceLocator()->get('Tme');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $tme = $serviceTme->getDados($this->sessao->parametros)->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($tme));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'tme'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrartmeAction(){
        $idTme = $this->params()->fromRoute('id');
        $serviceTme = $this->getServiceLocator()->get('Tme');
        $formTme = new formTme('frmTme', $this->getServiceLocator());
        $tme = false;
        $operacao = 'Inserir';
        if($idTme){
            $tme = $serviceTme->getDado($idTme);
            $formTme->setData($tme);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formTme->setData($this->getRequest()->getPost());
            if($formTme->isValid()){
                $dados = $formTme->getData();

                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho_imagem'])){
                    if(!empty($files['caminho_imagem']['name'])){
                        //salvar
                        $dir = 'public/arquivos/tme';
                        $dados = $this->uploadImagem($files, $dir, $dados);

                        if($idTme){
                            //alterar
                            $serviceTme->update($dados, array('id' => $idTme));
                            $this->flashMessenger()->addSuccessMessage('TME alterada com sucesso!');
                        }else{
                            //cadastrar
                            $idTme = $serviceTme->insert($dados);
                            $this->flashMessenger()->addSuccessMessage('TME inserida com sucesso!');
                        }

                        return $this->redirect()->toRoute('cadastrarTme', array('id' => $idTme));
                        
                    }else{
                        $formTme->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }
            }
        }

        return new ViewModel(array('form' => $formTme, 'operacao' => $operacao, 'tme' => $tme));
    }

    public function visualizartmeAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $tme = $this->getServiceLocator()->get('Tme')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('tme' => $tme));
    }

    public function listarqmaticAction(){
        $serviceQmatic = $this->getServiceLocator()->get('Qmatic');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $qmatic = $serviceQmatic->getDados($this->sessao->parametros)->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($qmatic));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'qmatics'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarqmaticAction(){
        $idQmatic = $this->params()->fromRoute('id');
        $serviceQmatic = $this->getServiceLocator()->get('Qmatic');
        $formQmatic = new formQmatic('frmQmatic', $this->getServiceLocator());
        $qmatic = false;
        $operacao = 'Inserir';
        if($idQmatic){
            $qmatic = $serviceQmatic->getDado($idQmatic);
            $formQmatic->setData($qmatic);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formQmatic->setData($this->getRequest()->getPost());
            if($formQmatic->isValid()){
                $dados = $formQmatic->getData();

                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho_arquivo'])){
                    if(!empty($files['caminho_arquivo']['name'])){
                        //salvar
                        $dir = 'public/arquivos/qmatic';
                        $dados = $this->uploadImagem($files, $dir, $dados);

                        if($idQmatic){
                            //alterar
                            $serviceQmatic->update($dados, array('id' => $idQmatic));
                            $this->flashMessenger()->addSuccessMessage('Qmatic alterada com sucesso!');
                        }else{
                            //cadastrar
                            $idQmatic = $serviceQmatic->insert($dados);
                            $this->flashMessenger()->addSuccessMessage('Qmatic inserida com sucesso!');
                        }

                        return $this->redirect()->toRoute('cadastrarQmatic', array('id' => $idQmatic));
                        
                    }else{
                        $formQmatic->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }
            }
        }

        return new ViewModel(array('form' => $formQmatic, 'operacao' => $operacao, 'qmatic' => $qmatic));
    }

    public function visualizarqmaticAction(){
        
    }


}

