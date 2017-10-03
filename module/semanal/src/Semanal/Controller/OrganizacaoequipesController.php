<?php

namespace Semanal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Semanal\Form\PesquisarEquipes as formPesquisa;

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
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
            
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
          }

          //inicializar dados da funcao
          if($idFuncao != $escala['id_funcao']){
            $idFuncao = $escala['id_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']] = array();
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['nome_funcao'] = $escala['nome_funcao'];
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['qtd_funcao'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['M'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['T'] = 0;
            $preparedArray[$idSetor]['funcao'][$escala['id_funcao']]['N'] = 0;
            
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

