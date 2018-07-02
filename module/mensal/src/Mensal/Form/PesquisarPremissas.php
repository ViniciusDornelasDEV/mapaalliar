<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarPremissas extends BaseForm
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
       
        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', 'Empresa:', false, $empresas, 'carregarUnidade(this.value, "P");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => '-- Selecione --'));


        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
