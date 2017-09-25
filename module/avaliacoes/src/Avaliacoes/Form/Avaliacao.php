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

        $opcoes = array('A' => ' Atende', 'N' => ' Não atende');
        
        $this->_addRadio('organizacao_controle', '1. Organização e controle dos materiais.', true, $opcoes);    
        $this->genericTextArea('obs_organizacao_controle', '', false);

        $this->_addRadio('identificacao', '2. Conferência da identificação do cliente.', true, $opcoes);    
        $this->genericTextArea('obs_identificacao', '', false);
        
        $this->_addRadio('anotacao_controles', '3. Anotação dos controles diários e semanais.', true, $opcoes);    
        $this->genericTextArea('obs_anotacao_controles', '', false);
        
        $this->_addRadio('organizacao_higiene', '4. Organização e Higiene da sala de exames.', true, $opcoes);    
        $this->genericTextArea('obs_organizacao_higiene', '', false);
        
        $this->_addRadio('ambiente_trabalho', '5. Organização do ambiente de trabalho.', true, $opcoes);    
        $this->genericTextArea('obs_ambiente_trabalho', '', false);
        
        $this->_addRadio('questionarios', '6. Conferência dos termos e preenchimentos dos questionários.', true, $opcoes);    
        $this->genericTextArea('obs_questionarios', '', false);
        
        $this->_addRadio('agilidade_qualidade', '7. Agilidade X Qualidade.', true, $opcoes);    
        $this->genericTextArea('obs_agilidade_qualidade', '', false);
        
        $this->_addRadio('epi', '8. Uso de EPI.', true, $opcoes);    
        $this->genericTextArea('obs_epi', '', false);
        
        $this->_addRadio('pastas', '9. Organização das pastas / fluxo de atendimento.', true, $opcoes);    
        $this->genericTextArea('obs_pastas', '', false);
                
        $this->_addRadio('pontualidade', '10. Pontualidade.', true, $opcoes);    
        $this->genericTextArea('obs_pontualidade', '', false);
        
        $this->_addRadio('assiduidade', '11. Assiduidade.', true, $opcoes);    
        $this->genericTextArea('obs_assiduidade', '', false);
        
        $this->_addRadio('equipe', '12. Trabalho em Equipe.', true, $opcoes);    
        $this->genericTextArea('obs_equipe', '', false);
        
        $this->_addRadio('empatia', '13. Empatia.', true, $opcoes);    
        $this->genericTextArea('obs_empatia', '', false);
        
        $this->_addRadio('flexibilidade', '14. Flexibilidade.', true, $opcoes);    
        $this->genericTextArea('obs_flexibilidade', '', false);
        
        $this->_addRadio('proatividade', '15. Proatividade.', true, $opcoes);    
        $this->genericTextArea('obs_proatividade', '', false);

        $this->_addRadio('interpessoal', '16. Bom relacionamento interpessoal.', true, $opcoes);    
        $this->genericTextArea('obs_interpessoal', '', false);
        
        $this->_addRadio('atencao_foco', '17. Atenção e foco nas tarefas realizadas.', true, $opcoes);    
        $this->genericTextArea('obs_atencao_foco', '', false);
        
        $this->_addRadio('interesse_tarefas', '18. Interesse em executar as tarefas.', true, $opcoes);    
        $this->genericTextArea('obs_interesse_tarefas', '', false);
        
        $this->_addRadio('unhas_cabelos', '19. Unhas e cabelos de acordo com padrão. ', true, $opcoes);    
        $this->genericTextArea('obs_unhas_cabelos', '', false);
        
        $this->_addRadio('cracha', '20. Uso correto do crachá.', true, $opcoes);    
        $this->genericTextArea('obs_cracha', '', false);
        
        $this->_addRadio('vestimenta', '21. Uso correto da vestimenta.', true, $opcoes);    
        $this->genericTextArea('obs_vestimenta', '', false);
        
        $this->_addRadio('uniforme', '22. Uso do uniforme.', true, $opcoes);    
        $this->genericTextArea('obs_uniforme', '', false);
        
        $this->_addRadio('simpatia_leveza', '23. Atende o paciente com simpatia e leveza.', true, $opcoes);    
        $this->genericTextArea('obs_simpatia_leveza', '', false);
        
        $this->_addRadio('simpatia_cordialidade', '24. Cumprimenta o paciente com simpatia e cordialidade.', true, $opcoes);    
        $this->genericTextArea('obs_simpatia_cordialidade', '', false);
        
        $this->_addRadio('agradece', '25. Agradece o paciente.', true, $opcoes);    
        $this->genericTextArea('obs_agradece', '', false);
        

        $this->genericTextArea('observacoes', 'PONTOS POSITIVOS', false);
        
        $this->setAttributes(array(
            'role'   => 'form'
        ));

    }


 }
