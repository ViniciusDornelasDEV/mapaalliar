<?php

 namespace Application\Form;
 
 use Application\Form\Base as BaseForm; 

 class PesquisaDash extends BaseForm {
     
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

      
        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', '* Unidade:', true, array('' => '-- Selecione --'));

        $this->genericTextInput('inicio', 'Data de:', false);
        $this->genericTextInput('fim', 'Até:', false);


        $this->setAttributes(array(
            'class'  => 'form-inline'
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

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        return $dados;
    }
 }
