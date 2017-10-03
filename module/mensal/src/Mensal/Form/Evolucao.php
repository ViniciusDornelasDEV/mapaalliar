<?php

 namespace Mensal\Form;
 
use Mensal\Form\Premissas as BaseForm;
 
 class Evolucao extends BaseForm
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

        parent::__construct($name, $serviceLocator);  


        //ouro
        $this->genericTextInput('ouro', '* Ouro:', true);
        
        //prata
        $this->genericTextInput('prata', '* Prata:', true);
        
        //bronze
        $this->genericTextInput('bronze', '* Bronze:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
