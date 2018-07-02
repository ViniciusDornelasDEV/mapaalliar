<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class Premissas extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $todos = false)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');

        $preparedArray = array('' => '-- selecione --');
        
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'), $preparedArray);

        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => '-- Selecione --'));
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        if(isset($dados['empresa']) && !empty($dados['empresa'])){
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade')->setAttribute('options', $unidades);
        }
        return parent::setData($dados);
    }

 }
