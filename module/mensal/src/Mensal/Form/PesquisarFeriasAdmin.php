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
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => '-- Selecione --'), 'carregarArea(this.value, "P");');

        //area    
        $this->_addDropdown('area', 'Área:', false, array('' => '-- Selecione --'), 'carregarSetor(this.value, "P", "N", true);');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => '-- Selecione --'), 'carregarFuncao(this.value, "P", true);');

        //funcao
        $this->_addDropdown('funcao', 'Função:', false, array('' => '-- Selecione --'));

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

    public function setData($dados){
        
        if(isset($dados['area']) && !empty($dados['area'])){
            $setores = $this->serviceLocator->get('Setor')->getSetores(array('area' => $dados['area']));
            $setores = $this->prepareForDropDown($setores, array('id', 'nome'));

            //Setando valores
            $this->get('setor')->setAttribute('options', $setores);
        }

        if(isset($dados['setor']) && !empty($dados['setor'])){
            $funcoes = $this->serviceLocator->get('Funcao')->getFuncoes(array('setor' => $dados['setor']));
            $funcoes = $this->prepareForDropDown($funcoes, array('id', 'nome'));

            //Setando valores
            $this->get('funcao')->setAttribute('options', $funcoes);
        }

        if(isset($dados['empresa']) && !empty($dados['empresa'])){
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade')->setAttribute('options', $unidades);
        }

        if(isset($dados['unidade']) && !empty($dados['unidade'])){
            $areas = $this->serviceLocator->get('Area')->getAreaUnidade($dados['unidade']);
            $areas = $this->prepareForDropDown($areas, array('id', 'nome'));

            //Setando valores
            $this->get('area')->setAttribute('options', $areas);
        }

        return parent::setData($dados);
    }

 }
