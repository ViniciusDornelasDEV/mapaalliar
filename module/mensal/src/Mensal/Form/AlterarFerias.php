<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class AlterarFerias extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $usuario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //funcionário
        $funcionarios = $this->serviceLocator->get('Funcionario')->getFuncionarios(array('lider_imediato' => $usuario['funcionario']));
        
        $funcionarios = $this->prepareForDropDown($funcionarios, array('id', 'nome'));
        $this->_addDropdown('funcionario', '* Funcionário:', false, $funcionarios);        

        //data_inicio
        $this->genericTextInput('data_inicio', '* Data de início:', true);

        //data_fim
        $this->genericTextInput('data_fim', '* Data de término:', true);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }


    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);

        parent::setData($dados);
    }
 }
