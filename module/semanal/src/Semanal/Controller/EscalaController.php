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
        $usuario = $this->getServiceLocator()->get('session')->read();
        $serviceEscala = $this->getServiceLocator()->get('Escala');
        
        $serviceFuncionario = $this->getServiceLocator()->get('FuncionarioEscala');
        $funcionario = $serviceFuncionario->getRecord($usuario['funcionario']);
        $escala = $serviceEscala->getEscala($idEscala, $funcionario['unidade']);
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if($this->getServiceLocator()->get('EscalasFuncionario')->salvarEscalas($dados, $escala)){
                $this->flashMessenger()->addSuccessMessage('Escala salva com sucesso!');
            }else{
                $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao salvar escala!');
            }
            return $this->redirect()->toRoute('novoEscala', array('id' => $idEscala));
        }

        //escalas do funcionario
        $escalas = $serviceFuncionario->getFuncionariosEscala($escala, $usuario['funcionario'])->toArray();

        $ultimoDia = date("t", mktime(0,0,0,$escala['mes'],'01',$escala['ano']));   
        
        $funcionarios = $serviceFuncionario->getFuncionariosGestor($usuario['funcionario'], $escala['setor']);

        $preparedArray = array();
        foreach ($funcionarios as $funcionario) {
            $preparedArray[$funcionario['id']] = $funcionario;
            for ($dia=1; $dia <= $ultimoDia; $dia++) { 
                $preparedArray[$funcionario['id']]['dias'][$dia] = false;
            }
        }

        //popular escalas marcadas
        foreach ($escalas as $escala2) {
            //descobrir dia
            if(!empty($escala2['data_escala'])){
                $data = explode('-', $escala2['data_escala']);
                $data = intval($data['2']);
                $preparedArray[$escala2['id']]['dias'][$data] = true;
            }
        }

        return new ViewModel(array(
                'escala'        => $escala,
                'escalas'       => $preparedArray
            ));
    }


}

