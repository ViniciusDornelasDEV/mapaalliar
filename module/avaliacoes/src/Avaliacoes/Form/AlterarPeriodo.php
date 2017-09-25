<?php

 namespace Avaliacoes\Form;
 
use Application\Form\Base as BaseForm;
 
 class AlterarPeriodo extends BaseForm
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

        //setor
        $setores = $this->serviceLocator->get('Setor')->getRecordsFromArray(array(), 'nome');
        $setores = $this->prepareForDropDown($setores, array('id', 'nome'));
        $this->_addDropdown('setor', 'Setor:', false, $setores);

        //data_inicio
        $this->genericTextInput('data_inicio', '* Início:', true);
        
        //data_fim
        $this->genericTextInput('data_fim', '* Fim:', true);

        //referencia_inicio
        $this->genericTextInput('referencia_inicio', '* Período de referência, de:', true);

        //referencia_fim
        $this->genericTextInput('referencia_fim', '* até:', true);

        //referencia
        $referencias = $this->serviceLocator->get('PilhaAvaliacoesReferencia')->getRecordsFromArray(array(), 'nome');
        
        $referencias = $this->prepareForDropDown($referencias, array('id', 'nome'));
        $this->_addDropdown('referencia', '* Referência:', true, $referencias);

    
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data_inicio'] = parent::converterData($dados['data_inicio']);
        $dados['data_fim'] = parent::converterData($dados['data_fim']);
        $dados['referencia_inicio'] = parent::converterData($dados['referencia_inicio']);
        $dados['referencia_fim'] = parent::converterData($dados['referencia_fim']);
        
        parent::setData($dados);
    }

 }
