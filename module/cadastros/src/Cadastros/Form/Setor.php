<?php

 namespace Cadastros\Form;
 
use Application\Form\Base as BaseForm;
 
 class Setor extends BaseForm
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
        parent::__construct($name);

        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        $this->genericTextInput('nome', '* Nome do setor:', true, 'Nome do setor');

        //area
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área:', true, $areas);
        
        $this->_addDropdown('ativo', 'Ativo:', false, array('' => '--', 'S' => 'Ativo', 'N' => 'Inativo'));
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }
 }
