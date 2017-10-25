<?php

 namespace Diario\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class AusenciasAdmin extends BaseForm
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
        $this->genericTextInput('data', '* Data:', true);

        //motivo
        $this->genericTextInput('motivo', 'Motivo da ausência:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        
        parent::setData($dados);
    }
 }
