<?php

 namespace Semanal\Form;
 
 use Application\Form\Base as BaseForm;
 
 class ArquivoEscala extends BaseForm
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
        
        $this->addFileInput('caminho_arquivo', '* Arquivo:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
