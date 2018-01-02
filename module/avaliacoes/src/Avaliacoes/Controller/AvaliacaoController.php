<?php

namespace Avaliacoes\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Avaliacoes\Form\PesquisarPeriodo as formPesquisaPeriodo;
use Avaliacoes\Form\Periodo as formPeriodo;
use Avaliacoes\Form\AlterarPeriodo as formPeriodoAlterar;

use Avaliacoes\Form\Avaliacao as formAvaliacao;
use Avaliacoes\Form\PesquisarAvaliacao as formPesquisaAvaliacao;
use Avaliacoes\Form\PesquisarAvaliacaoAdmin as formPesquisaAvaliacaoAdmin;

class AvaliacaoController extends BaseController
{

    public function indexperiodoAction(){
        $servicePilha = $this->getServiceLocator()->get('PilhaAvaliacoes');
        
        $formPesquisa = new formPesquisaPeriodo('frmPilha', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $periodos = $servicePilha->getAvaliacoes($this->sessao->parametros[$rota])->toArray();
        
        $paginator = new Paginator(new ArrayAdapter($periodos));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'periodos'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoperiodoAction(){
        $formPilha = new formPeriodo('frmPilha', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formPilha->setData($this->getRequest()->getPost());
            if($formPilha->isValid()){
                $idPilha = $this->getServiceLocator()->get('PilhaAvaliacoes')->insert($formPilha->getData());
                $this->flashMessenger()->addSuccessMessage('Período de avaliação incluído com sucesso!');
                return $this->redirect()->toRoute('alterarPeriodo', array('id' => $idPilha));
            }
        }
        return new ViewModel(array('formPilha' => $formPilha));
    }

    public function alterarperiodoAction(){
        $idPilha = $this->params()->fromRoute('id');
        $servicePilha = $this->getServiceLocator()->get('PilhaAvaliacoes');

        $formPilha = new formPeriodoAlterar('frmPilha', $this->getServiceLocator());

        $pilha = $servicePilha->getRecord($idPilha);
        if(!$pilha){
            $this->flashMessenger()->addWarningMessage('Período de avaliação não encontrado!');
            return $this->redirect()->toRoute('listarPeriodo');
        }

        $formPilha->setData($pilha);
        
        if($this->getRequest()->isPost()){
            $formPilha->setData($this->getRequest()->getPost());
            if($formPilha->isValid()){
                $dados = $formPilha->getData();
                unset($dados['setor']);
                $servicePilha->update($dados, array('id' => $idPilha));
                $this->flashMessenger()->addSuccessMessage('Período de avaliação alterado com sucesso!');
                return $this->redirect()->toRoute('alterarPeriodo', array('id' => $idPilha));
            }
        }

        
        return new ViewModel(array(
            'formPilha'    => $formPilha
            ));
    }

    public function indexavaliacaoresponderAction(){
        $this->layout('layout/gestor');

        //pesquisar períodos de avaliações 
        $periodosAbertos = $this->getServiceLocator()->get('PilhaAvaliacoes')->getPeriodosAbertos();
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $avaliacoesAbertas = array();
        $usuario = $this->getServiceLocator()->get('session')->read();
        foreach ($periodosAbertos as $key => $periodo) {
            //pesquisar avaliações por periodo
            $funcionarios = $serviceFuncionario
                                ->getFuncionariosAvaliacaoAberta($periodo, $usuario['funcionario'])
                                ->toArray();

            if(count($funcionarios) > 0){
                $avaliacoesAbertas[$key] = $periodo;
                $avaliacoesAbertas[$key]['funcionarios'] = $funcionarios;
            }
        }

        $formPesquisa = new formPesquisaPeriodo('frmPilha', $this->getServiceLocator());

        return new ViewModel(array(
                    'formPesquisa'  => $formPesquisa,
                    'avaliacoes'    => $avaliacoesAbertas
                    ));
    }

    public function indexavaliacaorespondidaAction(){
        $this->layout('layout/gestor');
        $serviceAvaliacoes = $this->getServiceLocator()->get('Avaliacao');
        
        $formPesquisa = new formPesquisaAvaliacao('frmPesquisa', $this->getServiceLocator());
        
        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $usuario = $this->getServiceLocator()->get('session')->read();
        $avaliacoes = $serviceAvaliacoes->getAvaliacoes($this->sessao->parametros[$rota], $usuario['funcionario'])->toArray();
        
        $paginator = new Paginator(new ArrayAdapter($avaliacoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'avaliacoes'        => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));

    }

    public function novoavaliacaoAction(){
        $this->layout('layout/gestor');
        $idFuncionario = $this->params()->fromRoute('funcionario');
        $idReferencia = $this->params()->fromRoute('referencia');
        
        $formAvaliacao = new formAvaliacao('formAvaliacao');

        $referencia = $this->getServiceLocator()->get('PilhaAvaliacoes')->getPeriodo($idReferencia);
        if(!$referencia){
            $this->flashMessenger()->addWarningMessage('Período de referência não encontrado!');
            return $this->redirect()->toRoute('listarAvaliacoesResponder');
        }

        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getFuncionarioGestor($idFuncionario, $usuario['funcionario']);
        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarAvaliacoesResponder');   
        }


        $serviceAvaliacao = $this->getServiceLocator()->get('Avaliacao');
        $avaliacao = $serviceAvaliacao->getRecordFromArray(array('funcionario' => $funcionario['id'], 'periodo' => $idReferencia));
        if($avaliacao){
            if($avaliacao['enviado'] == 'S'){
                $this->flashMessenger()->addWarningMessage('Já existe uma avaliação para este funcionário!');
                return $this->redirect()->toRoute('listarAvaliacoesResponder');   
            }

            $formAvaliacao->setData($avaliacao);
        }

        if($this->getRequest()->isPost()){
            $dados2 = $this->getRequest()->getPost();
            $formAvaliacao->setData($dados2);
            if($formAvaliacao->isValid()){
                $dados = $formAvaliacao->getData();
                $dados['funcionario'] = $funcionario['id'];
                $dados['periodo'] = $referencia['id'];

                $dados['responsavel'] = $usuario['funcionario'];

                if(isset($dados2['enviar'])){
                    $dados['enviado'] = 'S';
                }
                
                if($avaliacao){
                    $this->flashMessenger()->addSuccessMessage('Avaliação alterada com sucesso!');
                    $serviceAvaliacao->update($dados, array('id' => $avaliacao['id']));
                }else{    
                    $this->flashMessenger()->addSuccessMessage('Avaliação inserida com sucesso!');
                    $serviceAvaliacao->insert($dados);
                }
                return $this->redirect()->toRoute('listarAvaliacoesResponder');
            }
        }

        return new ViewModel(array(
                'formAvaliacao'     =>  $formAvaliacao,
                'funcionario'       =>  $funcionario,
                'referencia'        =>  $referencia
            ));
    }

    public function visualizaravaliacaoAction(){
        $this->layout('layout/gestor');

        $idAvaliacao = $this->params()->fromRoute('id');
        $avaliacao = $this->getServiceLocator()->get('Avaliacao')->getAvaliacao($idAvaliacao);

        if(!$avaliacao){
            $this->flashMessenger()->addWarningMessage('Avaliação não encontrada!');
            return $this->redirect()->toRoute('listarAvaliacoesResponder');
        }

        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getFuncionarioGestor($avaliacao['funcionario'], $usuario['funcionario']);
        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarAvaliacoesResponder');   
        }

        $formAvaliacao = new formAvaliacao('frmAvaliacao');
        $formAvaliacao->setData($avaliacao);
        $formAvaliacao->desabilitarCampos();
        
        return new ViewModel(array(
                'formAvaliacao' =>  $formAvaliacao,
                'avaliacao'     =>  $avaliacao,
                'funcionario'   =>  $funcionario
            ));
    }


    //admin
    public function indexavaliacaorespondidaadminAction(){
        $serviceAvaliacoes = $this->getServiceLocator()->get('Avaliacao');
        
        $formPesquisa = new formPesquisaAvaliacaoAdmin('frmPesquisa', $this->getServiceLocator());
        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);

        $avaliacoes = $serviceAvaliacoes->getAvaliacoes($this->sessao->parametros[$rota])->toArray();
        
        $paginator = new Paginator(new ArrayAdapter($avaliacoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'avaliacoes'        => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));

    }

    public function visualizaravaliacaoadminAction(){
        $idAvaliacao = $this->params()->fromRoute('id');
        $avaliacao = $this->getServiceLocator()->get('Avaliacao')->getAvaliacao($idAvaliacao);

        if(!$avaliacao){
            $this->flashMessenger()->addWarningMessage('Avaliação não encontrada!');
            return $this->redirect()->toRoute('listarAvaliacoesRespondidasAdmin');
        }

        $formAvaliacao = new formAvaliacao('frmAvaliacao');
        $formAvaliacao->setData($avaliacao);
        $formAvaliacao->desabilitarCampos();
        
        return new ViewModel(array(
                'formAvaliacao' =>  $formAvaliacao,
                'avaliacao'     =>  $avaliacao,
            ));
    }

}

