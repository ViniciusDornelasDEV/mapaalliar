<?php

namespace Diario\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Diario\Form\Banco as formBanco;

use Diario\Form\BancoAdmin as formBancoAdmin;
use Application\Form\PesquisaAdmin as formPesquisa;

class BancoController extends BaseController
{
    public function indexAction(){
        $this->layout('layout/gestor');
    	$serviceBanco = $this->getServiceLocator()->get('BancoHoras');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $bancos = $serviceBanco->getBancos($funcionario['unidade'])->toArray();
        
        $paginator = new Paginator(new ArrayAdapter($bancos));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'bancos'         => $paginator
                            ));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $formBanco = new formBanco('frmBanco');

        if($this->getRequest()->isPost()){
            $formBanco->setData($this->getRequest()->getPost());
            if($formBanco->isValid()){
                $dados = $formBanco->getData();
                
                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho'])){
                    if(!empty($files['caminho']['name'])){
                        //salvar
                        $usuario = $this->getServiceLocator()->get('session')->read();
                        $funcionario = $this->serviceLocator->get('Funcionario')->getFuncionario($usuario['funcionario']);
                        
                        $dir = 'public/arquivos/bancoHoras';
                        $dados = $this->uploadImagem($files, $dir, $dados);
                        $dados['unidade'] = $funcionario['unidade'];
                        $idBanco = $this->getServiceLocator()->get('BancoHoras')->insert($dados);

                        $this->flashMessenger()->addSuccessMessage('banco de horas inserido com sucesso!');
                        return $this->redirect()->toRoute('listarBancoHoras');
                        
                    }else{
                        $formBanco->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }

            }
        }
        return new ViewModel(array('formBanco' => $formBanco));
    }

    public function deletarAction(){
        $serviceBanco = $this->getServiceLocator()->get('BancoHoras');
        $banco = $serviceBanco->getRecord($this->params()->fromRoute('id'));

        unlink($banco['caminho']);

        if($serviceBanco->delete(array('id' => $banco['id']))){
            $this->flashMessenger()->addSuccessMessage('Banco de horas excluído com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao excluir banco de horas!');
        }

        return $this->redirect()->toRoute('listarBancoHoras');
    }



    //admin
    public function indexadminAction(){
        $serviceBanco = $this->getServiceLocator()->get('BancoHoras');
        
        $formPesquisa = new formPesquisa('frmAusencia', $this->getServiceLocator());

        $formPesquisa = parent::verificarPesquisa($formPesquisa);
        $bancos = $serviceBanco->getBancos(false, $this->sessao->parametros)->toArray();
        
        $paginator = new Paginator(new ArrayAdapter($bancos));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'bancos'         => $paginator,
                                'formPesquisa'   => $formPesquisa
                            ));
    }

    public function novoadminAction(){
        $formBanco = new formBancoAdmin('frmBanco', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formBanco->setData($this->getRequest()->getPost());
            if($formBanco->isValid()){
                $dados = $formBanco->getData();
                
                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho'])){
                    if(!empty($files['caminho']['name'])){
                        //salvar
                        
                        $dir = 'public/arquivos/bancoHoras';
                        $dados = $this->uploadImagem($files, $dir, $dados);
                        $idBanco = $this->getServiceLocator()->get('BancoHoras')->insert($dados);

                        $this->flashMessenger()->addSuccessMessage('banco de horas inserido com sucesso!');
                        return $this->redirect()->toRoute('listarBancoHorasAdmin');
                        
                    }else{
                        $formBanco->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }

            }
        }
        return new ViewModel(array('formBanco' => $formBanco));
    }

    public function deletaradminAction(){
        $serviceBanco = $this->getServiceLocator()->get('BancoHoras');
        $banco = $serviceBanco->getRecord($this->params()->fromRoute('id'));

        unlink($banco['caminho']);

        if($serviceBanco->delete(array('id' => $banco['id']))){
            $this->flashMessenger()->addSuccessMessage('Banco de horas excluído com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao excluir banco de horas!');
        }

        return $this->redirect()->toRoute('listarBancoHorasAdmin');
    }


}

