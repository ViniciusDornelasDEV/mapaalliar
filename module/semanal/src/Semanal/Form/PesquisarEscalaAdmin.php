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
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => '-- Selecione --'), 'carregarArea(this.value, "C");');

        $this->_addDropdown('area', '* Área:', true, array('' => '-- Selecione --'), 'carregarSetor(this.value, "C", "N", true);');

        //setor
        $this->_addDropdown('setor', '* Setor:', true, array('' => '-- Selecione --'));

        //mes e ano
        $this->genericTextInput('mes_ano', '* Mês/ano:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
