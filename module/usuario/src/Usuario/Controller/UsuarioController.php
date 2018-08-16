<?php

namespace Usuario\Controller;

use Application\Controller\BaseController;
use Usuario\Form\Login as loginForm;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Crypt\Password\Bcrypt;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;

use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Session\Container;
use Usuario\Form\Usuario as usuarioForm;
use Usuario\Form\AlterarUsuario as alterarUsuarioForm;
use Usuario\Form\PesquisaUsuario as pesquisaForm;
use Usuario\Form\AlterarSenha as alterarSenhaForm;
use Usuario\Form\RecuperarSenha as novaSenhaForm;
use Usuario\Form\Registrese as formRegistro;
use Usuario\Form\VincularEmpresa as formEmpresa;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Mail;

class UsuarioController extends BaseController
{
    public function loginAction()
    {
        $this->layout('layout/login');
        $form = new loginForm();
        
        //Log in
        $request = $this->getRequest();
        $post = $request->getPost();
        
        if(!isset($post->login)) {
            if(isset($_POST['login'])){
                $post = $_POST;
            }
        }
        
        if ($request->isPost()) {
            $form->setData($post);
            if ($form->isValid()) {

                $data = $form->getData();

                // Configure the instance with constructor parameters...

                $authAdapter = new AuthAdapter($this->getServiceLocator()
                                    ->get('db_adapter_main'), 'tb_usuario', 'login', 'senha', 
                                    function($dbCredential, $requestCredential) {
                                        $bcrypt = new Bcrypt();
                                        return $bcrypt->verify($requestCredential, $dbCredential);
                });
                
                //apenas ativo = S
                $select = $authAdapter->getDbSelect();
                $select->where('ativo = "S"');

                $authAdapter
                        ->setTableName('tb_usuario')
                        ->setIdentityColumn('login')
                        ->setCredentialColumn('senha');

                $authAdapter
                        ->setIdentity($data['login'])
                        ->setCredential($data['password']);    

                $result = $authAdapter->authenticate()->getCode();    
                
                $session = $this->getServiceLocator()->get('session'); 
               
                
                if ($result === Result::SUCCESS) {
                    //remember me?
                    if(isset($post->remember_me) && $post->remember_me == 1) {                     
                        $defaultNamespace = new SessionManager();
                        $defaultNamespace->rememberMe();
                    }            
                    
                    $user = (array)$authAdapter->getResultRowObject();  
                    $user['funcionario_original'] = $user['funcionario'];  
                    $session->write($user);                                       

                    //Create acl config
                    $sessao = new Container();
                    $sessao->acl = $this->criarAutorizacao();
                    
                    if($user['primeiro_login'] == 'S' && $user['id_usuario_tipo'] != 1){
                        return $this->redirect()->toRoute('alterarSenha');
                    }

                    $this->getServiceLocator()->get('Usuario')->logSistema($user, 'login', false);
                    if($user['id_usuario_tipo'] == 3){
                        return $this->redirect()->toRoute('listarFuncionarioTi');
                    }

                    if($user['id_usuario_tipo'] == 2){
                        if(empty($user['funcionario'])){
                            $session->clear();
                            $this->flashMessenger()->addWarningMessage('Gestor sem funcionário vinculado, por favor contate o administrador!');
                            return $this->redirect()->toRoute('login');

                        }
                        return $this->redirect()->toRoute('homeGestor');
                    }


                    return $this->redirect()->toRoute('home');
                    
                } else {
                	//form invalido
                    $session->clear();
                    $this->flashMessenger()->addWarningMessage('Login ou senha inválidos!');
                    return $this->redirect()->toRoute('login');
                }
            }
        }        

        $sessao = new Container();
        if(isset($sessao->postBlog->nome) && isset($sessao->postBlog->email)){
            //pesquisar se já existe visitante com este email

            //se existir 
        }else{
            if(isset($sessao->postBlog->tipoLogin)){
                if($sessao->postBlog->tipoLogin == 'F'){
                    //Login com o facebook
                
                }

                if($sessao->postBlog->tipoLogin == 'G'){
                    //Login com o google
                
                }

                if($sessao->postBlog->tipoLogin == 'E'){
                    //Login com email
                
                }

            }else{
                //você precisa se registrar ou informar nome e email para inserir um comentário

            }
        }

        return new ViewModel(array('form' => $form));

    }

    public function logoutAction() {
        $session = $this->getServiceLocator()->get('session');  
        $defaultNamespace = new SessionManager();
        $defaultNamespace->destroy();
        $session->clear();
        return $this->redirect()->toRoute('login');
    }

    public function alterarsenhaAction() {
        $this->layout('layout/login');
        $form = new alterarSenhaForm('frmUsuario');
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $form->setData($dados);
            if($form->isValid()){
                //Pegar usuário logado
                $serviceUsuario = $this->getServiceLocator()->get('Usuario');
                $usuario = $this->getServiceLocator()->get('session')->read();
                $bcrypt = new bcrypt();                

                if(!$bcrypt->verify($dados['senha_atual'], $usuario['senha'])){
                    $this->flashMessenger()->addWarningMessage('Senha atual não confere!');
                    return $this->redirect()->toRoute('alterarSenha');
                }
                //alterar senha
                $usuario['senha'] = $bcrypt->create($dados['senha']);
                $usuario['primeiro_login'] = 'N';
                if($serviceUsuario->update($usuario, array('id' => $usuario['id']))){
                    $this->flashMessenger()->addSuccessMessage('Senha alterada com sucesso!');  
                    return $this->redirect()->toRoute('logout');
                }else{
                    $this->flashMessenger()->addErrorMessage('Falha ao alterar senha!');
                    return $this->redirect()->toRoute('alterarSenha');
                }
                
            }
        }
        return new ViewModel(array('form' => $form));
    }

    public function recuperarsenhaAction(){
        $this->layout('layout/site');
        $form = new novaSenhaForm('frmRecuperaSenha');
        
        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost();
            $form->setData($dados);
            if($form->isValid()){
                $bcrypt = new bcrypt();                
                //alterar senha
                $serviceUsuario = $this->getServiceLocator()->get('Usuario');
                $novaSenha = 'otp'.date('Y+m()ds').rand(0, 99999);
                $usuario = array('senha' => $bcrypt->create($novaSenha));

                if($serviceUsuario->update($usuario, array('login' => $dados->login))){
                    $this->flashMessenger()->addSuccessMessage('Verifique a nova senha em sua conta de e-mail!');  
                    $mailer = $this->getServiceLocator()->get('mailer');
                    $mailer->mailUser($dados->login, 'Recuperar senha', 'Sua nova senha de acesso ao sistema é '.$novaSenha);
                    return $this->redirect()->toRoute('login');
                }else{
                    $this->flashMessenger()->addErrorMessage('Falha ao recuperar senha!');
                    return $this->redirect()->toRoute('recuperarSenha');
                }

                
            }
            
        }
        
        

        return new ViewModel(array('form' => $form));
    }

    private function criarAutorizacao() {
        //pesquisar perfil de usuário
        $serviceUsuario = $this->getServiceLocator()->get('UsuarioTipo');
        $perfilUsuario = $serviceUsuario->getRecord($serviceUsuario->getIdentity('id_usuario_tipo'));
        
        //criando papel do usuário
        $acl = new Acl();
        $papel = new Role($perfilUsuario['perfil']);
        $acl->addRole($papel);

        //definindo recursos existentes no sistema
        $serviceRecurso = $this->getServiceLocator()->get('Recurso');
        $recursos = $serviceRecurso->fetchAll();
        foreach ($recursos as $resource) {
            $acl->addResource(new Resource($resource->nome));
        }

        //Adicionar permissões
        $recursosUsuario = $serviceRecurso->getRecursosByTipoUsuario(array('usuario_tipo' => $perfilUsuario['id']));
        foreach ($recursosUsuario as $resource) {
            $acl->allow($perfilUsuario['perfil'], $resource->nome);
        }
        return $acl;
    }

    public function indexAction(){
        $rota = $this->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        $formPesquisa = new pesquisaForm('frmPesquisa', $this->getServiceLocator());
        $formPesquisa = $this->verificarPesquisa($formPesquisa, $rota);
        
        
        $serviceUsuario = $this->getServiceLocator()->get('Usuario');
        $usuarios = $serviceUsuario->getUsuariosByParams($this->sessao->parametros[$rota]);

        $Paginator = new Paginator(new ArrayAdapter($usuarios->toArray()));
        $Paginator->setCurrentPageNumber($this->sessao->page[$rota]);
        $Paginator->setItemCountPerPage(10);
        $Paginator->setPageRange(5);
        
        return new ViewModel(array(
                                'usuarios'      => $Paginator, 
                                'formPesquisa'   => $formPesquisa,
                            ));
    }

    public function novoAction(){
        $usuario = $this->getServiceLocator()->get('session')->read();
        if($usuario['id_usuario_tipo'] == 3){
            $this->layout('layout/layoutti');
        }

        $idFuncionario = $this->params()->fromRoute('funcionario');
        if($idFuncionario){
            $funcionario = $this->getServiceLocator()->get('Funcionario')->getRecord($idFuncionario);
            if(!$funcionario){
                $this->flashMessenger()->addWarningMessage('Funcionário não encontrado!');
                return $this->redirect()->toRoute('usuario');
            }

            if($funcionario['lider'] == 'N'){
                $this->flashMessenger()->addWarningMessage('Apenas líder pode realizar login no sistema!');
                return $this->redirect()->toRoute('usuario');
            }
        }
        $formUsuario = new usuarioForm('frmUsuario', $this->getServiceLocator(), $idFuncionario);
        //caso venha um post salvar
        if($this->getRequest()->isPost()){
            //salvar e enviar para  edit
            $dados = $this->getRequest()->getPost();
            $serviceUsuario = $this->getServiceLocator()->get('Usuario');
            
            //validar form
            $formUsuario->setData($dados);
            if($formUsuario->isValid()){  
                $bcrypt = new Bcrypt();
                $dados = $formUsuario->getData();
                $dados['senha'] = $bcrypt->create($dados['senha']);
                if($idFuncionario){
                    $dados['id_usuario_tipo'] = 2;
                    $dados['funcionario'] = $idFuncionario;
                }

                $result = $serviceUsuario->insert($dados);
                if($result){
                    
                    //sucesso criar mensagem e redir para edit
                    $this->flashMessenger()->addSuccessMessage('Usuário inserido com sucesso!');                
                    return $this->redirect()->toRoute('usuarioAlterar', array('id' => $result));
                }else{
                    //falha, exibir mensagem
                    $this->flashMessenger()->addErrorMessage('Falha ao inserir usuário!');
                }
            }

        }

        return new ViewModel(array('formUsuario' => $formUsuario, 'usuario' => $usuario));
    }


    public function alterarAction(){
        $usuarioLogado = $this->getServiceLocator()->get('session')->read();
        if($usuarioLogado['id_usuario_tipo'] == 3){
            $this->layout('layout/layoutti');
        }
        //Pesquisar cliente
        $idUsuario = $this->params()->fromRoute('id');
        $serviceUsuario = $this->getServiceLocator()->get('Usuario');
        $usuario = $serviceUsuario->getRecordFromArray(array('id' => $idUsuario));

        //Popular form
        $formUsuario = new alterarUsuarioForm('frmUsuario', $this->getServiceLocator(), $usuario);
        //$formUsuario->remove('senha');
        //$formUsuario->remove('confirma_senha');

        unset($usuario['senha']);
        $formUsuario->setData($usuario);
        
        //se for ti, form de empresa
        $formEmpresa = false;
        $unidades = false;
        if($usuario['id_usuario_tipo'] == 3){
            $formEmpresa = new formEmpresa('frmEmpresa', $this->getServiceLocator());
            $serviceUsuarioEmpresa = $this->getServiceLocator()->get('UsuarioUnidade');
            $unidades = $serviceUsuarioEmpresa->getUnidadesByUsuario($usuario['id']);
        }

        if($this->getRequest()->isPost()){
            $dados = $this->getRequest()->getPost()->toArray();

            if(isset($dados['empresa'])){
                $formEmpresa->setData($dados);
                if($formEmpresa->isValid()){
                    $dados = $formEmpresa->getData();
                    $dados['usuario'] = $usuario->id;
                    if($serviceUsuarioEmpresa->insert($dados)){
                        $this->flashMessenger()->addSuccessMessage('Unidade vinculada com sucesso!');
                        return $this->redirect()->toRoute('usuarioAlterar', array('id' => $usuario->id));
                    }
                }
            }else{
                $formUsuario->setData($dados);
                if($formUsuario->isValid()){
                    if((empty($dados['senha']))){
                        unset($dados['senha']);
                    }else{
                        $bcrypt = new Bcrypt();
                        $dados['senha'] = $bcrypt->create($dados['senha']);
                    }
                    $serviceUsuario->update($dados, array('id'  =>  $usuario->id));
                    $this->flashMessenger()->addSuccessMessage('Usuario alterado com sucesso!'); 
                    return $this->redirect()->toRoute('usuarioAlterar', array('id' => $usuario->id));
                }
                
            }

        }

        return new ViewModel(array(
                                'formUsuario' => $formUsuario,
                                'formEmpresa' => $formEmpresa,
                                'unidades'    => $unidades,
                                'usuario'     => $usuario,
                                'usuarioLogado' =>  $usuarioLogado
                                )
                            );
    }

    public function deletarunidadeAction(){
        $serviceUsuarioEmpresa = $this->getServiceLocator()->get('UsuarioUnidade');
        if($serviceUsuarioEmpresa->delete(array('id' => $this->params()->fromRoute('id')))){
            $this->flashMessenger()->addSuccessMessage('Unidade desvinculada com sucesso!');
        }else{
            $this->flashMessenger()->addErrorMessage('Erro ao desvincular unidade');
        }
        return $this->redirect()->toRoute('usuarioAlterar', array('id' => $this->params()->fromRoute('usuario')));
    }

    public function deletarusuarioAction(){
        $serviceUsuario = $this->getServiceLocator()->get('Usuario');

        $res = $serviceUsuario->update(array('ativo' => 'N'), array('id' => $this->params()->fromRoute('id')));
        if($res){
           $this->flashMessenger()->addSuccessMessage('Usuário desativado com sucesso!');  
        }else{
            $this->flashMessenger()->addErrorMessage('Erro ao desativar usuário!');
        }
        return $this->redirect()->toRoute('usuario');
    }

}

