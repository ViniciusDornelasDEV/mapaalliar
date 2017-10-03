<?php

namespace Semanal\Model;

use Application\Model\BaseTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Predicate\Expression;

class EscalaFuncionario Extends BaseTable {
    public function salvarEscalas($escalasFunc, $escala){
        $adapter = $this->getTableGateway()->getAdapter();
        $connection = $adapter->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            $this->delete(array('escala' => $escala['id']));
            foreach ($escalasFunc as $key => $valor) {
                $dados = explode('-', $key);
                $dadosInsert = array(
                        'escala'        =>  $escala['id'],
                        'funcionario'   =>  $dados[0],
                        'data'          =>  $escala['ano'].'-'.$escala['mes'].'-'.$dados[1]
                    );
                $this->insert($dadosInsert);
            }

            $connection->commit();
            return true;
        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
        $connection->rollback();
        return false;
    }

}
