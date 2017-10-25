<?php

 namespace Mensal\Form;
 
 use Application\Form\Base as BaseForm;
 
 class TmaAndar extends BaseForm
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
 
        //descricao
        $this->genericTextInput('descricao', '* Descrição:', true);
        
        //quantidade
        $this->genericTextInput('quantidade', '* Quantidade:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }


 }
