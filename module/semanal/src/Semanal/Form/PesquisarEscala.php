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
   public function __construct($name, $serviceLocator, $idUnidade)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //area    
        $areas = $this->serviceLocator->get('Area')->getAreaUnidade($idUnidade);
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área:', true, $areas, 'carregarSetor(this.value, "P", "N", '.$idUnidade.');');

        //setor
        $this->_addDropdown('setor', '* Setor:', true, array('' => '-- Selecione --'));

        //mes e ano
        $this->genericTextInput('mes_ano', '* Mês/ano:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
