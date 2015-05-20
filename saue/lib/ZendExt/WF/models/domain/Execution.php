<?php

class Execution extends BaseExecution {

    public function setUp() {
        parent::setUp();
        $this->hasOne("WorkFlow", array('local' => 'workflow_id', 'foreign' => 'workflow_id'));
    }

    public function GetLLave() {
        
    }

    public static function Buscar($execution_id) {
        $temp = Doctrine::getTable('Execution')->find($execution_id);
        return $temp;
    }
    
    public static function BuscarEjecucionWorkflow($execution_id, $workflowId) {
        $query = Doctrine_Query::create();
        $temp = $query->from('Execution e')->where("e.execution_id= ? AND e.workflow_id= ?", array($execution_id, $workflowId))->execute();
        return $temp;
    }

    public function GetTodos() {
        $query = new Doctrine_Query ();
        $result = $query->from('Execution')->execute();
        return $result->toArray();
    }

    public function GetPorLimite($limite = 10, $inicio = 0) {
        $query = new Doctrine_Query ();
        $result = $query->from('Execution')->limit($limite = 10)->offset($inicio = 0)->execute();
        return $result->toArray();
    }

    public function eliminarExecution($id) {
        $query = Doctrine_Query::create();
        $query->delete()->from('Execution e')->where("e.execution_id=$id OR e.execution_parent=$id")->execute();
    }

    public static function getDatos($execution_id) {
        $query = Doctrine_Query::create();
        $temp = $query->from('Execution e')->where("e.execution_id= ?", array($execution_id))->execute();
        return $temp;
    }

    public static function obtenerUltimoId() {
        $query = Doctrine_Query::create();
        $result = $query->select('max(e.execution_id)')
                        ->from('Execution e')
                        ->execute()->toArray();
        return $result[0]['max'];
    }

}

//fin clase 
?>


