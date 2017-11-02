<?php

namespace Semanal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Semanal\Form\PesquisarEquipes as formPesquisa;
use Semanal\Form\PesquisarEquipesAdmin as formPesquisaAdmin;

class OrganizacaoequipesController extends BaseController
{


    public function pesquisarAction(){
        $this->layout('layout/gestor');
      	
      	$formPesquisa = new formPesquisa('frmPesquisa');

      	if($this->getRequest()->isPost()){
      		$dados = $this->getRequest()->getPost();
      		$mesAno = explode('/', $dados['mes_ano']);
      		return $this->redirect()->toRoute('visualizarEquipes', array('mes' => $mesAno[0], 'ano' => $mesAno[1]));
      	}

        return new ViewModel(array('formPesquisa' => $formPesquisa));
    }

    public function visualizarAction(){
        $this->layout('layout/gestor');
        $mes = $this->params()->fromRoute('mes');
        $ano = $this->params()->fromRoute('ano');
        
        //pesquisar todas as escalas deste período da unidade logado
        $usuario = $this->getServiceLocator()->get('session')->read();
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $funcionario = $serviceFuncionario->getRecord($usuario['funcionario']);
            
        $escalas = $this->getServiceLocator()->get('Escala')->getEscalasEquipes($mes, $ano, $funcionario['unidade']);
        
        
        $preparedArray = array();
        $idSetor = false;
        $idFuncao = false;
        foreach ($escalas as $escala) {
          if($idSetor != $escala['id_setor']){
            $idSetor = $escala['id_setor'];
            $preparedArray[$idSetor] = array();
            $preparedArray[$idSetor]['nome_setor'] = strtoupper($escala['nome_setor']);
            $preparedArray[$idSetor]['total'] = 0;
            
            $total = $serviceFuncionario->getFuncionariosSetor($idSetor, $funcionario['unidade']); 
            $preparedArray[$idSetor]['total_real'] = $total['total'];
          }

          //inicializar dados da funcao
          if($idFuncao != $escala['id_funcao']){
            $idFuncao = $escala['id_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']] = array();
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['nome_funcao'] = $escala['nome_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['qtd_funcao'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['ccusto'] = $escala['ccusto'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['M'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['T'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['N'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IM'] = $escala['manha'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IT'] = $escala['tarde'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IN'] = $escala['noite'];
            
          }

          $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['qtd_funcao']++;

          if($escala['periodo_trabalho'] == 'Tarde'){
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['T']++;
          }else{
            if($escala['periodo_trabalho'] == 'Noite'){
              $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['N']++;
            }else{
              $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['M']++;
            }
          }

          $preparedArray[$idSetor]['total']++;
        }

        return new ViewModel(array('equipes' => $preparedArray));
    }


    //ADMIN
    public function pesquisaradminAction(){
        
        $formPesquisa = new formPesquisaAdmin('frmPesquisa', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
          $dados = $this->getRequest()->getPost();
          $mesAno = explode('/', $dados['mes_ano']);
          return $this->redirect()->toRoute('visualizarEquipesAdmin', array(
              'mes' => $mesAno[0], 
              'ano' => $mesAno[1], 
              'unidade' => $dados['unidade']
            ));
        }

        return new ViewModel(array('formPesquisa' => $formPesquisa));
    }

    public function visualizaradminAction(){
        $mes = $this->params()->fromRoute('mes');
        $ano = $this->params()->fromRoute('ano');
        $idUnidade = $this->params()->fromRoute('unidade');

        //pesquisar todas as escalas deste período da unidade logado    
        $escalas = $this->getServiceLocator()->get('Escala')->getEscalasEquipes($mes, $ano, $idUnidade);
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        
        $preparedArray = array();
        $idSetor = false;
        $idFuncao = false;
        foreach ($escalas as $escala) {
          if($idSetor != $escala['id_setor']){
            $idSetor = $escala['id_setor'];
            $preparedArray[$idSetor] = array();
            $preparedArray[$idSetor]['nome_setor'] = strtoupper($escala['nome_setor']);
            $preparedArray[$idSetor]['total'] = 0;

            //pesquisar numer ototoal de funcionarios do setor
            $total = $serviceFuncionario->getFuncionariosSetor($idSetor, $idUnidade); 
            $preparedArray[$idSetor]['total_real'] = $total['total'];
          }

          //inicializar dados da funcao
          if($idFuncao != $escala['id_funcao']){
            $idFuncao = $escala['id_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']] = array();
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['nome_funcao'] = $escala['nome_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['qtd_funcao'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['ccusto'] = $escala['ccusto'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['M'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['T'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['N'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IM'] = $escala['manha'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IT'] = $escala['tarde'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['IN'] = $escala['noite'];
            
          }

          $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['qtd_funcao']++;

          if($escala['periodo_trabalho'] == 'Tarde'){
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['T']++;
          }else{
            if($escala['periodo_trabalho'] == 'Noite'){
              $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['N']++;
            }else{
              $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['M']++;
            }
          }

          $preparedArray[$idSetor]['total']++;
        }

        return new ViewModel(array('equipes' => $preparedArray));
    }


}

