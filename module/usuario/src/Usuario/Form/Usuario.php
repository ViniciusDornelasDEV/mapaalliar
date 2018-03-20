<?php

 namespace Usuario\Form;
 
 use Application\Form\Base as BaseForm; 
 
 class Usuario extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $idFuncionario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);
        $this->genericTextInput('nome', '* Nome do usuário:', true, 'Nome do usuário');

        $this->genericTextInput('login', '* Login', true, 'Login');
        

        if(!$idFuncionario){
            $serviceTipoUsuario = $this->serviceLocator->get('UsuarioTipo');
            $tipos = $serviceTipoUsuario->getRecords('S', 'ativo');

            $tipos = $this->prepareForDropDown($tipos, array('id', 'perfil'));
            $this->_addDropdown('id_usuario_tipo', '* Tipo de usuário: ', true, $tipos);
            
        }

        $this->_addPassword('senha', '* Senha: ', 'Senha');
        
        $this->_addPassword('confirma_senha', '* Confirma senha: ', 'Confirmar senha', 'senha');

        $this->setAttributes(array(
            'class'  => 'form-signin',
            'role'   => 'form'
        ));

    }
 }
