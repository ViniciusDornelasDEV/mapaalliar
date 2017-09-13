<?php

 namespace Cadastros\Form;
 
use Application\Form\Base as BaseForm;
 
 class Area extends BaseForm
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
        
        $this->genericTextInput('nome', '* Nome:', true, 'Nome da área');

        $this->genericTextInput('responsavel', '* Responsável:', true, 'Nome do responsável');
        
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }
 }
