<?php

namespace Cadastros\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;

use Cadastros\Form\PesquisarFuncionario as formPesquisa;
use Cadastros\Form\Funcionario as formFuncionario;
use Cadastros\Form\VincularGestor as formGestor;
use Cadastros\Form\AlterarFuncionario as formAlterarFuncionario;
use Cadastros\Form\ImportarFuncionario as formImportacao;
use Cadastros\Form\MudarGestor as formMudarGestor;
use Cadastros\Form\MudarLider as formMudarLider;
use Cadastros\Form\AdicionarGestor as formAdicionarGestor;

use Cadastros\Form\PesquisarFuncionarioTi as formPesquisaTi;
use Cadastros\Form\FuncionarioTi as formFuncionarioTi;

class FuncionarioController extends BaseController
{
    private $campos = array(
            'Matrícula'             => 'matricula',
            'Nome'                  => 'nome',
            'Empresa'               => 'nome_empresa',
            'Unidade'               => 'nome_unidade',
            'Área'                  => 'nome_area',
            'Setor'                 => 'nome_setor',
            'Função'                => 'nome_funcao',
            'Tipo de contratação'   => 'tipo_contratacao',
            'Tipo de contrato'      => 'tipo_contrato',
            'Data de início'        => 'data_inicio',
            'Período de trabalho'   => 'periodo_trabalho',
            'Centro de custo'       =>  'ccusto',
            'Descrição c. custo'    =>  'desc_ccusto',
            'Horário'               =>  'horario',
            'Líder imediato'        =>  'nome_lider',
            'Líder'                 =>  'lider',
            'Email'                 =>  'email',
            'Data de nascimento'    =>  'data_nascimento',
            'Data de saída'         =>  'data_saida'
        );

    public function indexAction(){
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        
        $formPesquisa = new formPesquisa('frmFuncionario', $this->getServiceLocator());

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota, array('ativo' => 'S'));
        $funcionarios = $serviceFuncionario->getFuncionarios($this->sessao->parametros[$rota])->toArray();
        
        foreach ($funcionarios as $key => $funcionario) {
            $funcionarios[$key]['data_inicio'] = $formPesquisa->converterData($funcionario['data_inicio']);
            $funcionarios[$key]['data_nascimento'] = $formPesquisa->converterData($funcionario['data_nascimento']);
            $funcionarios[$key]['data_saida'] = $formPesquisa->converterData($funcionario['data_saida']);
            $funcionarios[$key]['lider'] = $formPesquisa->simNao($funcionario['lider']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $funcionarios, 'Funciónários');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($funcionarios));
        $paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'funcionarios'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novoAction(){
        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formFuncionario->setData($this->getRequest()->getPost());
            if($formFuncionario->isValid()){
                $idFuncionario = $this->getServiceLocator()->get('Funcionario')->insert($formFuncionario->getData());
                $this->flashMessenger()->addSuccessMessage('Funcionário incluído com sucesso!');
                return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
            }
        }
        return new ViewModel(array('formFuncionario' => $formFuncionario));
    }

    public function alterarAction(){
        $idFuncionario = $this->params()->fromRoute('id');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $funcionario = $serviceFuncionario->getFuncionario($idFuncionario);

        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarFuncionario');
        }

        $formFuncionario = new formAlterarFuncionario('frmFuncionario', $this->getServiceLocator(), $funcionario);

        $formFuncionario->setData($funcionario);
        $serviceGestor = $this->getServiceLocator()->get('FuncionarioGestor');
        $formGestor = new formGestor('frmGestor', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['gestor'])){
                //salvar gestor
                $formGestor->setData($dados);
                if($formGestor->isValid()){
                    $dados = $formGestor->getData();
                    $dados['funcionario'] = $idFuncionario;
                    $serviceGestor->insert($dados);
                    $this->flashMessenger()->addSuccessMessage('Gestor vinculado com sucesso!');   
                }
            }else{
                //alterar funcionario
                $formFuncionario->setData($dados);
                if($formFuncionario->isValid()){
                    $serviceFuncionario->update($formFuncionario->getData(), array('id' => $idFuncionario));
                    $this->flashMessenger()->addSuccessMessage('Funcionário alterado com sucesso!');
                }
            }
            return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
        }

        //pesquisar gestores vinculados
        $gestores = $serviceGestor->getGestoresByFuncionario($idFuncionario);
        
        $usuario = false;
        if($funcionario['lider'] == 'S'){
            //pesquisar usuário
            $usuario = $this->getServiceLocator()->get('Usuario')->getRecord($funcionario['id'], 'funcionario');
            if(!$usuario){
                $usuario = 'I';
            }
        }

        return new ViewModel(array(
            'formFuncionario'   => $formFuncionario,
            'formGestor'        => $formGestor,
            'gestores'          => $gestores,
            'funcionario'       => $funcionario,
            'usuario'           => $usuario
            ));
    }

    public function deletargestorAction(){
        $idGestor = $this->params()->fromRoute('idGestor');
        $idFuncionario = $this->params()->fromRoute('idFuncionario');

        if($this->getServiceLocator()->get('FuncionarioGestor')->delete(array('id' => $idGestor))){
            $this->flashMessenger()->addSuccessMessage('Gestor desvinculado com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao desvincular gestor, por favor tente novamente!');
        }
        return $this->redirect()->toRoute('alterarFuncionario', array('id' => $idFuncionario));
    }

    public function trocargestorAction(){
        $formGestor = new formMudarGestor('frmGestor', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formGestor->setData($this->getRequest()->getPost());
            if($formGestor->isValid()){
                if($this->getServiceLocator()->get('Funcionario')->trocarGestor($formGestor->getData())){
                    //sucesso!
                    $this->flashMessenger()->addSuccessMessage('Gestor alterado com sucesso!');
                }else{
                    //erro!
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao alterar gestor!');
                }
                return $this->redirect()->toRoute('trocarGestor');
            }
        }

        return new ViewModel(array(
                'formGestor'    =>  $formGestor
            ));
    }

    public function adicionargestorAction(){
        $formGestor = new formAdicionarGestor('frmGestor', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formGestor->setData($this->getRequest()->getPost());
            if($formGestor->isValid()){
                if($this->getServiceLocator()->get('Funcionario')->adicionarGestor($formGestor->getData())){
                    //sucesso!
                    $this->flashMessenger()->addSuccessMessage('Gestor adicionado com sucesso!');
                }else{
                    //erro!
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao adicionar gestor!');
                }
                return $this->redirect()->toRoute('adicionarGestor');
            }
        }

        return new ViewModel(array('formGestor' => $formGestor));
    }

    public function trocarliderAction(){
        $formLider = new formMudarLider('frmLider', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $formLider->setData($this->getRequest()->getPost());
            if($formLider->isValid()){
                $dados = $formLider->getData();
                if($this->getServiceLocator()->get('Funcionario')->update(
                    array('lider_imediato' => $dados['novo_lider']),
                    array('lider_imediato' => $dados['lider_imediato'])
                    )){
                    //sucesso!
                    $this->flashMessenger()->addSuccessMessage('Líder imediato alterado com sucesso!');
                }else{
                    //erro!
                    $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao alterar líder imediato!');
                }
                return $this->redirect()->toRoute('trocarLider');
            }
        }

        return new ViewModel(array(
                'formLider'    =>  $formLider
            ));
    }

    public function carregartrocagestorAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        $formGestor = new formMudarGestor('frmGestor', $this->getServiceLocator());
        $lideres = $formGestor->setLiderByLider($params->gestor);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('lider' => $lideres));
        return $view;
    }

    public function carregarfuncaoAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisa('frmFuncionario', $this->getServiceLocator());
        }
        
        $funcao = $formFuncionario->setFuncaoBySetor($params->setor, $params->unidade);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('funcao' => $funcao));
        return $view;
    }

    public function carregarunidadeAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisa('frmFuncionario', $this->getServiceLocator());
        }

        $unidade = $formFuncionario->setUnidadeByEmpresa($params->empresa, $params->todos);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('unidade' => $unidade));
        return $view;
    }

    public function carregarareaAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisa('frmFuncionario', $this->getServiceLocator());
        }

        $area = $formFuncionario->setAreaByUnidade($params->idUnidade);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('area' => $area));
        return $view;
    }

    public function carregarliderAction(){
        $params = $this->getRequest()->getPost();
        //instanciar form
        $formFuncionario = new formFuncionario('frmFuncionario', $this->getServiceLocator());
        $lideres = $formFuncionario->setLiderByUnidade($params->unidade);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('lider' => $lideres));
        return $view;
    }

    public function importarfuncionariosAction(){
        $formImportacao = new formImportacao('frmImportar', $this->getServiceLocator());

        if($this->getRequest()->isPost()){
            $files = $this->getRequest()->getfiles()->toArray();
            $dados = $this->getRequest()->getPost();

            if(isset($files['arquivo'])){
                if(!empty($files['arquivo']['name'])){
                    //salvar
                    $dir = 'public/arquivos/funcionarios';
                    $dados = $this->uploadImagem($files, $dir, $dados);
                    
                    $inputFileType = \PHPExcel_IOFactory::identify($dados['arquivo']);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($dados['arquivo']);
                    $sheet = $objPHPExcel->getSheet(0); 
                    $highestRow = $sheet->getHighestRow(); 

                    $res = $this->getServiceLocator()->get('Funcionario')->importar($sheet, $highestRow, $dados);
                    if($res){
                        $this->flashMessenger()->addSuccessMessage('Funcionários importados com sucesso!');
                    }else{
                        $this->flashMessenger()->addWarningMessage('Ocorreu algum erro ao importar funcionários, por favor tente novamente!');
                    }
                    return $this->redirect()->toRoute('importarFuncionario');
                }
            }
        }

        return new ViewModel(array('form' => $formImportacao));
    }


    //Perfil de TI
    public function indextiAction(){
        $this->layout('layout/layoutti');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formPesquisa = new formPesquisaTi('frmFuncionario', $this->getServiceLocator(), $usuario);

        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        
        $formPesquisa = parent::verificarPesquisa($formPesquisa, $rota);
        $funcionarios = $serviceFuncionario->getFuncionariosTi($this->sessao->parametros[$rota], $usuario)->toArray();
        
        foreach ($funcionarios as $key => $funcionario) {
            $funcionarios[$key]['data_inicio'] = $formPesquisa->converterData($funcionario['data_inicio']);
            $funcionarios[$key]['data_nascimento'] = $formPesquisa->converterData($funcionario['data_nascimento']);
            $funcionarios[$key]['data_saida'] = $formPesquisa->converterData($funcionario['data_saida']);
            $funcionarios[$key]['lider'] = $formPesquisa->simNao($funcionario['lider']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados->exportar)){
                parent::gerarExcel($this->campos, $funcionarios, 'Funcionários');
            }
        }
        
        $paginator = new Paginator(new ArrayAdapter($funcionarios));
        $paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'funcionarios'      => $paginator,
                                'formPesquisa'  => $formPesquisa
                            ));
    }

    public function novotiAction(){
        $this->layout('layout/layoutti');
        $usuario = $this->getServiceLocator()->get('session')->read();
        $formFuncionario = new formFuncionarioTi('frmFuncionario', $this->getServiceLocator(), $usuario);

        if($this->getRequest()->isPost()){
            $formFuncionario->setData($this->getRequest()->getPost());
            if($formFuncionario->isValid()){
                $dados = $formFuncionario->getData();

                //verificar se a unidade informada pertence ao funcionário de TI
                $unidade = $this->getServiceLocator()
                    ->get('UsuarioUnidade')
                    ->getRecordFromArray(array('usuario' => $usuario['id'], 'unidade' => $dados['unidade']));

                if(!$unidade){
                    $this->flashMessenger()->addWarningMessage('Unidade informada é inválida!');
                    return $this->redirect()->toRoute('novoFuncionarioTi');
                }
                $idFuncionario = $this->getServiceLocator()->get('Funcionario')->insert($dados);
                $this->flashMessenger()->addSuccessMessage('Funcionário incluído com sucesso!');
                return $this->redirect()->toRoute('alterarFuncionarioTi', array('id' => $idFuncionario));
            }
        }
        return new ViewModel(array('formFuncionario' => $formFuncionario));
    }

    public function alterartiAction(){
        $this->layout('layout/layoutti');
        $idFuncionario = $this->params()->fromRoute('id');
        $serviceFuncionario = $this->getServiceLocator()->get('Funcionario');
        $funcionario = $serviceFuncionario->getFuncionario($idFuncionario);

        if(!$funcionario){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarFuncionarioTi');
        }

        //verificar se funcionário é da empresa do tecnico de TI
        $usuario = $this->getServiceLocator()->get('session')->read();
        $serviceFuncionarioUnidade = $this->getServiceLocator()
                    ->get('UsuarioUnidade');
        $unidade = $serviceFuncionarioUnidade
            ->getRecordFromArray(array('usuario' => $usuario['id'], 'unidade' => $funcionario['unidade']));

        if(!$unidade){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarFuncionarioTi');
        }


        $formFuncionario = new formFuncionarioTi('frmFuncionario', $this->getServiceLocator(), $usuario);

        $formFuncionario->setData($funcionario);
        $serviceGestor = $this->getServiceLocator()->get('FuncionarioGestor');
        $formGestor = new formGestor('frmGestor', $this->getServiceLocator(), $funcionario);

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            if(isset($dados['gestor'])){
                //salvar gestor
                $formGestor->setData($dados);
                if($formGestor->isValid()){
                    $dados = $formGestor->getData();
                    $dados['funcionario'] = $idFuncionario;
                    $serviceGestor->insert($dados);
                    $this->flashMessenger()->addSuccessMessage('Gestor vinculado com sucesso!');   
                }
            }else{
                //alterar funcionario
                $formFuncionario->setData($dados);
                if($formFuncionario->isValid()){
                    $unidade = $serviceFuncionarioUnidade
                    ->getRecordFromArray(array('usuario' => $usuario['id'], 'unidade' => $dados['unidade']));

                    if(!$unidade){
                        $this->flashMessenger()->addWarningMessage('Unidade informada é inválida!');
                        return $this->redirect()->toRoute('listarFuncionarioTi');
                    }
                    $serviceFuncionario->update($formFuncionario->getData(), array('id' => $idFuncionario));
                    $this->flashMessenger()->addSuccessMessage('Funcionário alterado com sucesso!');
                }
            }
            return $this->redirect()->toRoute('alterarFuncionarioTi', array('id' => $idFuncionario));
        }

        //pesquisar gestores vinculados
        $gestores = $serviceGestor->getGestoresByFuncionario($idFuncionario);
        
        $usuario = false;
        if($funcionario['lider'] == 'S'){
            //pesquisar usuário
            $usuario = $this->getServiceLocator()->get('Usuario')->getRecord($funcionario['id'], 'funcionario');
            if(!$usuario){
                $usuario = 'I';
            }
        }

        return new ViewModel(array(
            'formFuncionario'   => $formFuncionario,
            'formGestor'        => $formGestor,
            'gestores'          => $gestores,
            'funcionario'       => $funcionario,
            'usuario'           => $usuario
            ));
    }

    public function deletargestortiAction(){
        $idGestor = $this->params()->fromRoute('idGestor');
        $idFuncionario = $this->params()->fromRoute('idFuncionario');
        $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($idFuncionario);
        $usuario = $this->getServiceLocator()->get('session')->read();
        $unidade = $this->getServiceLocator()
                    ->get('UsuarioUnidade')
                    ->getRecordFromArray(array('usuario' => $usuario['id'], 'unidade' => $funcionario['unidade']));
        if(!$unidade){
            $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
            return $this->redirect()->toRoute('listarFuncionarioTi');
        }

        if($this->getServiceLocator()->get('FuncionarioGestor')->delete(array('id' => $idGestor))){
            $this->flashMessenger()->addSuccessMessage('Gestor desvinculado com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Ocorreu algum erro ao desvincular gestor, por favor tente novamente!');
        }
        return $this->redirect()->toRoute('alterarFuncionarioTi', array('id' => $idFuncionario));
    }

    public function carregarunidadetiAction(){
        $params = $this->getRequest()->getPost();
        $usuario = $this->getServiceLocator()->get('session')->read();
        //instanciar form
        if($params->tipo == 'C'){
            $formFuncionario = new formFuncionarioTi('frmFuncionario', $this->getServiceLocator());
        }else{
            $formFuncionario = new formPesquisaTi('frmFuncionario', $this->getServiceLocator(), $usuario);
        }

        $unidade = $formFuncionario->setUnidadeByEmpresaTi($params->empresa, $usuario);
        
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariables(array('unidade' => $unidade));
        return $view;
    }


}

