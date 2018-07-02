<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class MudarLider extends BaseForm {
     
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
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => '-- Selecione --'), 'carregarLider(this.value);');

        //lider_atual
        $this->_addDropdown('lider_imediato', '* Líder atual:', true, array('' => '-- Selecione --'), 'trocarLider(this.value);');

        //novo_lider
        $this->_addDropdown('novo_lider', '* Novo líder:', true, array('' => '-- Selecione --'));
        
        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function setLiderByLider($idLider){
        $serviceFuncionario = $this->serviceLocator->get('Funcionario');
        $funcionario = $serviceFuncionario->getRecord($idLider);
        //buscar funcionarios
        $funcionarios = $serviceFuncionario->getFuncionarios(array(
            'lider' => 'S', 
            'unidade' => $funcionario->unidade, 
            'funcionario' => $idLider
        ));
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

        //Setando valores
        $funcionarios = $this->get('novo_lider')->setAttribute('options', $funcionarios);
        return $funcionarios;
    }
 }
