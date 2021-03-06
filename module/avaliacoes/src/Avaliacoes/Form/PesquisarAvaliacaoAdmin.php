<?php

 namespace Avaliacoes\Form;
 
use Application\Form\PesquisaAdmin as AdminForm;
 
 class PesquisarAvaliacaoAdmin extends AdminForm
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
        //referencia_inicio
        $this->genericTextInput('inicio', 'Período de referência, de:', false);
        $this->genericTextInput('fim', 'a:', false);

        //referencia
        $referencias = $this->serviceLocator->get('PilhaAvaliacoesReferencia')->getRecordsFromArray(array(), 'nome');
        $referencias = $this->prepareForDropDown($referencias, array('id', 'nome'));
        $this->_addDropdown('referencia', 'Referência:', false, $referencias);

        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', 'Área:', false, $areas, 'carregarSetor(this.value, "A");');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => '-- Selecione --'));

        //matricula
        $this->genericTextInput('matricula', 'Nº matrícula:', false);

        //nome
        $this->genericTextInput('nome', 'Nome: do funcionário', false);


        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);
        
        parent::setData($dados);
    }

 }
