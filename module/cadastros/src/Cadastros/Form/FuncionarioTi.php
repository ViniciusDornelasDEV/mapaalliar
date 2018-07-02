<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class FuncionarioTi extends BaseForm {
     
   private $usuario;
   public function __construct($name, $serviceLocator, $usuario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);      

        $this->usuario = $usuario;
        //matricula
        $this->genericTextInput('matricula', '* Matrícula:', true, 'Número da matrícula');

        //nome
        $this->genericTextInput('nome', '* Nome:', true, 'Nome do funcionário');
        //empresa
        $empresas = $this->serviceLocator->get('UsuarioUnidade')->getEmpresasTi($usuario['id']);
        $empresas = $this->prepareForDropDown($empresas, array('id_empresa', 'nome_empresa'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidadeTI(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => '-- Selecione --'), 'carregarLider(this.value);');

        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', '* Área:', true, $areas, 'carregarSetor(this.value, "C");');

        //setor
        $this->_addDropdown('setor', '* Setor:', true, array('' => '-- Selecione --'), 'carregarFuncao(this.value, "C");');

        //funcao
        $this->_addDropdown('funcao', '* Função:', true, array('' => '-- Selecione --'));

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
        $this->_addDropdown('lider_imediato', 'Líder imediato:', false, array('' => '-- Selecione --'));

        //lider
        $this->_addDropdown('lider', '* líder:', true, array('' => '--', 'S' => 'Sim', 'N' => 'Não'));

        //numero_rp
        $this->genericTextInput('numero_rp', 'Número da RP:', false);

        //email
        $this->addEmailElement('email', 'Email', false);

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
        
        //registro_profissional
        $this->genericTextInput('registro_profissional', 'Registro profissional:', false);

        //obs
        $this->genericTextArea('obs', 'Observações: ', false);

        $this->_addDropdown('ativo', 'Ativo:', false, array('S' => 'Ativo', 'N' => 'Inativo'));
        
        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function setUnidadeByEmpresaTi($idEmpresa, $usuario){
        //buscar unidades
        $unidades = $this->serviceLocator->get('UsuarioUnidade')->getUnidadesTi($usuario['id'], $idEmpresa);
        $unidades = $this->prepareForDropDown($unidades, array('id_unidade', 'nome_unidade'));

        //Setando valores
        $unidades = $this->get('unidade')->setAttribute('options', $unidades);
        return $unidades;
    }
   

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_nascimento'] = parent::converterData($dados['data_nascimento']);

        
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
            $unidades =$this->serviceLocator->get('UsuarioUnidade')->getUnidadesTi($this->usuario['id'], $dados['empresa']);
            $unidades = $this->prepareForDropDown($unidades, array('id_unidade', 'nome_unidade'));

            //Setando valores
            $this->get('unidade')->setAttribute('options', $unidades);
        }

        if(isset($dados['lider']) && ($dados['lider'] == 'N')){
            //carregar lideres da unidade
            $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('lider' => 'S', 'unidade' => $dados['unidade']));
            $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
            //Setando valores
            $this->get('lider_imediato')->setAttribute('options', $funcionarios);
        }


        return parent::setData($dados);
    }
 }
