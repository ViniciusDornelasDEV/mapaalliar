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

        $this->genericTextInput('inicio_referencia', '* Início:', true);

        $this->genericTextInput('fim_referencia', 'Fim:', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio_referencia'] = parent::converterData($dados['inicio_referencia']);
        $dados['fim_referencia'] = parent::converterData($dados['fim_referencia']);

        return $dados;
    }

 }
