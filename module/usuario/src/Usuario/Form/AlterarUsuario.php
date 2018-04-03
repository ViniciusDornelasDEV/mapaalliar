<?php

 namespace Usuario\Form;
 
use Application\Form\Base as BaseForm;
 
 class AlterarUsuario extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $usuario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);
        $this->genericTextInput('nome', '* Nome do usuário:', true, 'Nome do usuário');

        $this->genericTextInput('login', '* Login: ', true, 'login');

        //Tipo de usuário
        $serviceTipoUsuario = $this->serviceLocator->get('UsuarioTipo');
        $tipos = $serviceTipoUsuario->getRecords('S', 'ativo');

        if($usuario['id_usuario_tipo'] != 2){
            if(!$tipos){
                $tipos = array();
            }
            $tipos = $this->prepareForDropDown($tipos, array('id', 'perfil'));
            $this->_addDropdown('id_usuario_tipo', '* Tipo de usuário: ', true, $tipos);
        }

        $this->genericTextInput('senha', 'Alterar senha: ', false, 'Nova senha');

        $this->_addDropdown('ativo', 'Ativo:', true, array('S' => 'Ativo', 'N' => 'Inativo'));
        
        $this->setAttributes(array(
            'class'  => 'form-signin',
            'role'   => 'form'
        ));

    }
 }
