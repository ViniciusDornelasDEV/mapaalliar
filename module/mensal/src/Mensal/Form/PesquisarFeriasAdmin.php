<?php

 namespace Mensal\Form;
 
 use Application\Form\Base as BaseForm;
 
 class PesquisarFeriasAdmin extends BaseForm
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

        //matricula
        $this->genericTextInput('matricula', 'Matrícula:', false, 'Númeroda matrícula');
        
        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', 'Empresa:', false, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => 'Selecione uma empresa'), 'carregarArea(this.value, "P");');

        //area    
        $this->_addDropdown('area', 'Área:', false, array('' => 'Selecione uma unidade'), 'carregarSetor(this.value, "P");');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => 'Selecione uma área'), 'carregarFuncao(this.value, "P");');

        //funcao
        $this->_addDropdown('funcao', 'Função:', false, array('' => 'Selecione um setor'), 'carregarFuncionario(this.value);');

        //data_inicio
        $this->genericTextInput('inicio_inicio', 'Data de início, de:', false);

        $this->genericTextInput('inicio_fim', 'Até:', false);
            
        //data_fim
        $this->genericTextInput('fim_inicio', 'Data de término, de:', false);

        $this->genericTextInput('fim_fim', 'Até:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

 }
