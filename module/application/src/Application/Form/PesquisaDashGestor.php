<?php

 namespace Application\Form;
 
 use Application\Form\Base as BaseForm; 

 class PesquisaDashGestor extends BaseForm {
     
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

      

        $this->genericTextInput('inicio', 'Data de:', false);
        $this->genericTextInput('fim', 'Até:', false);


        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        return $dados;
    }
 }
