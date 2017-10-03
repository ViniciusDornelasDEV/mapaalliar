<?php

 namespace Mensal\Form;
 
use Mensal\Form\Premissas as BaseForm;
 
 class Nps extends BaseForm
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


        //promotores
        $this->genericTextInput('promotores', '* Promotores:', true);
        
        //defratores
        $this->genericTextInput('defratores', '* Defratores:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
