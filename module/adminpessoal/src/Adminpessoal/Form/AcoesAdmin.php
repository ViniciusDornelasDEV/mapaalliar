<?php

 namespace Adminpessoal\Form;
 
use Application\Form\NovoAdmin as BaseForm;
 
 class AcoesAdmin extends BaseForm
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

        //tipo
        $tipos = $this->serviceLocator->get('AcaoDisciplinarTipo')->getRecordsFromArray(array());
        $tipos = $this->prepareForDropDown($tipos, array('id', 'nome'));
        $this->_addDropdown('tipo', '* Tipo de ação:', true, $tipos);

        //data_inicio
        $this->genericTextInput('data', '* Data:', true);

        $this->genericTextInput('apontamento', '* Apontamento:', true);

        $this->genericTextInput('orientacao_acao', '* Orientação/ação realizada:', true);

        $this->genericTextInput('planejamento', '* Planejamento:', true);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        $dados['data'] = parent::converterData($dados['data']);
        
        parent::setData($dados);
    }
 }
