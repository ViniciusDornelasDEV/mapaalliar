<?php

 namespace Cadastros\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarEmpresa extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name)
    {
        parent::__construct($name);
        $this->genericTextInput('nome', 'Nome da empresa:', false, 'Nome da empresa');
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }
 }
