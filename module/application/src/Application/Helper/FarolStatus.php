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

class FarolStatus extends AbstractHelper
{
    protected $count = 0;

    public function __invoke($data) {
        $atual = strtotime(date('Y-m-d'));
    	
    	if($atual < strtotime($data)){
    		//a cumprir Vermelho
    		return 'img/vermelho.png';
    	}else{
    		return 'img/verde.png';
    	}
        
    }
}