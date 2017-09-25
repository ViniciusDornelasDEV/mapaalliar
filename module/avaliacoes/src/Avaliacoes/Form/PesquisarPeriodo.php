<?php

 namespace Avaliacoes\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarPeriodo extends BaseForm
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

        parent::__construct($name);  

        $this->genericTextInput('inicio', 'Data de início, de:', false);
        
        $this->genericTextInput('fim', 'até:', false);

        
        $referencias = $this->serviceLocator->get('PilhaAvaliacoesReferencia')->getRecordsFromArray(array(), 'nome');
        
        $referencias = $this->prepareForDropDown($referencias, array('id', 'nome'));
        $this->_addDropdown('referencia', 'Período:', false, $referencias);

    
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
