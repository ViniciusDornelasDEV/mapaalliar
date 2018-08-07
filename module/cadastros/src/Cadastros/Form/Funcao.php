<?php

 namespace Cadastros\Form;
 
 use Application\Form\Base as BaseForm; 

 class Funcao extends BaseForm {
     
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
        $this->_addDropdown('area', '* Área:', true, $areas, 'carregarSetor(this.value, "C");');

        //setor
        $setores = array('' => '-- Selecione --');
        $this->_addDropdown('setor', '* Setor:', true, $setores);

        //nome
        $this->genericTextInput('nome', '* Cargo:', true, 'Nome da função');

        $this->_addDropdown('ativo', 'Ativo:', false, array('' => '--', 'S' => 'Ativo', 'N' => 'Inativo'));
        
        $this->setAttributes(array(
            'class'  => 'form-inline'
        ));
        
    }

    public function setSetorByArea($idArea, $todos = 'N', $idUnidade = false){
        //buscar setores
        $params = array('area' => $idArea);
        if($idUnidade != 'false'){
            $params['unidade'] = $idUnidade;
        }
        
        $setores = $this->serviceLocator->get('Setor')->getSetores($params);
        
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
