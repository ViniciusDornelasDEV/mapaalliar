<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class Ferias extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $funcionario = false)
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

        //data_inicio
        $this->genericTextInput('data_inicio', '* Data de início:', true);

        //data_fim
        $this->genericTextInput('data_fim', '* Data de término:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setFuncionarioByFuncao($idFuncao, $idLider){
        //buscar cargos
        $funcionarios = $this->serviceLocator
                            ->get('Funcionario')
                            ->getFuncionarios(array('funcao' => $idFuncao, 'ativo' => 'S'), $idLider);
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

        //Setando valores
        $funcionarios = $this->get('funcionario')->setAttribute('options', $funcionarios);
        return $funcionarios;
    }

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);

        parent::setData($dados);
    }
 }
