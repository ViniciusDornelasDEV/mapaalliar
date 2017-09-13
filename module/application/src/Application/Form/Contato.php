<?php

 namespace Application\Form;
 
use Application\Form\Base as BaseForm; 

 class Contato extends BaseForm {
     
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


        $this->genericTextInput('nome', false, true, '* Nome');

        $this->addEmailElement('email', false, true, '* Email');
        
        $this->genericTextArea('comentario', false, true, '* Insira aqui seu comentário', true, 0, 200, 'width: 100%');

        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
    }

 }
