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
        
        //unidade de origem
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'));
        $this->_addDropdown('empresa_apoio', 'Empresa de apoio:', false, $empresas, 'carregarUnidadeDestino(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade_destino', 'Unidade de apoio:', false, array('' => '-- Selecione --'));
        
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
        if(isset($dados['empresa_apoio']) && !empty($dados['empresa_apoio'])){
            //carregar unidades da empresa
            $unidades = $this->serviceLocator->get('Unidade')->getRecords($dados['empresa_apoio'], 'empresa', array('*'), 'nome');
            $unidades = $this->prepareForDropDown($unidades, array('id', 'nome'));

            //Setando valores
            $this->get('unidade_destino')->setAttribute('options', $unidades);
        }
        parent::setData($dados);
    }

    public function getData($flag = 17){
        $dados = parent::getData();
        $dados['inicio'] = parent::converterData($dados['inicio']);
        $dados['fim'] = parent::converterData($dados['fim']);

        return $dados;
    }

 }
