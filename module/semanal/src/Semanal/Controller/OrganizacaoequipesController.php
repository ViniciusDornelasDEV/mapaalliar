<?php

namespace Semanal\Controller;

use Application\Controller\BaseController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

class OrganizacaoequipesController extends BaseController
{


    public function pesquisarAction(){
        $this->layout('layout/gestor');
        
        


        return new ViewModel();
    }

    public function visualizarAction(){
        $this->layout('layout/gestor');
        
        return new ViewModel();
    }


}

