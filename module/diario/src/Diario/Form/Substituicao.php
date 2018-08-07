<?php

 namespace Diario\Form;
 
use Application\Form\Base as BaseForm;
 
 class Substituicao extends BaseForm
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

        parent::setData($dados);
    }
 }
