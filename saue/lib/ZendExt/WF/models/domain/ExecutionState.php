<?php

class ExecutionState extends BaseExecutionState {

    public function setUp() {
        parent::setUp();
        $this->hasOne("Node", array('local' => 'node_id', 'foreign' => 'node_id'));
    }

    public function GetLLave() {
        /* $query = new Doctrine_Query (); 
          $result = $query ->select('MAX('execution_id')')->from('ExecutionState')->execute();
          $arr = $result->toArray();
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); */
    }

    public static function Buscar($execution_id) {
        $temp = Doctrine::getTable('ExecutionState')->find($execution_id);
        return $temp->toArray();
    }

    public function GetTodos() {
        $query = new Doctrine_Query ();
        $result = $query->from('ExecutionState')->execute();
        return $result->toArray();
    }

    public function GetPorLimite($limite = 10, $inicio = 0) {
        $query = new Doctrine_Query ();
        $result = $query->from('ExecutionState')->limit($limite = 10)->offset($inicio = 0)->execute();
        return $result->toArray();
    }

    public function eliminarExecutionState($id) {
        $query = Doctrine_Query::create();
        $query->delete()->from('ExecutionState')->where('execution_id=?', $id)->execute();
    }

    public function getDatos($executionId) {
        $query = Doctrine_Query::create();
        $result = $query->select('e.node_id,e.node_state,e.node_activated_from,e.node_thread_id')
                        ->from('ExecutionState e')
                        ->where('e.execution_id=?', $executionId)
                        ->execute()->toArray();
        return $result;
    }

}

//fin clase 
?>


