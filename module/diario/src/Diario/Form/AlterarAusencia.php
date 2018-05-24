<?php

 namespace Diario\Form;
 
use Application\Form\Base as BaseForm;
 
 class AlterarAusencia extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $usuario = false)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //funcionário
        $params = array('ativo' => 'S');
        if($usuario){
            $params = array('lider_imediato' => $usuario['funcionario']);
        }
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios($params);
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('funcionario', '* Funcionário:', false, $funcionarios);        

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
