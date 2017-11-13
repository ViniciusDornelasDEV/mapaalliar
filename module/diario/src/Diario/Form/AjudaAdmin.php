<?php

 namespace Diario\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class AjudaAdmin extends BaseForm
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

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array('ativo' => 'S'), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");carregarUnidadeDestino(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade de suporte:', true, array('' => 'Selecione uma empresa'), 'CarregarFuncionariosByUnidade(this.value);');
        
        //funcionario
        $this->_addDropdown('funcionario', '* Funcionário:', true, array('' => 'Selecione uma unidade'));

        //unidade de destino
        $this->_addDropdown('unidade_destino', '* Unidade de destino:', true, array('' => 'Selecione uma empresa'));
        //data_inicio
        $this->genericTextInput('data_inicio', '* Data de início:', true);

        //data_fim
        $this->genericTextInput('data_fim', '* Data de término:', true);

        //hora_inicio
        $this->genericTextInput('hora_inicio', '* Hora de início:', true);

        //hora_fim
        $this->genericTextInput('hora_fim', '* Hora de término:', true);

        //area
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array('ativo' => 'S'), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área de atuação:', true, $areas, 'carregarSetor(this.value, "C");');

        //setor
        $this->_addDropdown('setor', '* Setor de atuação:', true, array('' => 'Selecione uma área'));

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);
        
        if(isset($dados['empresa']) && !empty($dados['empresa'])){
            //carregar unidades da empresa
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade')->setAttribute('options', $unidades);
            $this->get('unidade_destino')->setAttribute('options', $unidades);
        }

        if(isset($dados['unidade']) && !empty($dados['unidade'])){
            //carregar funcionarios da unidade
            $funcionarios = $this->serviceLocator->get('Funcionario')->getRecordsFromArray(array('ativo' => 'S', 'unidade' => $dados['unidade']));
            $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

            //Setando valores
            $funcionarios = $this->get('funcionario')->setAttribute('options', $funcionarios);
        }

        if(isset($dados['area']) && !empty($dados['area'])){
            $setores = $this->serviceLocator->get('Setor')->getSetores(array('area' => $dados['area']));
            $setores = $this->prepareForDropDown($setores, array('id', 'nome'));

            //Setando valores
            $this->get('setor')->setAttribute('options', $setores);
        }

        parent::setData($dados);
    }


 }
