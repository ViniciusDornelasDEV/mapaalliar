<?php

namespace Diario\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Diario\Form\PesquisarSubstituicao as formPesquisa;
use Diario\Form\Substituicao as formSubstituicao;

use Diario\Form\PesquisarSubstituicaoAdmin as formPesquisaAdmin;
use Diario\Form\SubstituicaoAdmin as formSubstituicaoAdmin;

class SubstituicaoController extends BaseController
{
    private $campos = array(
            'Nome da área'              => 'nome_area',
            'Nome do setor'             => 'nome_setor',
            'Nome da função'            => 'nome_funcao',
            'Nome do funcionário'       => 'nome_funcionario',
            'Data de desligamento'         => 'data_desligamento',
            'Vaga aberta RH'         => 'vaga_rh',
            'Vaga encerrada'               => 'encerrada'
        );

    

    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada');
        
        $formPesquisa = new formPesquisa('frmSubstituicao', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    	$formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $usuario = $this->getServiceLocator()->get('session')->read();
        $substituicoes = $serviceSubstituicao->getSubstituicoes($this->sessao->parametros[$rota], $usuario['funcionario'])->toArray();
        
        foreach ($substituicoes as $key => $substituicao) {
            $substituicoes[$key]['data_desligamento'] = $formPesquisa->converterData($substituicao['data_desligamento']);
            $substituicoes[$key]['vaga_rh'] = $formPesquisa->simNao($substituicao['vaga_rh']);
            $substituicoes[$key]['encerrada'] = $formPesquisa->simNao($substituicao['encerrada']);


        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $substituicoes, 'SubstituiçãoProgramada');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($substituicoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'substituicoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $formSubstituicao = new formSubstituicao('frmSubstituicao', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $formSubstituicao->setData($this->getRequest()->getPost());
            if($formSubstituicao->isValid()){
                $idSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada')->insert($formSubstituicao->getData());
                $this->flashMessenger()->addSuccessMessage('Substituição programada inserida com sucesso!');
                return $this->redirect()->toRoute('alterarSubstituicao', array('id' => $idSubstituicao));
            }
        }
        return new ViewModel(array('formSubstituicao' => $formSubstituicao));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idSubstituicao = $this->params()->fromRoute('id');
        $serviceSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formSubstituicao = new formSubstituicao('frmSubstituicao', $this->getServiceLocator(), $usuario);

        $substituicao = $serviceSubstituicao->getSubstituicao($idSubstituicao);
        if(!$substituicao){
            $this->flashMessenger()->addWarningMessage('Substituição programada não encontrada!');
            return $this->redirect()->toRoute('listarSubstituicao');
        }

        $formSubstituicao->setData($substituicao);
        
        if($this->getRequest()->isPost()){
            $formSubstituicao->setData($this->getRequest()->getPost());
            if($formSubstituicao->isValid()){
                $dados = $formSubstituicao->getData();
                $serviceSubstituicao->update($dados, array('id' => $idSubstituicao));
                $this->flashMessenger()->addSuccessMessage('Substituição programada alterada com sucesso!');
                return $this->redirect()->toRoute('alterarSubstituicao', array('id' => $idSubstituicao));
            }
        }

        
        return new ViewModel(array(
            'formSubstituicao'    => $formSubstituicao
            ));
    }



    //admin
    public function indexadminAction(){
        $serviceSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada');
        
        $formPesquisa = new formPesquisaAdmin('frmSubstituicao', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $substituicoes = $serviceSubstituicao->getSubstituicoes($this->sessao->parametros[$rota])->toArray();
        
        foreach ($substituicoes as $key => $substituicao) {
            $substituicoes[$key]['data_desligamento'] = $formPesquisa->converterData($substituicao['data_desligamento']);
            $substituicoes[$key]['vaga_rh'] = $formPesquisa->simNao($substituicao['vaga_rh']);
            $substituicoes[$key]['encerrada'] = $formPesquisa->simNao($substituicao['encerrada']);


        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $substituicoes, 'SubstituiçãoProgramada');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($substituicoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'substituicoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoadminAction(){
        $formSubstituicao = new formSubstituicaoAdmin('frmSubstituicao', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formSubstituicao->setData($this->getRequest()->getPost());
            if($formSubstituicao->isValid()){
                $idSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada')->insert($formSubstituicao->getData());
                $this->flashMessenger()->addSuccessMessage('Substituição programada inserida com sucesso!');
                return $this->redirect()->toRoute('alterarSubstituicaoAdmin', array('id' => $idSubstituicao));
            }
        }
        return new ViewModel(array('formSubstituicao' => $formSubstituicao));
    }

    public function alteraradminAction(){
        $idSubstituicao = $this->params()->fromRoute('id');
        $serviceSubstituicao = $this->getServiceLocator()->get('SubstituicaoProgramada');

        $formSubstituicao = new formSubstituicaoAdmin('frmSubstituicao', $this->getServiceLocator());

        $substituicao = $serviceSubstituicao->getSubstituicao($idSubstituicao);
        if(!$substituicao){
            $this->flashMessenger()->addWarningMessage('Substituição programada não encontrada!');
            return $this->redirect()->toRoute('listarSubstituicaoAdmin');
        }

        $formSubstituicao->setData($substituicao);
        
        if($this->getRequest()->isPost()){
            $formSubstituicao->setData($this->getRequest()->getPost());
            if($formSubstituicao->isValid()){
                $dados = $formSubstituicao->getData();
                $serviceSubstituicao->update($dados, array('id' => $idSubstituicao));
                $this->flashMessenger()->addSuccessMessage('Substituição programada alterada com sucesso!');
                return $this->redirect()->toRoute('alterarSubstituicaoAdmin', array('id' => $idSubstituicao));
            }
        }

        
        return new ViewModel(array(
            'formSubstituicao'    => $formSubstituicao
            ));
    }

}

