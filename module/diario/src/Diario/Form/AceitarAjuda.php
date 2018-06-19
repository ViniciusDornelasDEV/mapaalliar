<?php

 namespace Diario\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class AceitarAjuda extends BaseForm
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
        $this->_addDropdown('empresa', '* Empresa solicitante:', false, $empresas, 'carregarUnidade(this.value, "C");carregarUnidadeDestino(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade solicitante:', false, array('' => 'Selecione uma empresa'));
        
        //data_inicio
        $this->genericTextInput('data_inicio', '* Data de início:', false);

        //data_fim
        $this->genericTextInput('data_fim', '* Data de término:', false);

        //hora_inicio
        $this->genericTextInput('hora_inicio', '* Hora de início:', false);

        //hora_fim
        $this->genericTextInput('hora_fim', '* Hora de término:', false);

        //area
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array('ativo' => 'S'), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área de atuação:', false, $areas, 'carregarSetor(this.value, "C");');

        //setor
        $this->_addDropdown('setor', '* Setor de atuação:', false, array('' => 'Selecione uma área'));

        $this->genericTextArea('anotacoes', 'Funcionários: ');

        $this->_addDropdown('aceita', '* Aceitar ajuda:', true, array('--', 'S' => 'Aprovada', 'N' => 'Rejeitada'));
        
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
