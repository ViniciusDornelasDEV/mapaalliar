<?php

 namespace Mensal\Form;
 
use Mensal\Form\Premissas as BaseForm;
 
 class Tme extends BaseForm
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

        $this->addFileInput('caminho_imagem', '* Imagem:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
