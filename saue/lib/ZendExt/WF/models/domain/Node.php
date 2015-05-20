<?php

class Node extends BaseNode {

    public function setUp() {
        parent::setUp();
        $this->hasOne("ExecutionState", array('local' => 'node_id', 'foreign' => 'node_id'));
        $this->hasOne("Workflow", array('local' => 'workflow_id', 'foreign' => 'workflow_id'));
    }

    public function GetLLave() {
        /* $query = new Doctrine_Query (); 
          $result = $query ->select('MAX('node_id')')->from('Node')->execute();
          $arr = $result->toArray();
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); */
    }

    public function nodeIdExists($node_id) {
        $found = $this->Buscar($node_id);
        return !empty($found);
    }


    public static function Buscar($node_id) {
        $temp = Doctrine::getTable('Node')->find($node_id);
        return $temp;
    }

    public function GetTodos() {
        $query = new Doctrine_Query ();
        $result = $query->from('Node')->execute();
        return $result->toArray();
    }

    public function GetPorLimite($limite = 10, $inicio = 0) {
        $query = new Doctrine_Query ();
        $result = $query->from('Node')->limit($limite = 10)->offset($inicio = 0)->execute();
        return $result->toArray();
    }

    public static function getNodos($idWorkFlow) {
        $query = Doctrine_Query::create();
        $datos = $query->select('n.node_id,n.node_class,n.node_configuration')
                ->from('node n ')
                ->where('n.workflow_id=?', array($idWorkFlow))
                ->execute()
                ->toArray();
        return $datos;
    }

}

//fin clase 
?>


