<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class PesquisarFuncao extends BaseForm {
     
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

        //area    
        $areas = $this->serviceLocator->get('Area')->getRecordsFromArray(array(), 'nome');
        
        $areas = $this->prepareForDropDown($areas, array('id', 'nome'));
        $this->_addDropdown('area', 'Área:', false, $areas, 'carregarSetor(this.value, "P");');

        //setor
        $this->_addDropdown('setor', 'Setor:', false, array('' => '-- Selecione --'));

        //nome
        $this->genericTextInput('nome', 'Cargo:', false, 'Nome do cargo');

        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function setSetorByArea($idArea, $todos = 'N', $idUnidade){
        $params = array('area' => $idArea);
        if($idUnidade != 'false'){
            $params['unidade'] = $idUnidade;
        }
        //buscar setores
        $setores = $this->serviceLocator->get('Setor')->getSetores($params, true);

        $preparedArray = array('' => '-- selecione --');
        if($todos == 'S'){
            $preparedArray['T'] = 'Todos';
        }

        if($idArea == 'T'){
            $preparedArray = array('T' => 'Todos');
        }

        $setores = $this->prepareForDropDown($setores, array('id', 'nome'), $preparedArray);

        //Setando valores
        $setores = $this->get('setor')->setAttribute('options', $setores);
        return $setores;
    }

    public function setData($dados){
        if(isset($dados['area']) && !empty($dados['area'])){
            $setores = $this->serviceLocator->get('Setor')->getSetores(array('area' => $dados['area']));
            $setores = $this->prepareForDropDown($setores, array('id', 'nome'));

            //Setando valores
            $this->get('setor')->setAttribute('options', $setores);
        }

        return parent::setData($dados);
    }
 }
