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

    public function __invoke($dataInicio, $dataFim) {
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
}