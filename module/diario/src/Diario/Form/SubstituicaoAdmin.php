<?php

 namespace Diario\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class SubstituicaoAdmin extends BaseForm
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


        //funcionário
        $this->_addDropdown('funcionario', '* Funcionário:', true, array('' => '-- Selecione --'));        

        //data_desligamento
        $this->genericTextInput('data_desligamento', '* Data de desligamento:', true);

        //vaga_rh
        $this->_addDropdown('vaga_rh', '* Vaga aberta RH:', true, array('' => '--', 'S' => 'Sim', 'N' => 'Não'));

        //encerrada
        $this->_addDropdown('encerrada', '* Vaga Substituida:', true, array('' => '--', 'S' => 'Sim', 'N' => 'Não'));

        //numero_rp
        $this->genericTextInput('numero_rp', 'Número da RP:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }


    public function setData($dados){
        $dados['data_desligamento'] = parent::converterData($dados['data_desligamento']);

        if(isset($dados['empresa']) && !empty($dados['empresa'])){
            //carregar unidades da empresa
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade')->setAttribute('options', $unidades);
        }

        if(isset($dados['unidade']) && !empty($dados['unidade'])){
            //carregar funcionarios da unidade
            $funcionarios = $this->serviceLocator->get('Funcionario')->getRecordsFromArray(array('ativo' => 'S', 'unidade' => $dados['unidade']));
            $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));

            //Setando valores
            $funcionarios = $this->get('funcionario')->setAttribute('options', $funcionarios);
        }

        parent::setData($dados);
    }
 }
