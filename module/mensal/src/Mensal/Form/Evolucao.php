<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
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

        parent::__construct($name);  

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'), array('' => '-- selecione --', 'T' => 'Todos'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "P", "T");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => 'Selecione uma empresa'));


        //ouro
        $this->genericTextInput('ouro', '* Ouro:', true);
        
        //prata
        $this->genericTextInput('prata', '* Prata:', true);
        
        //bronze
        $this->genericTextInput('bronze', '* Bronze:', true);

        //classificacao_atual
        $this->_addDropdown('classificacao_atual', '* Classificação atual:', true, array('O' => 'Ouro', 'P' => 'Prata', 'B' => 'Bronze'));
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
