<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class VincularGestor extends BaseForm {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $funcionario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);      

        //gestores
        $gestores = $this->serviceLocator->get('Funcionario')->getGestores($funcionario);
        $gestores = $this->prepareForDropDown($gestores, array('id', 'nome'));
        $this->_addDropdown('gestor', '* Gestor:', true, $gestores);

        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }
 }
