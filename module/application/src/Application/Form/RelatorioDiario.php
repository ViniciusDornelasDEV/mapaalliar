<?php

 namespace Application\Form;
 
use Application\Form\Base as BaseForm;
 
 class RelatorioDiario extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name)
    {
        parent::__construct($name);  

        $this->genericTextInput('data_referencia', '* Data:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['data_referencia'] = parent::converterData($dados['data_referencia']);

        return $dados;
    }

 }
