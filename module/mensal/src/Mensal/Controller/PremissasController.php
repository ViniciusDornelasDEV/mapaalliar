<?php

namespace Mensal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

use Mensal\Form\PesquisarPremissas as formPesquisa;
use Mensal\Form\Nps as formNps;
use Mensal\Form\NpsAlterar as formNpsAlterar;
use Mensal\Form\Evolucao as formEvolucao;
use Mensal\Form\EvolucaoAlterar as formEvolucaoAlterar;
use Mensal\Form\Tme as formTme;
use Mensal\Form\TmeAlterar as formTmeAlterar;
use Mensal\Form\Qmatic as formQmatic;
use Mensal\Form\QmaticAlterar as formQmaticAlterar;

use Mensal\Form\Tma as formTma;
use Mensal\Form\TmaAndar as formTmaAndar;

use Mensal\Form\Equipes as formEquipe;

use Mensal\Form\Mira as formMira;
use Mensal\Form\MiraAlterar as formMiraAlterar;

class PremissasController extends BaseController
{
    public function menuAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $qmatic = $this->getServiceLocator()->get('Qmatic')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('qmatic' => $qmatic));
    }

    public function menuadminAction(){

    }

    public function listarnpsAction(){
        $serviceNps = $this->getServiceLocator()->get('Nps');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $nps = $serviceNps->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($nps));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'nps'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarnpsAction(){
        $idNps = $this->params()->fromRoute('id');
        $serviceNps = $this->getServiceLocator()->get('Nps');
        if($idNps){
            $formNps = new formNpsAlterar('frmNps', $this->getServiceLocator());
        }else{
            $formNps = new formNps('frmNps', $this->getServiceLocator());
        }
        $nps = false;
        $operacao = 'Inserir';
        if($idNps){
            $nps = $serviceNps->getDado($idNps);
            $formNps->setData($nps);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formNps->setData($this->getRequest()->getPost());
            if($formNps->isValid()){
                if($idNps){
                    //alterar
                    $serviceNps->update($formNps->getData(), array('id' => $idNps));
                    $this->flashMessenger()->addSuccessMessage('NPS alterado com sucesso!');
                    return $this->redirect()->toRoute('cadastrarNps', array('id' => $idNps));
                }else{
                    //cadastrar
                    $idNps = $serviceNps->insert($formNps->getData());
                    $this->flashMessenger()->addSuccessMessage('NPS inserido com sucesso!');
                    return $this->redirect()->toRoute('listarNps');
                }
            }
        }

        return new ViewModel(array('form' => $formNps, 'operacao' => $operacao));
    }

    public function visualizarnpsAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $nps = $this->getServiceLocator()->get('Nps')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('nps' => $nps));
    }

    public function listartmaAction(){
        $serviceTma = $this->getServiceLocator()->get('Tma');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $tma = $serviceTma->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($tma));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'tmas'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrartmaAction(){
        $formTma = new formTma('frmTma', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formTma->setData($this->getRequest()->getPost());
            if($formTma->isValid()){
                $idTma = $this->getServiceLocator()->get('Tma')->insert($formTma->getData());
                return $this->redirect()->toRoute('alterarTma', array('id' => $idTma));
            }
        }

        return new ViewModel(array('formTma' => $formTma));
    }

    public function alterartmaAction(){
        $idTma = $this->params()->fromRoute('id');
        $idAndar = $this->params()->fromRoute('andar');

        $serviceTma = $this->getServiceLocator()->get('Tma');
        $tma = $serviceTma->getDado($idTma);

        if(!$tma){
            $this->flashMessenger()->addWarningMessage('TMA não encontrado!');
            return $this->redirect()->toRoute('listarTma');
        }
        $formTma = new formTma('frmTma', $this->getServiceLocator());
        $formTma->setData($tma);

        $serviceTmaAndar = $this->getServiceLocator()->get('TmaAndar');
        $andar = $serviceTmaAndar->getRecord($idAndar);
        $formAndar = new formTmaAndar('frmAndar');
        if($andar){
            $formAndar->setData($andar);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['unidade'])){
                //tma
                $formTma->setData($dados);
                if($formTma->isValid()){
                    $serviceTma->update($formTma->getData(), array('id' => $idTma));
                    $this->flashMessenger()->addSuccessMessage('Tma alterado com sucesso!');
                    return $this->redirect()->toRoute('alterarTma', array('id' => $idTma));
                }
            }else{
                //andar
                $formAndar->setData($dados);
                if($formAndar->isValid()){
                    $dados = $formAndar->getData();
                    $dados['tma'] = $idTma;
                    if($idAndar){
                        //alterar
                        $serviceTmaAndar->update($dados, array('id' => $idAndar));
                        $this->flashMessenger()->addSuccessMessage('Andar alterado com sucesso!');
                    }else{
                        //inserir
                        $serviceTmaAndar->insert($dados);
                        $this->flashMessenger()->addSuccessMessage('Andar inserido com sucesso!');
                    }
                    return $this->redirect()->toRoute('alterarTma', array('id' => $idTma));
                }
            }
        }
        
        $andares = $serviceTmaAndar->getRecords($idTma, 'tma');
        return new ViewModel(array(
                'formTma'       => $formTma,
                'formAndar'     => $formAndar,
                'andares'       => $andares,
                'tma'           => $tma
            ));
    }

    public function visualizartmaAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);

        $andares = $this->getServiceLocator()->get('Tma')->getAndares($funcionario['unidade'])->toArray();
        
        return new ViewModel(array('andares' => $andares));
    }

    public function deletarandartmaAction(){
        $tma = $this->params()->fromRoute('tma');
        $andar = $this->params()->fromRoute('andar');
        
        if($this->getServiceLocator()->get('TmaAndar')->delete(array('id' => $andar))){
            $this->flashMessenger()->addSuccessMessage('Item excluído com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao deletar, por favor tente novamente!');
        }
        return $this->redirect()->toRoute('alterarTma', array('id' => $tma));
    }


    public function listarevolucaoAction(){
        $serviceEvolucao = $this->getServiceLocator()->get('Evolucao');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $evolucao = $serviceEvolucao->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($evolucao));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'evolucoes'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarevolucaoAction(){
        $idEvolucao = $this->params()->fromRoute('id');
        $serviceEvolucao = $this->getServiceLocator()->get('Evolucao');
        if($idEvolucao){
            $formEvolucao = new formEvolucaoAlterar('frmEvolucao', $this->getServiceLocator());
        }else{
            $formEvolucao = new formEvolucao('frmEvolucao', $this->getServiceLocator());   
        }
        $evolucao = false;
        $operacao = 'Inserir';
        if($idEvolucao){
            $evolucao = $serviceEvolucao->getDado($idEvolucao);
            $formEvolucao->setData($evolucao);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formEvolucao->setData($this->getRequest()->getPost());
            if($formEvolucao->isValid()){
                if($idEvolucao){
                    //alterar
                    $serviceEvolucao->update($formEvolucao->getData(), array('id' => $idEvolucao));
                    $this->flashMessenger()->addSuccessMessage('Evolução alterada com sucesso!');
                    return $this->redirect()->toRoute('cadastrarEvolucao', array('id' => $idEvolucao));
                }else{
                    //cadastrar
                    $idEvolucao = $serviceEvolucao->insert($formEvolucao->getData());
                    $this->flashMessenger()->addSuccessMessage('Evolução inserida com sucesso!');
                    return $this->redirect()->toRoute('listarEvolucao');
                }
            }
        }

        return new ViewModel(array('form' => $formEvolucao, 'operacao' => $operacao));
    }

    public function visualizarevolucaoAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $evolucao = $this->getServiceLocator()->get('Evolucao')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('evolucao' => $evolucao));
    }

    public function listartmeAction(){
        $serviceTme = $this->getServiceLocator()->get('Tme');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $tme = $serviceTme->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($tme));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'tme'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrartmeAction(){
        $idTme = $this->params()->fromRoute('id');
        $serviceTme = $this->getServiceLocator()->get('Tme');
        if($idTme){
            $formTme = new formTmeAlterar('frmTme', $this->getServiceLocator());
        }else{
            $formTme = new formTme('frmTme', $this->getServiceLocator());   
        }

        $tme = false;
        $operacao = 'Inserir';
        if($idTme){
            $tme = $serviceTme->getDado($idTme);
            $formTme->setData($tme);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formTme->setData($this->getRequest()->getPost());
            if($formTme->isValid()){
                $dados = $formTme->getData();

                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho_imagem'])){
                    if(!empty($files['caminho_imagem']['name'])){
                        //salvar
                        $dir = 'public/arquivos/tme';
                        $dados = $this->uploadImagem($files, $dir, $dados);

                        if($idTme){
                            //alterar
                            $serviceTme->update($dados, array('id' => $idTme));
                            $this->flashMessenger()->addSuccessMessage('TME alterada com sucesso!');
                            return $this->redirect()->toRoute('cadastrarTme', array('id' => $idTme));
                        }else{
                            //cadastrar
                            $idTme = $serviceTme->insert($dados);
                            $this->flashMessenger()->addSuccessMessage('TME inserida com sucesso!');
                            return $this->redirect()->toRoute('listarTme');
                        }

                        
                    }else{
                        $formTme->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }
            }
        }

        return new ViewModel(array('form' => $formTme, 'operacao' => $operacao, 'tme' => $tme));
    }

    public function visualizartmeAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);
        $tme = $this->getServiceLocator()->get('Tme')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array('tme' => $tme));
    }

    public function listarqmaticAction(){
        $serviceQmatic = $this->getServiceLocator()->get('Qmatic');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $qmatic = $serviceQmatic->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($qmatic));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'qmatics'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarqmaticAction(){
        $idQmatic = $this->params()->fromRoute('id');
        $serviceQmatic = $this->getServiceLocator()->get('Qmatic');
        if($idQmatic){
            $formQmatic = new formQmaticAlterar('frmQmatic', $this->getServiceLocator());
        }else{
            $formQmatic = new formQmatic('frmQmatic', $this->getServiceLocator());
        }
        $qmatic = false;
        $operacao = 'Inserir';
        if($idQmatic){
            $qmatic = $serviceQmatic->getDado($idQmatic);
            $formQmatic->setData($qmatic);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formQmatic->setData($this->getRequest()->getPost());
            if($formQmatic->isValid()){
                $dados = $formQmatic->getData();

                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['caminho_arquivo'])){
                    if(!empty($files['caminho_arquivo']['name'])){
                        //salvar
                        $dir = 'public/arquivos/qmatic';
                        $dados = $this->uploadImagem($files, $dir, $dados);

                        if($idQmatic){
                            //alterar
                            $serviceQmatic->update($dados, array('id' => $idQmatic));
                            $this->flashMessenger()->addSuccessMessage('Qmatic alterada com sucesso!');
                            return $this->redirect()->toRoute('cadastrarQmatic', array('id' => $idQmatic));
                        }else{
                            //cadastrar
                            $idQmatic = $serviceQmatic->insert($dados);
                            $this->flashMessenger()->addSuccessMessage('Qmatic inserida com sucesso!');
                            return $this->redirect()->toRoute('listarQmatic');
                        }

                        
                    }else{
                        $formQmatic->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }
            }
        }

        return new ViewModel(array('form' => $formQmatic, 'operacao' => $operacao, 'qmatic' => $qmatic));
    }

    public function visualizarqmaticAction(){
        
    }

    public function indexorganizacaoequipesAction(){
        $serviceEquipes = $this->getServiceLocator()->get('Equipe');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $equipes = $serviceEquipes->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($equipes));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'equipes'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoorganizacaoequipesAction(){
        $formEquipe = new formEquipe('formEquipe', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $formEquipe->setData($dados);
            if($formEquipe->isValid()){
                $idEquipe = $this->getServiceLocator()->get('Equipe')->insert($formEquipe->getData());
                $this->flashMessenger()->addSuccessMessage('Parâmetros de equipe inserido com sucesso!');
                return $this->redirect()->toRoute('editarorganizacaoequipes', array('id' => $idEquipe));
            }
        }

        return new ViewModel(array('formEquipe' => $formEquipe));
    }

    public function editarorganizacaoequipesAction(){
        $formEquipe = new formEquipe('formEquipe', $this->getServiceLocator());
        $serviceEquipe = $this->getServiceLocator()->get('Equipe');
        $idEquipe = $this->params()->fromRoute('id');
        $equipe = $serviceEquipe->getDado($idEquipe);
        if(!$equipe){
            $this->flashMessenger()->addWarningMessage('Equipe não encontrada!');
            return $this->redirect()->toRoute('listarPremissasEquipes');
        }

        $formEquipe->setData($equipe);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $formEquipe->setData($dados);
            if($formEquipe->isValid()){
                $serviceEquipe->update($formEquipe->getData(), array('id' => $idEquipe));
                $this->flashMessenger()->addSuccessMessage('Parâmetros de equipe alterado com sucesso!');
                return $this->redirect()->toRoute('editarorganizacaoequipes', array('id' => $idEquipe));
            }
        }

        return new ViewModel(array('formEquipe' => $formEquipe));
    }

    public function listarmiraAction(){
        $serviceMira = $this->getServiceLocator()->get('Mira');
            
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $miras = $serviceMira->getDados($this->sessao->parametros[$rota])->toArray();
        
        
        $paginator = new Paginator(new ArrayAdapter($miras));
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'miras'           => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function cadastrarmiraAction(){
        $idMira = $this->params()->fromRoute('id');
        $serviceMira = $this->getServiceLocator()->get('Mira');
        if($idMira){
            $formMira = new formMiraAlterar('frmMira', $this->getServiceLocator());
        }else{
            $formMira = new formMira('frmMira', $this->getServiceLocator());
        }
        $mira = false;
        $operacao = 'Inserir';
        if($idMira){
            $mira = $serviceMira->getDado($idMira);
            $formMira->setData($mira);
            $operacao = 'Alterar';
        }

        if($this->getRequest()->isPost()){
            $formMira->setData($this->getRequest()->getPost());
            if($formMira->isValid()){

                $dados = $formMira->getData();

                $files = $this->getRequest()->getfiles()->toArray();
                if(isset($files['imagem_1'])){
                    if(!empty($files['imagem_1']['name'])){
                        //salvar
                        $dir = 'public/arquivos/mira';
                        $dados = $this->uploadImagem($files, $dir, $dados);
                        
                        if($idMira){
                            //alterar
                            $serviceMira->update($dados, array('id' => $idMira));
                            $this->flashMessenger()->addSuccessMessage('MIRA alterada com sucesso!');
                            return $this->redirect()->toRoute('cadastrarMira', array('id' => $idMira));
                        }else{
                            //cadastrar
                            $idMira = $serviceMira->insert($dados);
                            $this->flashMessenger()->addSuccessMessage('MIRA inserida com sucesso!');
                            return $this->redirect()->toRoute('listarMira');
                        }

                        
                    }else{
                        $formMira->setData($dados);
                        $this->flashMessenger()->addErrorMessage('Por favor insira um arquivo!');
                    }

                }
            }
        }

        return new ViewModel(array('form' => $formMira, 'operacao' => $operacao, 'mira' => $mira));
    }

    public function visualizarmiraAction(){
        $this->layout('layout/gestor');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($usuario['funcionario']);

        //pesquisar MIRA
        $mira = $this->getServiceLocator()->get('Mira')->getRecord($funcionario['unidade'], 'unidade');

        return new ViewModel(array(
                'mira'  =>  $mira
            ));
    }




}

