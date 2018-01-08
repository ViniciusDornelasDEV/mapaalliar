<?php

 namespace Diario\Form;
 
 use Application\Form\Base as BaseForm; 

 class AlterarFuncionario extends BaseForm {
     
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

        //matricula
        $this->genericTextInput('matricula', '* Matrícula:', true, 'Número da matrícula');

        //nome
        $this->genericTextInput('nome', '* Nome:', true, 'Nome do funcionário');
     
        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array('ativo' => 'S'), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área:', true, $areas, 'carregarSetor(this.value, "C");');

        //setor
        $this->_addDropdown('setor', '* Setor:', true, array('' => 'Selecione uma área'), 'carregarFuncao(this.value, "C");');

        //funcao
        $this->_addDropdown('funcao', '* Função:', true, array('' => 'Selecione um setor'));

        //tipo_contratacao
        $tiposContratacao = array('' => '-- Selecione --', 'Interna' => 'Interna', 'Externa' => 'Externa');
        $this->_addDropdown('tipo_contratacao', '* Tipo de contratação:', true, $tiposContratacao);
        
        //tipo_contrato
        $tiposContrato = array('' => '-- Selecione --', 'Contrato' => 'Contrato', 'CLT' => 'CLT');
        $this->_addDropdown('tipo_contrato', '* Tipo de contrato:', true, $tiposContrato);

        //data_inicio
        $this->genericTextInput('data_inicio', '* Data de início:', true);

        //periodo_trabalho
        $periodos = array('' => '-- Selecione --', 'Manhã' => 'Manhã', 'Tarde' => 'Tarde', 'Noite' => 'Noite');
        $this->_addDropdown('periodo_trabalho', '* Período de trabalho:', true, $periodos);

        //inicio_turno
        $this->genericTextInput('inicio_turno', '* Início da jornada:', true);

        //fim_turno
        $this->genericTextInput('fim_turno', '* Fim da jornada:', true);

        //ccusto
        $this->genericTextInput('ccusto', '* Centro de custo:', true);
        
        //desc_ccusto
        $this->genericTextInput('desc_ccusto', '* Descrição centro de custo:', true);
        
        //horario
        $this->genericTextInput('horario', '* Horário:', true);
        
        //lider_imediato
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('lider' => 'S', 'unidade' => $funcionario['unidade']));
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('lider_imediato', 'Líder imediato:', false, $funcionarios);

        //lider
        $this->_addDropdown('lider', '* líder:', true, array('' => '--', 'S' => 'Sim', 'N' => 'Não'));

        //numero_rp
        $this->genericTextInput('numero_rp', 'Número da RP:', false);

        //email
        $this->addEmailElement('emailh xc', 'Email', false);

        //data_nascimento
        $this->genericTextInput('data_nascimento', 'Data de nascimento:', false);

        //cpf
        $this->genericTextInput('cpf', 'CPF:', false);

        //login_qmatic
        $this->genericTextInput('login_qmatic', 'Login QMATIC:', false);

        //login_pleres
        $this->genericTextInput('login_pleres', 'Login PLERES:', false);

        //login_afip
        $this->genericTextInput('login_afip', 'Login AFIP:', false);
        
        //obs
        $this->genericTextArea('obs', 'Observações: ', false);

        $this->_addDropdown('ativo', 'Ativo:', false, array('S' => 'Ativo', 'N' => 'Inativo'));

        //data_saida
        $this->genericTextInput('data_saida', 'Data de saída:', false);

        //motivo_saida
        $this->genericTextArea('motivo_saida', 'Motivo da saída: ', false);

        //substituicao_de
        $substituicoes = $this->serviceLocator
                            ->get('Funcionario')
                            ->getFuncionarios(array('unidade' => $funcionario['unidade'], 'funcionario' => $funcionario['id'], 'ativo' => 'S'));
        $substituicoes = $this->prepareForDropDown($substituicoes, array('id', 'nome'));
        $this->_addDropdown('substituicao_de', 'Substituição de:', false, $substituicoes);

        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);


        $dados['data_nascimento'] = parent::converterData($dados['data_nascimento']);

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


        return parent::setData($dados);
    }
 }
