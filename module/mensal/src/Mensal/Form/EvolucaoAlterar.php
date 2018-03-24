<?php

 namespace Mensal\Form;
 
use Application\Form\Base as BaseForm;
 
 class EvolucaoAlterar extends BaseForm
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

        //empresa
        $empresas = $this->serviceLocator->get('Empresa')->getRecordsFromArray(array(), 'nome');
        $empresas = $this->prepareForDropDown($empresas, array('id', 'nome'), array('' => '-- selecione --'));
        $this->_addDropdown('empresa', '* Empresa:', true, $empresas, 'carregarUnidade(this.value, "C");');

        //unidade
        $this->_addDropdown('unidade', 'Unidade:', false, array('' => 'Selecione uma empresa'));
 

        //ouro
        $this->genericTextInput('ouro', '* Ouro:', true);
        
        //prata
        $this->genericTextInput('prata', '* Prata:', true);
        
        //bronze
        $this->genericTextInput('bronze', '* Bronze:', true);

        //tipo
        $this->_addDropdown('tipo', '* Tipo:', true, array('EVOLUÇÃO SIGA' => 'EVOLUÇÃO SIGA', 'ONA' => 'ONA'));

        //classificacao_atual
        $this->_addDropdown('classificacao_atual', '* Classificação atual:', true, array('' => '-- Selecione --', 'O' => 'Ouro', 'P' => 'Prata', 'B' => 'Bronze'));
        
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
