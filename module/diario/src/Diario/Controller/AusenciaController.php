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

use Diario\Form\PesquisarAusenciaAdmin as formPesquisaAdmin;
use Diario\Form\AusenciasAdmin as formAusenciaAdmin;

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

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
    	$formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $ausencias = $serviceAusencia->getAusencias($this->sessao->parametros[$rota], $usuario['funcionario'])->toArray();
        
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
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $formAusencia = new formAusencia('frmAusencia', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $files = $this->getRequest()->getfiles()->toArray();
            $dados = $this->getRequest()->getPost();
            $formAusencia->setData($dados);
            if($formAusencia->isValid()){
                $dados = $formAusencia->getData();
                //se tiver imagem fazer upload
                if(isset($files['atestado'])){
                    $dir = 'public/arquivos/ausencias';
                    $dados = $this->uploadImagem($files, $dir, $dados);
                }
                $idAusencia = $this->getServiceLocator()->get('Ausencia')->insert($dados);
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
                $files = $this->getRequest()->getfiles()->toArray();
                $dados = $formAusencia->getData();
                unset($dados['atestado']);

                if(isset($files['atestado']) && !empty($files['atestado']['name'])){
                    $dir = 'public/arquivos/ausencias';
                    $dados = $this->uploadImagem($files, $dir, $dados);
                }

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



    //admin
    public function indexadminAction(){
        $serviceAusencia = $this->getServiceLocator()->get('Ausencia');
        
        $formPesquisa = new formPesquisaAdmin('frmAusencia', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $ausencias = $serviceAusencia->getAusencias($this->sessao->parametros[$rota])->toArray();
        
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

    public function novoadminAction(){
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAusencia = new formAusenciaAdmin('frmAusencia', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formAusencia->setData($this->getRequest()->getPost());
            if($formAusencia->isValid()){
                $dados = $formAusencia->getData();
                
                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['atestado'])){
                    $dir = 'public/arquivos/ausencias';
                    $dados = $this->uploadImagem($files, $dir, $dados);
                }

                $idAusencia = $this->getServiceLocator()->get('Ausencia')->insert($dados);
                $this->flashMessenger()->addSuccessMessage('Ausência incluída com sucesso!');
                return $this->redirect()->toRoute('alterarAusenciaAdmin', array('id' => $idAusencia));
            }
        }
        return new ViewModel(array('formAusencia' => $formAusencia));
    }

    public function alteraradminAction(){
        $idAusencia = $this->params()->fromRoute('id');
        $serviceAusencia = $this->getServiceLocator()->get('Ausencia');

        $usuario = $this->getServiceLocator()->get('session')->read();
        $formAusencia = new formAlterarAusencia('frmAusencia', $this->getServiceLocator());

        $ausencia = $serviceAusencia->getRecord($idAusencia);
        if(!$ausencia){
            $this->flashMessenger()->addWarningMessage('Ausência não encontrada!');
            return $this->redirect()->toRoute('listarAusenciaAdmin');
        }

        $formAusencia->setData($ausencia);
        
        if($this->getRequest()->isPost()){
            $formAusencia->setData($this->getRequest()->getPost());
            if($formAusencia->isValid()){
                $dados = $formAusencia->getData();
                unset($dados['funcionario']);
                unset($dados['atestado']);

                if(isset($files['atestado']) && !empty($files['atestado']['name'])){
                    $dir = 'public/arquivos/ausencias';
                    $dados = $this->uploadImagem($files, $dir, $dados);
                }

                $files = $this->getRequest()->getfiles()->toArray();

                $serviceAusencia->update($dados, array('id' => $idAusencia));
                $this->flashMessenger()->addSuccessMessage('Ausência alterada com sucesso!');
                return $this->redirect()->toRoute('alterarAusenciaAdmin', array('id' => $idAusencia));
            }
        }

        
        return new ViewModel(array(
            'formAusencia'    => $formAusencia
            ));
    }

}

