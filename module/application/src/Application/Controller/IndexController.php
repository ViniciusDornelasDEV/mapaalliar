<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

use Cliente\Form\cliente as formCliente;
use Application\Form\Contato as formContato;
use Application\Form\PesquisaDash as formPesquisa;
use Application\Form\PesquisaDashGestor as formPesquisaGestor;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {   
        $formPesquisa = new formPesquisa('frmPesquisa', $this->getServiceLocator());
        $ausencias = false;
        $ferias = false;
        $acoes = false;
        $ajudas = false;
        $empresa = false;
        $unidade = false;

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $formPesquisa->setData($dados);
            $empresa = $this->getServiceLocator()->get('Empresa')->getRecord($dados['empresa']);
            $unidade = $this->getServiceLocator()->get('Unidade')->getRecord($dados['unidade']);

            if($formPesquisa->isValid()){
                $dados = $formPesquisa->getData();
                //pegar período
                if(empty($dados['inicio']) && empty($dados['fim'])){
                    $dataInicio = date('Y-m').'-01';
                    $dataFim = date("t", mktime(0,0,0,date('m'),'01',date('Y')));
                    $dataFim = date('Y-m').'-'.$dataFim;
                    
                }else{
                    $dataInicio = $dados['inicio'];
                    $dataFim = $dados['fim'];
                }
                
                //pesquisar ausencias do mês
                $ausencias = $this->getServiceLocator()
                    ->get('Ausencia')
                    ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id']));
                
                //pesquisar funcionários de férias
                $ferias = $this->getServiceLocator()
                    ->get('Ferias')
                    ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim, 'unidade' => $unidade['id']));

                //pesquisar ações disciplinares
                $acoes = $this->getServiceLocator()
                    ->get('AcaoDisciplinar')
                    ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim, 'unidade' => $unidade['id']));

                //pesquisar ajudas
                $ajudas = $this->getServiceLocator()
                    ->get('Ajuda')
                    ->getAjudas(array('inicio' => $dataInicio, 'fim' => $dataFim, 'unidade' => $unidade['id']));
            }

        }
        
        return new ViewModel(array(
                'ausencias'     =>  $ausencias,
                'ferias'        =>  $ferias,
                'acoes'         =>  $acoes,
                'ajudas'        =>  $ajudas,
                'formPesquisa'  =>  $formPesquisa,
                'empresa'       =>  $empresa,
                'unidade'       =>  $unidade
            ));
    }

    public function indexgestorAction(){
        $this->layout('layout/gestor');
        $formPesquisa = new formPesquisaGestor('formPesquisa');
        //pegar período
        $dataInicio = date('Y-m').'-01';
        $dataFim = date("t", mktime(0,0,0,date('m'),'01',date('Y')));
        $dataFim = date('Y-m').'-'.$dataFim;
        
        if($this->getRequest()->isPost()){
            $formPesquisa->setData($this->getRequest()->getPost());
            if($formPesquisa->isValid()){
                $dados = $formPesquisa->getData();
                $dataInicio = $dados['inicio'];
                $dataFim = $dados['fim'];
            }
        }

        //pegar usuario logado
        $usuario = $this->getServiceLocator()->get('session')->read();

        //pesquisar ausencias do mês
        $ausencias = $this->getServiceLocator()
            ->get('Ausencia')
            ->getAusencias(array('inicio' => $dataInicio, 'fim'    => $dataFim), $usuario['funcionario']);
        
        //pesquisar funcionários de férias
        $ferias = $this->getServiceLocator()
            ->get('Ferias')
            ->getFerias(array('inicio_inicio' => $dataInicio, 'inicio_fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ações disciplinares
        $acoes = $this->getServiceLocator()
            ->get('AcaoDisciplinar')
            ->getAcoes(array('inicio' => $dataInicio, 'fim'    => $dataFim), $usuario['funcionario']);

        //pesquisar ajudas
        $ajudas = $this->getServiceLocator()
            ->get('Ajuda')
            ->getAjudas(array('inicio' => $dataInicio, 'fim' => $dataFim), $usuario['funcionario']);
        
        return new ViewModel(array(
                'ausencias' =>  $ausencias,
                'ferias'    =>  $ferias,
                'acoes'     =>  $acoes ,
                'ajudas'    =>  $ajudas,
                'inicio'    =>  $dataInicio,
                'fim'       =>  $dataFim,
                'formPesquisa'  =>  $formPesquisa  
            ));
    }

    public function downloadAction(){
        $service = $this->params()->fromRoute('service');
        $data = $this->getServiceLocator()->get($service)->getRecord($this->params()->fromRoute('id'));
        $fileName = $data[$this->params()->fromRoute('campo')];
        if(!is_file($fileName)) {
            //Não foi possivel encontrar o arquivo
            return false;
        }
        $fileContents = file_get_contents($fileName);

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Content-Length', strlen($fileContents));
        return $this->response;
    }

    /*public function downloadAction(){
        $sessao = new Container();
        $fileName = $sessao->offsetGet('arquivo');

        die('here!');
        
        if(!is_file($fileName)) {
            //Não foi possivel encontrar o arquivo
            return false;
        }
        $fileContents = file_get_contents($fileName);

        $response = $this->getResponse();
        $response->setContent($fileContents);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'whatever your content type is')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Content-Length', strlen($fileContents));
        return $this->response;
    }*/



}
