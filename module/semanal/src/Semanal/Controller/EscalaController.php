<?php

namespace Semanal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Semanal\Form\PesquisarEscala as formPesquisa;

class EscalaController extends BaseController
{


    public function indexAction(){
        $this->layout('layout/gestor');
        
        $formPesquisa = new formPesquisa('frmEscala', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
        	$formPesquisa->setData($this->getRequest()->getPost());
        	if($formPesquisa->isValid()){
        		$dados = $formPesquisa->getData();
        		$usuario = $this->getServiceLocator()->get('session')->read();
        		$funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        		$dados['unidade'] = $funcionario['unidade'];
        		$mesAno = explode('/', $dados['mes_ano']);
        		$dados['mes'] = $mesAno[0];
        		$dados['ano'] = $mesAno[1];
        		
        		//pesquisar escala
                $serviceEscala = $this->getServiceLocator()->get('Escala');
                $escala = $serviceEscala->getRecordFromArray(array(
                        'mes'       =>  $dados['mes'],
                        'ano'       =>  $dados['ano'],
                        'unidade'   =>  $dados['unidade'],
                        'setor'     =>  $dados['setor']
                    ));
        		
        		if($escala){
                    //redir
                    return $this->redirect()->toRoute('novoEscala', array('id' => $escala['id']));
                }else{
                    //insert
                    $idEscala = $serviceEscala->insert($dados);
                    return $this->redirect()->toRoute('novoEscala', array('id' => $idEscala));
                }
        	}
        }

        return new ViewModel(array('formPesquisa' => $formPesquisa));
    }

    public function novoAction(){
        $this->layout('layout/gestor');
        $idEscala = $this->params()->fromRoute('id');
        var_dump($idEscala);
        die();
        return new ViewModel();
    }


}

