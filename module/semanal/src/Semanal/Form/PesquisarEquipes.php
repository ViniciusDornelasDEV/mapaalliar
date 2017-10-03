<?php

 namespace Semanal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarEquipes extends BaseForm
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

        //mes e ano
        $this->genericTextInput('mes_ano', 'Mês/ano:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
