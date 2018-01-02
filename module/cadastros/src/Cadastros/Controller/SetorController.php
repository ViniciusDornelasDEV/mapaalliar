<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Cadastros\Form\PesquisarSetor as formPesquisa;
use Cadastros\Form\Setor as formSetor;

class SetorController extends BaseController
{
    private $campos = array(
            'Nome da área'           => 'nome_area',
            'Nome do setor'    => 'nome'
        );

    public function indexAction(){
        $serviceSetor = $this->getServiceLocator()->get('Setor');
            
        $formPesquisa = new formPesquisa('frmSetor', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $setores = $serviceSetor->getSetores($this->sessao->parametros[$rota])->toArray();
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $setores, 'Setores');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($setores));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'setores'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $formSetor = new formSetor('frmSetor', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formSetor->setData($this->getRequest()->getPost());
            if($formSetor->isValid()){
                $idSetor = $this->getServiceLocator()->get('Setor')->insert($formSetor->getData());
                $this->flashMessenger()->addSuccessMessage('Setor incluído com sucesso!');
                return $this->redirect()->toRoute('alterarSetor', array('id' => $idSetor));
            }
        }
        return new ViewModel(array('formSetor' => $formSetor));
    }

    public function alterarAction(){
        $idSetor = $this->params()->fromRoute('id');
        $serviceSetor = $this->getServiceLocator()->get('Setor');
        $formSetor = new formSetor('frmSetor', $this->getServiceLocator());

        $setor = $serviceSetor->getRecord($idSetor);
        if(!$setor){
            $this->flashMessenger()->addWarningMessage('Setor não encontrado!');
            return $this->redirect()->toRoute('listarSetor');
        }

        $formSetor->setData($setor);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $formSetor->setData($dados);
            if($formSetor->isValid()){
                $serviceSetor->update($formSetor->getData(), array('id' => $idSetor));
                $this->flashMessenger()->addSuccessMessage('Setor alterado com sucesso!');
                return $this->redirect()->toRoute('alterarSetor', array('id' => $idSetor));
            }
        }

        return new ViewModel(array(
            'formSetor'      => $formSetor,
        ));
    }


}

