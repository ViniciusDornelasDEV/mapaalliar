<?php

 namespace Diario\Form;
 
use Application\Form\Base as BaseForm;
 
 class Ajuda extends BaseForm
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

        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array('ativo' => 'S'), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));

        //empresa apoio
        $this->_addDropdown('empresa_apoio', '* Empresa de apoio:', true, $empresas, 'carregarUnidadeDestino(this.value, "C");');

        //unidade de destino
        $this->_addDropdown('unidade_destino', '* Unidade de apoio:', true, array('' => '-- Selecione --'));

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
        $this->_addDropdown('setor', '* Setor de atuação:', true, array('' => '-- Selecione --'));

        $this->genericTextArea('anotacoes', 'Outros funcionários: ');

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);

        if(isset($dados['empresa_apoio']) && !empty($dados['empresa_apoio'])){
            //carregar unidades da empresa
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa_apoio'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade_destino')->setAttribute('options', $unidades);
        }

        if(isset($dados['area']) && !empty($dados['area'])){
            $setores = $this->serviceLocator->get('Setor')->getSetores(array('area' => $dados['area']));
            $setores = $this->prepareForDropDown($setores, array('id', 'nome'));

            //Setando valores
            $this->get('setor')->setAttribute('options', $setores);
        }

        parent::setData($dados);
    }


    public function setFuncionarioByUnidade($idUnidade){
        //buscar funcionarios
        $funcionarios = $this->serviceLocator->get('Funcionario')->getRecordsFromArray(array('ativo' => 'S', 'unidade' => $idUnidade), 'nome');
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

        //Setando valores
        $funcionarios = $this->get('funcionario')->setAttribute('options', $funcionarios);
        return $funcionarios;
    }
 }
