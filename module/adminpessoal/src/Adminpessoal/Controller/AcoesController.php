<?php

namespace Adminpessoal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Adminpessoal\Form\PesquisarAcoes as formPesquisa;
use Adminpessoal\Form\Acoes as formAcao;
use Adminpessoal\Form\AlterarAcoes as formAlterarAcao;

use Adminpessoal\Form\PesquisarAcoesAdmin as formPesquisaAdmin;
use Adminpessoal\Form\AcoesAdmin as formAcaoAdmin;

class AcoesController extends BaseController
{
    private $campos = array(
            'Nome da área'              => 'nome_area',
            'Nome do setor'             => 'nome_setor',
            'Nome da função'            => 'nome_funcao',
            'Nome do funcionário'       => 'nome_funcionario',
            'Data da ação'              => 'data',
            'Apontamento'               => 'apontamento',
            'Orientação/ação realizada' =>  'orientacao_acao',
            'Planejamento'              =>  'planejamento'
        );

    

    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceAcoes = $this->getServiceLocator()->get('AcaoDisciplinar');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisa('frmAcoes', $this->getServiceLocator(), $usuario);

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    	$formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $acoes = $serviceAcoes->getAcoes($this->sessao->parametros[$rota], $usuario['funcionario'])->toArray();
        
        foreach ($acoes as $key => $acao) {
            $acoes[$key]['data'] = $formPesquisa->converterData($acao['data']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                /*if(isset($this->sessao->parametros)){
                    $this->sessao->parametros['inicio'] = $formPesquisa->converterData($this->sessao->parametros['inicio']);
                    $this->sessao->parametros['fim'] = $formPesquisa->converterData($this->sessao->parametros['fim']);
                }

                $acoes = $serviceAcoes->getAcoes($this->sessao->parametros)->toArray();*/
                parent::gerarExcel($this->campos, $acoes, 'Ações');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($acoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'acoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAcao = new formAcao('frmAcao', $this->getServiceLocator(), $usuario);

        if($this->getRequest()->isPost()){
            $formAcao->setData($this->getRequest()->getPost());
            if($formAcao->isValid()){
                $idAacao = $this->getServiceLocator()->get('AcaoDisciplinar')->insert($formAcao->getData());
                $this->flashMessenger()->addSuccessMessage('Ação disciplinar incluída com sucesso!');
                return $this->redirect()->toRoute('alterarAcoesDisciplinares', array('id' => $idAacao));
            }
        }
        return new ViewModel(array('formAcao' => $formAcao));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idAcao = $this->params()->fromRoute('id');
        $serviceAcao = $this->getServiceLocator()->get('AcaoDisciplinar');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAcao = new formAlterarAcao('frmAcao', $this->getServiceLocator(), $usuario);

        $acao = $serviceAcao->getRecord($idAcao);
        if(!$acao){
            $this->flashMessenger()->addWarningMessage('Ação disciplinar não encontrada!');
            return $this->redirect()->toRoute('listarAcoesDisciplinares');
        }

        $formAcao->setData($acao);
        
        if($this->getRequest()->isPost()){
            $formAcao->setData($this->getRequest()->getPost());
            if($formAcao->isValid()){
                $dados = $formAcao->getData();
                unset($dados['funcionario']);
                $serviceAcao->update($dados, array('id' => $idAcao));
                $this->flashMessenger()->addSuccessMessage('Ação disciplinar alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAcoesDisciplinares', array('id' => $idAcao));
            }
        }

        
        return new ViewModel(array(
            'formAcao'    => $formAcao
            ));
    }


    //ADMIN
    public function indexadminAction(){
        
        $serviceAcoes = $this->getServiceLocator()->get('AcaoDisciplinar');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisaAdmin('frmAcoes', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $acoes = $serviceAcoes->getAcoes($this->sessao->parametros[$rota])->toArray();
        
        foreach ($acoes as $key => $acao) {
            $acoes[$key]['data'] = $formPesquisa->converterData($acao['data']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                /*if(isset($this->sessao->parametros)){
                    $this->sessao->parametros['inicio'] = $formPesquisa->converterData($this->sessao->parametros['inicio']);
                    $this->sessao->parametros['fim'] = $formPesquisa->converterData($this->sessao->parametros['fim']);
                }

                $acoes = $serviceAcoes->getAcoes($this->sessao->parametros)->toArray();*/
                parent::gerarExcel($this->campos, $acoes, 'Ações');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($acoes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'acoes'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoadminAction(){
        $formAcao = new formAcaoAdmin('frmAcao', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formAcao->setData($this->getRequest()->getPost());
            if($formAcao->isValid()){
                $idAacao = $this->getServiceLocator()->get('AcaoDisciplinar')->insert($formAcao->getData());
                $this->flashMessenger()->addSuccessMessage('Ação disciplinar incluída com sucesso!');
                return $this->redirect()->toRoute('alterarAcoesDisciplinaresAdmin', array('id' => $idAacao));
            }
        }
        return new ViewModel(array('formAcao' => $formAcao));
    }

    public function alteraradminAction(){
        $idAcao = $this->params()->fromRoute('id');
        $serviceAcao = $this->getServiceLocator()->get('AcaoDisciplinar');

        $formAcao = new formAlterarAcao('frmAcao', $this->getServiceLocator());

        $acao = $serviceAcao->getRecord($idAcao);
        if(!$acao){
            $this->flashMessenger()->addWarningMessage('Ação disciplinar não encontrada!');
            return $this->redirect()->toRoute('listarAcoesDisciplinaresAdmin');
        }

        $formAcao->setData($acao);
        
        if($this->getRequest()->isPost()){
            $formAcao->setData($this->getRequest()->getPost());
            if($formAcao->isValid()){
                $dados = $formAcao->getData();
                unset($dados['funcionario']);
                $serviceAcao->update($dados, array('id' => $idAcao));
                $this->flashMessenger()->addSuccessMessage('Ação disciplinar alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAcoesDisciplinaresAdmin', array('id' => $idAcao));
            }
        }

        
        return new ViewModel(array(
            'formAcao'    => $formAcao
            ));
    }

}

