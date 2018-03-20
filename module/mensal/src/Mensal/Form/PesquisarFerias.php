<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarFerias extends BaseForm
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
        //matricula
        $this->genericTextInput('matricula', 'Matrícula:', false, 'Númeroda matrícula');

        //area    
        $areas = $this->serviceLocator->get('Area')->getAreaUnidade($idUnidade);
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', 'Área:', false, $areas, 'carregarSetor(this.value, "C", "N", '.$idUnidade.');');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => 'Selecione uma área'), 'carregarFuncao(this.value, "C", '.$idUnidade.');');

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
