<?php

 namespace Adminpessoal\Form;
 
use Application\Form\Base as BaseForm;
 
 class AlterarAcoes extends BaseForm
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
        $params = false;
        if($usuario){
            $params = array('lider_imediato' => $usuario['funcionario']);
        }
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios($params);
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('funcionario', '* Funcionário:', false, $funcionarios);        

        //data_inicio
        $this->genericTextInput('data', '* Data:', true);

        $this->genericTextInput('apontamento', '* Apontamento:', true);

        $this->genericTextInput('orientacao_acao', '* Orientação/ação realizada:', true);

        $this->genericTextInput('planejamento', '* Planejamento:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        
        parent::setData($dados);
    }
 }
