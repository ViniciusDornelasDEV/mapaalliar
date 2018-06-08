<?php

/**
 * Tech Studio Limited
 * 
 * General application view isMobile helper
 * 
 * @author  Vinicius Silva <vinicius.s.dornelas@gmail.com>
 * @version 1.0
 */

namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;

class Farol extends AbstractHelper
{
    protected $count = 0;

    public function __invoke($dataInicio = false, $dataFim = false, $aceita = false) {
        if($aceita !== false){
            return $this->farolStatus($aceita);
        }else{
            return $this->farolData($dataInicio, $dataFim);
        }
        
    }

    private function farolData($dataInicio, $dataFim){
        $atual = strtotime(date('Y-m-d'));
        
        if($atual < strtotime($dataInicio)){
            //a cumprir Vermelho
            return 'img/vermelho.png';
        }else{
            if($atual <= strtotime($dataFim)){
                // amarelo cumprindo
                return 'img/amarelo.png';
            }

            //verde ferias cumpridas
            return 'img/verde.png';
        }
    }

    private function farolStatus($aceita){
        if($aceita == 'S'){
            return 'img/verde.png';
        }else{
            if($aceita == 'N'){
                return 'img/vermelho.png';
            }else{
                return 'img/amarelo.png';
            }   
        }
    }
}