<?php

 namespace Mensal\Form;
 
 use Application\Form\Base as BaseForm;
 
 class Mira extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'), array('' => '-- selecione --', 'T' => 'Todos'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "P", "T");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => 'Selecione uma empresa'));
        
        //nome
        $this->genericTextInput('nome', '* Nome:', true, 'Nome');

        //email
        $this->addEmailElement('email', '* Email:', true, 'Email');


        $this->addFileInput('imagem_1', '* Imagem:', true);

        $this->addFileInput('imagem_2', 'Imagem:', false);

        $this->addFileInput('imagem_3', 'Imagem:', false);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
