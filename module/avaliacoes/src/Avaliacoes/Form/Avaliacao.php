<?php

 namespace Avaliacoes\Form;
 
use Application\Form\Base as BaseForm;
 
 class Avaliacao extends BaseForm
 {
     
    /**
     * Sets up generic form.
     * 
     * @access public
     * @param array $fields
     * @return void
     */
   public function __construct($name)
    {

        parent::__construct($name);  

        $opcoes = array(
                    '1' => ' Supera as expectativas', 
                    '2' => ' Atende plenamente',
                    '3' => ' Atende parcialmente',
                    '4' => ' Não atende'
                );
        
        $this->_addRadio('rotinas', '1. Domínio das rotinas e das tarefas', true, $opcoes);    
        $this->genericTextArea('obs_rotinas', '', false);

        $this->_addRadio('aprendizado', '2. Facilidade no aprendizado', true, $opcoes);    
        $this->genericTextArea('obs_aprendizado', '', false);

        $this->_addRadio('apresentacao', '3. Apresentação pessoal', true, $opcoes);    
        $this->genericTextArea('obs_apresentacao', '', false);

        $this->_addRadio('organizacao', '4. Organização', true, $opcoes);    
        $this->genericTextArea('obs_organizacao', '', false);

        $this->_addRadio('interesse', '5. Interesse', true, $opcoes);    
        $this->genericTextArea('obs_interesse', '', false);

        $this->_addRadio('comunicacao', '6. Comunicação', true, $opcoes);    
        $this->genericTextArea('obs_comunicacao', '', false);

        $this->_addRadio('adaptacao', '7. Adaptação às normas e regras da empresa', true, $opcoes);    
        $this->genericTextArea('obs_adaptacao', '', false);

        $this->_addRadio('acatar', '8. Capacidade de acatar orientações', true, $opcoes);    
        $this->genericTextArea('obs_acatar', '', false);

        $this->_addRadio('iniciativa', '9. Iniciativa', true, $opcoes);    
        $this->genericTextArea('obs_iniciativa', '', false);

        $this->_addRadio('relacionamento', '10. Relacionamento interpessoal', true, $opcoes);    
        $this->genericTextArea('obs_relacionamento', '', false);

        $this->_addRadio('responsabilidade', '11. Responsabilidade', true, $opcoes);    
        $this->genericTextArea('obs_responsabilidade', '', false);

        $this->_addRadio('motivacao', '12. Motivação/ satisfação no trabalho', true, $opcoes);    
        $this->genericTextArea('obs_motivacao', '', false);

        $this->_addRadio('assiduidade', '13. Assiduidade', true, $opcoes);    
        $this->genericTextArea('obs_assiduidade', '', false);

        $this->_addRadio('pontualidade', '14. Pontualidade', true, $opcoes);    
        $this->genericTextArea('obs_pontualidade', '', false);

        $this->_addRadio('cuidado', '15. Cuidado, zelo com os equipamentos / patrimônio', true, $opcoes);    
        $this->genericTextArea('obs_cuidado', '', false);        

        $this->genericTextArea('comentarios', 'Comentários/ ações e propostas de melhorias: ', false);

        //parecer_coordenador
        $this->_addDropdown('parecer_coordenador', 'Parecer do coordenador:', true, array('' => 'Selecione', 'Efetivar' => 'Efetivar', 'Dispensar' => 'Dispensar'));

        //parecer_rh
        $this->genericTextArea('parecer_rh', 'Parecer Gestão de Pessoas/RH: ', false);

        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }


 }
