<?php

 namespace Diario\Form;
 
use Application\Form\Base as BaseForm;
 
 class Ausencias extends BaseForm
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
        $this->_addDropdown('setor', 'Setor:', false, array('' => 'Selecione uma área'), 'carregarFuncao(this.value, "C", '.$funcionario['unidade'].');');

        //funcao
        $this->_addDropdown('funcao', 'Função:', false, array('' => 'Selecione um setor'), 'carregarFuncionario(this.value);');

        //funcionário
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('ativo' => 'S'), $funcionario['id']);
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

        $this->_addDropdown('funcionario', '* Funcionário:', true, $funcionarios);        

        //data_inicio
        $this->genericTextInput('data', '* Início:', true);

        //data_fim
        $this->genericTextInput('data_fim', '* Fim:', true);

        //motivo
        $this->genericTextInput('motivo', 'Motivo da ausência:', false);
        
        //cid
        $this->genericTextInput('cid', 'CID:', false);

        //atestado
        $this->addFileInput('atestado', 'Upload do atestado: ', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);

        parent::setData($dados);
    }
 }
