<?php

 namespace Cadastros\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarArea extends BaseForm
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
        $this->genericTextInput('nome', 'Nome da área:', false, 'Nome da área');
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }
 }
