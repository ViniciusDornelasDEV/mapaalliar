<?php

 namespace Diario\Form;
 
use Application\Form\PesquisaAdmin as AdminForm;
 
 class PesquisarAjudaAdmin extends AdminForm
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
        
        //unidade de origem
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', 'Empresa:', false, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade solicitante:', false, array('' => '-- Selecione --'));
        

        //data
        $this->genericTextInput('inicio', 'Data de início, de:', false);
        $this->genericTextInput('fim', 'Até:', false);
            

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        //$dados['inicio'] = parent::converterData($dados['inicio']);
        //$dados['fim'] = parent::converterData($dados['fim']);

        parent::setData($dados);
    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        return $dados;
    }

 }
