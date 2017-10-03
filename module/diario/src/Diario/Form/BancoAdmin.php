<?php

 namespace Diario\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class BancoAdmin extends BaseForm
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
        parent::__construct($name, $serviceLocator);  

        $this->genericTextInput('inicio', '* Período de:', true);

        $this->genericTextInput('fim', '* até:', true);

        //caminho
        $this->addFileInput('caminho', '* Arquivo: ', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        parent::setData($dados);
    }
 }
