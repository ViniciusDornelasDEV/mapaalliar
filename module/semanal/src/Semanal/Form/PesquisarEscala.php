<?php

 namespace Semanal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarEscala extends BaseForm
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

        parent::__construct($name);  

        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', 'Área:', false, $areas, 'carregarSetor(this.value, "P");');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => 'Selecione uma área'));

        //mes e ano
        $this->genericTextInput('mes_ano', 'Mês/ano:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
