<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class PesquisarFuncionario extends BaseForm {
     
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

        //nome
        $this->genericTextInput('nome', 'Nome:', false, 'Nome do funcionário');

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', 'Empresa:', false, $empresas, 'carregarUnidade(this.value, "P");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => '-- Selecione --'), 'carregarArea(this.value, "P");');

        //area    
        $this->_addDropdown('area', 'Área:', false, array('' => '-- Selecione --'), 'carregarSetor(this.value, "P", "N", true);');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => '-- Selecione --'), 'carregarFuncao(this.value, "P", true);');

        //funcao
        $this->_addDropdown('funcao', 'Função:', false, array('' => '-- Selecione --'));

        $this->_addDropdown('ativo', 'Ativo:', false, array('S' => 'Ativo', 'N' => 'Inativo'));
        //tipo_contratacao
        /*$tiposContratacao = array('' => '-- Selecione --', 'Interna' => 'Interna', 'Externa' => 'Externa');
        $this->_addDropdown('tipo_contratacao', 'Tipo de contratação:', false, $tiposContratacao);
        
        //tipo_contrato
        $tiposContrato = array('' => '-- Selecione --', 'Contrato' => 'Contrato', 'CLT' => 'CLT');
        $this->_addDropdown('tipo_contrato', 'Tipo de contrato:', false, $tiposContrato);*/

        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }



    public function setFuncaoBySetor($idSetor, $idUnidade){
        $params = array('setor' => $idSetor);
        if($idUnidade != 'false'){
            $params['unidade'] = $idUnidade;
        }
        //buscar funcoes
        $funcoes = $this->serviceLocator->get('Funcao')->getFuncoes($params, true);
        $funcoes = $this->prepareForDropDown($funcoes, array('id', 'nome'));

        //Setando valores
        $funcoes = $this->get('funcao')->setAttribute('options', $funcoes);
        return $funcoes;
    }

    public function setAreaByUnidade($idUnidade){
        $areas = $this->serviceLocator->get('Area')->getAreaUnidade($idUnidade);
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));

        //Setando valores
        $areas = $this->get('area')->setAttribute('options', $areas);
        return $areas;
    }

    public function setUnidadeByEmpresa($idEmpresa, $todos = false){
        //buscar unidades
        $unidades = $this->serviceLocator->get('Unidade')->getRecords($idEmpresa, 'empresa', array('*'), 'nome');

        $preparedArray = array('' => '-- selecione --');
        if($todos == 'T'){
            $preparedArray['T'] = 'Todos';
        }

        $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'), $preparedArray);

        //Setando valores
        $unidades = $this->get('unidade')->setAttribute('options', $unidades);
        return $unidades;
    }

    public function setData($dados){
        if(isset($dados['data_inicio']) && !empty($dados['data_inicio'])){
            $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        }

        if(isset($dados['data_nascimento']) && !empty($dados['data_nascimento'])){
            $dados['data_nascimento'] = parent::converterData($dados['data_nascimento']);
        }

        if(!empty($dados['data_saida'])){
            $dados['data_saida'] = parent::converterData($dados['data_saida']);
        }
        
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
