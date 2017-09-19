<?php

 namespace Diario\Form;
 
use Application\Form\Base as BaseForm;
 
 class PesquisarAjuda extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name, $serviceLocator, $usuario)
    {
        if($serviceLocator)
           $this->setServiceLocator($serviceLocator);

        parent::__construct($name);  
        //matricula
        $this->genericTextInput('matricula', 'Matrícula:', false, 'Númeroda matrícula');

        //funcionario
        $this->genericTextInput('nome_funcionario', 'Funcionário:', false, 'Nome do funcionário');

        //unidade de origem
        $funcionario = $this->serviceLocator->get('Funcionario')->getFuncionario($usuario['funcionario']);
        $unidades = $this->serviceLocator->get('Unidade')->getRecordsFromArray(array('empresa' => $funcionario['id_empresa']), 'nome');
        $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));
        $this->_addDropdown('unidade', 'Unidade de origem:', false, $unidades);

        //data
        $this->genericTextInput('inicio', 'Data de início, de:', false);
        $this->genericTextInput('fim', 'Até:', false);
            

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }

    public function setData($dados){
        //$dados['inicio'] = parent::converterData($dados['inicio']);
        //$dados['fim'] = parent::converterData($dados['fim']);

        parent::setData($dados);
    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        return $dados;
    }

 }
