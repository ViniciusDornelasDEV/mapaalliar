<?php

namespace Adminpessoal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Adminpessoal\Form\PesquisarDesempenho as formPesquisa;
use Adminpessoal\Form\Desempenho as formDesempenho;
use Adminpessoal\Form\AlterarDesempenho as formAlterarDesempenho;

use Adminpessoal\Form\PesquisarDesempenhoAdmin as formPesquisaAdmin;
use Adminpessoal\Form\DesempenhoAdmin as formDesempenhoAdmin;

class DesempenhoController extends BaseController
{
    private $campos = array(
            'Nome da área'              => 'nome_area',
            'Nome do setor'             => 'nome_setor',
            'Nome da função'            => 'nome_funcao',
            'Nome do funcionário'       => 'nome_funcionario',
            'Data da avaliação'         => 'data',
            'Data do próximo feedback'         => 'data_proximo_feedback',
            'Pontos positivos'               => 'pontos_positivos',
            'Pontos a serem desenvolvidos' =>  'pontos_desenvolver',
            'Plano de ação'              =>  'plano_acao'
        );

    

    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisa('frmDesempenho', $this->getServiceLocator(), $usuario);

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    	$formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $avaliacoes = $serviceDesempenho->getAvaliacoes($this->sessao->parametros[$rota], $usuario['funcionario'])->toArray();
        
        foreach ($avaliacoes as $key => $avaliacao) {
            $avaliacoes[$key]['data'] = $formPesquisa->converterData($avaliacao['data']);
            $avaliacoes[$key]['data_proximo_feedback'] = $formPesquisa->converterData($avaliacao['data_proximo_feedback']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $avaliacoes, 'AvaliacoesDesempenho');
            }
        }
        $paginator = new Paginator(new ArrayAdapter($avaliacoes));
        $paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'avaliacoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $formDesempenho = new formDesempenho('frmDesempenho', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $formDesempenho->setData($this->getRequest()->getPost());
            if($formDesempenho->isValid()){
                $idDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho')->insert($formDesempenho->getData());
                $this->flashMessenger()->addSuccessMessage('Avaliação de desempenho inserida com sucesso!');
                return $this->redirect()->toRoute('alterarAvaliacoesDesempenho', array('id' => $idDesempenho));
            }
        }
        return new ViewModel(array('formDesempenho' => $formDesempenho));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idDesempenho = $this->params()->fromRoute('id');
        $serviceDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formDesempenho = new formAlterarDesempenho('frmDesempenho', $this->getServiceLocator(), $usuario);

        $desempenho = $serviceDesempenho->getRecord($idDesempenho);
        if(!$desempenho){
            $this->flashMessenger()->addWarningMessage('Avaliação de desempenho não encontrada!');
            return $this->redirect()->toRoute('listarAvaliacoesDesempenho');
        }

        $formDesempenho->setData($desempenho);
        
        if($this->getRequest()->isPost()){
            $formDesempenho->setData($this->getRequest()->getPost());
            if($formDesempenho->isValid()){
                $dados = $formDesempenho->getData();
                unset($dados['funcionario']);
                $serviceDesempenho->update($dados, array('id' => $idDesempenho));
                $this->flashMessenger()->addSuccessMessage('Avaliação de desempenho alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAvaliacoesDesempenho', array('id' => $idDesempenho));
            }
        }

        
        return new ViewModel(array(
            'formDesempenho'    => $formDesempenho
            ));
    }

    //ADMIN
    public function indexadminAction(){
        $serviceDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho');
        
        $formPesquisa = new formPesquisaAdmin('frmDesempenho', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $avaliacoes = $serviceDesempenho->getAvaliacoes($this->sessao->parametros[$rota])->toArray();
        
        foreach ($avaliacoes as $key => $avaliacao) {
            $avaliacoes[$key]['data'] = $formPesquisa->converterData($avaliacao['data']);
            $avaliacoes[$key]['data_proximo_feedback'] = $formPesquisa->converterData($avaliacao['data_proximo_feedback']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $avaliacoes, 'AvaliacoesDesempenho');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($avaliacoes));
        $paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'avaliacoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoadminAction(){
        $formDesempenho = new formDesempenhoAdmin('frmDesempenho', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formDesempenho->setData($this->getRequest()->getPost());
            if($formDesempenho->isValid()){
                $idDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho')->insert($formDesempenho->getData());
                $this->flashMessenger()->addSuccessMessage('Avaliação de desempenho inserida com sucesso!');
                return $this->redirect()->toRoute('alterarAvaliacoesDesempenhoAdmin', array('id' => $idDesempenho));
            }
        }
        return new ViewModel(array('formDesempenho' => $formDesempenho));
    }

    public function alteraradminAction(){
        $idDesempenho = $this->params()->fromRoute('id');
        $serviceDesempenho = $this->getServiceLocator()->get('AvaliacaoDesempenho');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formDesempenho = new formAlterarDesempenho('frmDesempenho', $this->getServiceLocator());

        $desempenho = $serviceDesempenho->getRecord($idDesempenho);
        if(!$desempenho){
            $this->flashMessenger()->addWarningMessage('Avaliação de desempenho não encontrada!');
            return $this->redirect()->toRoute('listarAvaliacoesDesempenho');
        }

        $formDesempenho->setData($desempenho);
        
        if($this->getRequest()->isPost()){
            $formDesempenho->setData($this->getRequest()->getPost());
            if($formDesempenho->isValid()){
                $dados = $formDesempenho->getData();
                unset($dados['funcionario']);
                $serviceDesempenho->update($dados, array('id' => $idDesempenho));
                $this->flashMessenger()->addSuccessMessage('Avaliação de desempenho alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAvaliacoesDesempenhoAdmin', array('id' => $idDesempenho));
            }
        }

        
        return new ViewModel(array(
            'formDesempenho'    => $formDesempenho
            ));
    }

}

