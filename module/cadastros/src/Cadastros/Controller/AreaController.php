<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Cadastros\Form\PesquisarArea as formPesquisa;
use Cadastros\Form\Area as formArea;

class AreaController extends BaseController
{
    private $campos = array(
            'Nome da área'           => 'nome',
            'Responsável da área'    => 'responsavel'
        );
    public $rota;

    public function indexAction(){
        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $serviceArea = $this->getServiceLocator()->get('Area');
            
        $formPesquisa = new formPesquisa('frmArea');
        $formPesquisa = $this->verificarPesquisa($formPesquisa, $rota);
        
        $areas = $serviceArea->getAreas($this->sessao->parametros[$rota])->toArray();
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $areas, 'Areas');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($areas));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'areas'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $formArea = new formArea('frmArea');

        if($this->getRequest()->isPost()){
            $formArea->setData($this->getRequest()->getPost());
            if($formArea->isValid()){
                $idArea = $this->getServiceLocator()->get('Area')->insert($formArea->getData());
                $this->flashMessenger()->addSuccessMessage('Área incluída com sucesso!');
                return $this->redirect()->toRoute('alterarArea', array('id' => $idArea));
            }
        }
        return new ViewModel(array('formArea' => $formArea));
    }

    public function alterarAction(){
        $idArea = $this->params()->fromRoute('id');
        $serviceArea = $this->getServiceLocator()->get('Area');
        $formArea = new formArea('frmArea');

        $area = $serviceArea->getRecord($idArea);
        if(!$area){
            $this->flashMessenger()->addWarningMessage('Área não encontrada!');
            return $this->redirect()->toRoute('listarArea');
        }

        $formArea->setData($area);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $formArea->setData($dados);
            if($formArea->isValid()){
                $serviceArea->update($formArea->getData(), array('id' => $idArea));
                $this->flashMessenger()->addSuccessMessage('Área alterada com sucesso!');
                return $this->redirect()->toRoute('alterarArea', array('id' => $idArea));
            }
        }

        return new ViewModel(array(
            'formArea'      => $formArea,
        ));
    }


}

