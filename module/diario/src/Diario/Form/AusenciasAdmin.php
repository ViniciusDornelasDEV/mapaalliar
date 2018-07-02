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
        $this->_addDropdown('funcionario', '* Funcionário:', true, array('' => '-- Selecione --'));        

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
