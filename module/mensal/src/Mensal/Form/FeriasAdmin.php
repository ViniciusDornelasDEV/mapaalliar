<?php

 namespace Mensal\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class FeriasAdmin extends BaseForm
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
        $this->_addDropdown('funcionario', '* Funcionário:', true, array('' => 'Selecione uma unidade'));        

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
