<?php

 namespace Semanal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarEquipesAdmin extends BaseForm
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
       
        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => 'Selecione uma empresa'));
        
        //mes e ano
        $this->genericTextInput('mes_ano', 'Mês/ano:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
