<?php

namespace Mensal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Mensal\Form\PesquisarFerias as formPesquisa;
use Mensal\Form\Ferias as formFerias;
use Mensal\Form\AlterarFerias as formAlterarFerias;

class FeriasController extends BaseController
{
    private $campos = array(
            'Nome da área'          => 'nome_area',
            'Nome do setor'         => 'nome_setor',
            'Nome da função'        => 'nome_funcao',
            'Nome do funcionário'   => 'nome_funcionario',
            'Início'                => 'data_inicio',
            'Fim'                   => 'data_fim'
        );

    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceFerias = $this->getServiceLocator()->get('Ferias');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisa('frmFerias', $this->getServiceLocator(), $usuario);

    	$formPesquisa = parent::verificarPesquisa($formPesquisa);
        $ferias = $serviceFerias->getFerias($this->sessao->parametros)->toArray();
        foreach ($ferias as $key => $feria) {
            $ferias[$key]['data_inicio'] = $formPesquisa->converterData($feria['data_inicio']);
            $ferias[$key]['data_fim'] = $formPesquisa->converterData($feria['data_fim']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $ferias, 'Férias');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($ferias));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'ferias'        => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formFerias = new formFerias('frmFerias', $this->getServiceLocator(), $usuario);

        if($this->getRequest()->isPost()){
            $formFerias->setData($this->getRequest()->getPost());
            if($formFerias->isValid()){
                $idFerias = $this->getServiceLocator()->get('Ferias')->insert($formFerias->getData());
                $this->flashMessenger()->addSuccessMessage('Férias incluída com sucesso!');
                return $this->redirect()->toRoute('alterarFerias', array('id' => $idFerias));
            }
        }
        return new ViewModel(array('formFerias' => $formFerias));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idFerias = $this->params()->fromRoute('id');
        $serviceFerias = $this->getServiceLocator()->get('Ferias');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formFerias = new formAlterarFerias('frmFerias', $this->getServiceLocator(), $usuario);

        $ferias = $serviceFerias->getRecord($idFerias);
        if(!$ferias){
            $this->flashMessenger()->addWarningMessage('Férias não encontrada!');
            return $this->redirect()->toRoute('listarFerias');
        }

        $formFerias->setData($ferias);
        
        if($this->getRequest()->isPost()){
            $formFerias->setData($this->getRequest()->getPost());
            if($formFerias->isValid()){
                $dados = $formFerias->getData();
                unset($dados['funcionario']);
                $serviceFerias->update($dados, array('id' => $idFerias));
                $this->flashMessenger()->addSuccessMessage('Férias alterada com sucesso!');
                return $this->redirect()->toRoute('alterarFerias', array('id' => $idFerias));
            }
        }

        
        return new ViewModel(array(
            'formFerias'    => $formFerias
            ));
    }

    public function deletarferiasAction(){
        $this->layout('layout/gestor');
        $idFerias = $this->params()->fromRoute('id');
        $serviceFerias = $this->getServiceLocator()->get('Ferias');
        
        $ferias = $serviceFerias->getRecord($idFerias);
        if(strtotime($ferias['data_inicio']) < strtotime(date('Y-m-d'))){
            $this->flashMessenger()->addWarningMessage('Não é possível excluir férias em andamento ou já cumprida!');
            return $this->redirect()->toRoute('listarFerias');
        }

        if($serviceFerias->delete(array('id' => $idFerias))){
            $this->flashMessenger()->addSuccessMessage('Férias excluída com sucesso!');
        }else{
            $this->flashMessenger()->adddErrorMessage('Ocorreu algum erro ao excluir férias!');
        }

        return $this->redirect()->toRoute('listarFerias');
    }

    public function carregarfuncionarioAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formFerias = new formFerias('frmFerias', $this->getServiceLocator(), $usuario);

        $funcionarios = $formFerias->setFuncionarioByFuncao($params->funcao, $usuario['funcionario']);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('funcionarios' => $funcionarios));
        return $view;
    }


}

