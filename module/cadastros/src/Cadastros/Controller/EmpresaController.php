<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Cadastros\Form\PesquisarEmpresa as formPesquisa;
use Cadastros\Form\Empresa as formEmpresa;
use Cadastros\Form\Unidade as formUnidade;


class EmpresaController extends BaseController
{
    private $campos = array(
            'Nome da empresa'           => 'nome',
            'Responsável da empresa'    => 'responsavel',
            'Nome da unidade'           => 'nome_unidade',
            'Responsável da unidade'    => 'nome_responsavel'
        );

    public function indexAction(){
        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $serviceEmpresa = $this->getServiceLocator()->get('Empresa');
            
        $formPesquisa = new formPesquisa('frmEmpresa');

        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        
        $empresas = $serviceEmpresa->getEmpresas($this->sessao->parametros[$rota]);
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $serviceEmpresa->getEmpresasAndUnidades($this->sessao->parametros), 'Empresa');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($empresas->toArray()));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'empresas'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $formEmpresa = new formEmpresa('frmEmpresa');

        if($this->getRequest()->isPost()){
            $formEmpresa->setData($this->getRequest()->getPost());
            if($formEmpresa->isValid()){
                $idEmpresa = $this->getServiceLocator()->get('Empresa')->insert($formEmpresa->getData());
                $this->flashMessenger()->addSuccessMessage('Empresa incluída com sucesso!');
                return $this->redirect()->toRoute('alterarEmpresa', array('id' => $idEmpresa));
            }
        }
        return new ViewModel(array('formEmpresa' => $formEmpresa));
    }

    public function alterarAction(){
        $idEmpresa = $this->params()->fromRoute('id');
        $serviceEmpresa = $this->getServiceLocator()->get('Empresa');
        $formEmpresa = new formEmpresa('frmEmpresa');

        $empresa = $serviceEmpresa->getRecord($idEmpresa);
        if(!$empresa){
            $this->flashMessenger()->addWarningMessage('Empresa não encontrada!');
            return $this->redirect()->toRoute('listarEmpresa');
        }

        $formEmpresa->setData($empresa);
        
        $formUnidade = new formUnidade('frmUnidade');
        $idUnidade = $this->params()->fromRoute('unidade');
        $serviceUnidade = $this->getServiceLocator()->get('Unidade');
        if($idUnidade){
            $formUnidade->setData($serviceUnidade->getRecord($idUnidade));
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            //UNIDADE
            if(isset($dados['unidade'])){
                $formUnidade->setData($dados);
                if($formUnidade->isValid()){
                    $dados = $formUnidade->getData();
                    if($idUnidade){
                        //update
                        $serviceUnidade->update($dados, array('id' => $idUnidade));
                        $this->flashMessenger()->addSuccessMessage('Unidade alterada com sucesso!');
                    }else{
                        //insert
                        $dados['empresa'] = $idEmpresa;
                        $idUnidade = $serviceUnidade->insert($dados);
                        $this->flashMessenger()->addSuccessMessage('Unidade inserida com sucesso!');
                    }
                    return $this->redirect()->toRoute('alterarEmpresa', array('id' => $idEmpresa));
                }
            }else{
                //EMPRESA
                $formEmpresa->setData($dados);
                if($formEmpresa->isValid()){
                    $serviceEmpresa->update($formEmpresa->getData(), array('id' => $idEmpresa));
                    $this->flashMessenger()->addSuccessMessage('Empresa alterada com sucesso!');
                    return $this->redirect()->toRoute('alterarEmpresa', array('id' => $idEmpresa));
                }
            }
        }

        
        $unidades = $serviceUnidade->getRecords($idEmpresa, 'empresa');
        return new ViewModel(array(
            'formEmpresa'   => $formEmpresa,
            'formUnidade'   => $formUnidade,
            'empresa'       => $empresa,
            'unidades'      => $unidades
            ));
    }


}

