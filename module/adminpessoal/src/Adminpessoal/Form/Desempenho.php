<?php

 namespace Adminpessoal\Form;
 
use Application\Form\Base as BaseForm;
 
 class Desempenho extends BaseForm
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
        $this->_addDropdown('funcao', 'Cargo:', false, array('' => '-- Selecione --'), 'carregarFuncionario(this.value);');

        //funcionário
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('lider_imediato' => $funcionario['id'], 'ativo' => 'S'));
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('funcionario', '* Funcionário:', true, $funcionarios);        

        //data_inicio
        $this->genericTextInput('data', '* Data:', true);

        //data_proximo_feedback
        $this->genericTextInput('data_proximo_feedback', '* Data do próximo feedback:', true);

        //pontos_positivos
        $this->genericTextArea('pontos_positivos', '* Pontos positivos:', true);

        //pontos_desenvolver
        $this->genericTextArea('pontos_desenvolver', '* Pontos a serem desenvolvidos:', true);

        //plano_acao
        $this->genericTextArea('plano_acao', '* Plano de ação:', true);

        //feedback_realizado
        $this->_addDropdown('feedback_realizado', '* Feedback realizado:', true, array('N' => 'Não', 'S' => 'Sim'));
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        $dados['data_proximo_feedback'] = parent::converterData($dados['data_proximo_feedback']);
        
        parent::setData($dados);
    }
 }
