<?php

namespace Diario\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Diario\Form\PesquisarAusencia as formPesquisa;
use Diario\Form\Ausencias as formAusencia;
use Diario\Form\AlterarAusencia as formAlterarAusencia;

class AusenciaController extends BaseController
{
    private $campos = array(
            'Nome da área'              => 'nome_area',
            'Nome do setor'             => 'nome_setor',
            'Nome da função'            => 'nome_funcao',
            'Nome do funcionário'       => 'nome_funcionario',
            'Data da ausência'          => 'data'
        );

    

    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceAusencia = $this->getServiceLocator()->get('Ausencia');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisa('frmAusencia', $this->getServiceLocator(), $usuario);

    	$formPesquisa = parent::verificarPesquisa($formPesquisa);
        $ausencias = $serviceAusencia->getAusencias($this->sessao->parametros)->toArray();
        
        foreach ($ausencias as $key => $ausencia) {
            $ausencias[$key]['data'] = $formPesquisa->converterData($ausencia['data']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $ausencias, 'Ausências');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($ausencias));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'ausencias'         => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAusencia = new formAusencia('frmAusencia', $this->getServiceLocator(), $usuario);

        if($this->getRequest()->isPost()){
            $formAusencia->setData($this->getRequest()->getPost());
            if($formAusencia->isValid()){
                $idAusencia = $this->getServiceLocator()->get('Ausencia')->insert($formAusencia->getData());
                $this->flashMessenger()->addSuccessMessage('Ausência incluída com sucesso!');
                return $this->redirect()->toRoute('alterarAusencia', array('id' => $idAusencia));
            }
        }
        return new ViewModel(array('formAusencia' => $formAusencia));
    }

    public function alterarAction(){
        $this->layout('layout/gestor');
        $idAusencia = $this->params()->fromRoute('id');
        $serviceAusencia = $this->getServiceLocator()->get('Ausencia');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAusencia = new formAlterarAusencia('frmAusencia', $this->getServiceLocator(), $usuario);

        $ausencia = $serviceAusencia->getRecord($idAusencia);
        if(!$ausencia){
            $this->flashMessenger()->addWarningMessage('Ausência não encontrada!');
            return $this->redirect()->toRoute('listarAusencia');
        }

        $formAusencia->setData($ausencia);
        
        if($this->getRequest()->isPost()){
            $formAusencia->setData($this->getRequest()->getPost());
            if($formAusencia->isValid()){
                $dados = $formAusencia->getData();
                unset($dados['funcionario']);
                $serviceAusencia->update($dados, array('id' => $idAusencia));
                $this->flashMessenger()->addSuccessMessage('Ausência alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAusencia', array('id' => $idAusencia));
            }
        }

        
        return new ViewModel(array(
            'formAusencia'    => $formAusencia
            ));
    }

}

