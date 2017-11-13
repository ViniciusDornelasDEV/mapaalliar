<?php

 namespace Semanal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarEscalaAdmin extends BaseForm
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
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => 'Selecione uma empresa'));

        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área:', true, $areas, 'carregarSetor(this.value, "P");');

        //setor
        $this->_addDropdown('setor', '* Setor:', true, array('' => 'Selecione uma área'));

        //mes e ano
        $this->genericTextInput('mes_ano', '* Mês/ano:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
