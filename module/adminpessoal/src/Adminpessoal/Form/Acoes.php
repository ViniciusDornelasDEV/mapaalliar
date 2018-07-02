<?php

 namespace Adminpessoal\Form;
 
use Application\Form\Base as BaseForm;
 
 class Acoes extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $funcionario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //area    
        $areas = $this->serviceLocator->get('Area')->getAreaUnidade($funcionario['unidade']);
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', 'Área:', false, $areas, 'carregarSetor(this.value, "C", "N", '.$funcionario['unidade'].');');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => '-- Selecione --'), 'carregarFuncao(this.value, "C", '.$funcionario['unidade'].');');

        //funcao
        $this->_addDropdown('funcao', 'Função:', false, array('' => '-- Selecione --'), 'carregarFuncionario(this.value);');

        //funcionário
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('lider_imediato' => $funcionario['id'], 'ativo' => 'S'));
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('funcionario', '* Funcionário:', true, $funcionarios);        

        //tipo
        $tipos = $this->serviceLocator->get('AcaoDisciplinarTipo')->getRecordsFromArray(array());
        $tipos = $this->prepareForDropDown($tipos, array('id', 'nome'));
        $this->_addDropdown('tipo', '* Tipo de ação:', true, $tipos);
        
        //data_inicio
        $this->genericTextInput('data', '* Data:', true);

        $this->genericTextInput('apontamento', '* Apontamento:', true);

        $this->genericTextInput('orientacao_acao', '* Orientação/ação realizada:', true);

        $this->genericTextInput('planejamento', '* Planejamento:', true);



        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        
        parent::setData($dados);
    }
 }
