<?php

 namespace Cadastros\Form;
 
use Application\Form\Base as BaseForm;
 
 class Unidade extends BaseForm
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
        
        $this->addHiddenInput('unidade', false);

        $this->genericTextInput('nome', '* Nome:', true, 'Nome da unidade');

        $this->genericTextInput('responsavel', '* Responsável:', true, 'Nome do responsável');
        
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }
 }
