<?php

class Workflow extends BaseWorkflow {

    public function setUp() {
        parent::setUp();
        $this->hasMany("Execution", array('local' => 'workflow_id', 'foreign' => 'workflow_id'));
        $this->hasMany("Node", array('local' => 'workflow_id', 'foreign' => 'workflow_id'));
        $this->hasMany("VariableHandler", array('local' => 'workflow_id', 'foreign' => 'workflow_id'));
    }

    public function GetLLave() {
        /* $query = new Doctrine_Query (); 
          $result = $query ->select('MAX('workflow_id')')->from('Workflow')->execute();
          $arr = $result->toArray();
          return (is_array($arr) ? $arr[0]['MAX'] + 1 : 1); */
    }
    
    public function workflowIdExists($workflow_id) {
        $found = $this->Buscar($workflow_id);
        return !empty($found);
    }

    public function Buscar($workflow_id) {
        $temp = Doctrine::getTable('Workflow')->find($workflow_id);
        return $temp;
    }

    public function GetTodos() {
        $query = new Doctrine_Query ();
        $result = $query->from('Workflow')->execute();
        return $result->toArray();
    }

    public function GetPorLimite($limite = 10, $inicio = 0) {
        $query = new Doctrine_Query ();
        $result = $query->from('Workflow')->limit($limite = 10)->offset($inicio = 0)->execute();
        return $result->toArray();
    }

    public static function getCurrentVersion($workflowName) {

        $query = Doctrine_Query::create();
        $version = $query->select('max(w.workflow_version)')->from('workflow w ')->where('w.workflow_name=?', $workflowName)
                        ->execute()->toArray();

        if ($version[0]['max'] != null)
            return $version[0]['max'];

        return 0;
    }

    public static function getWorkFlowId($name, $version) {
        $query = Doctrine_Query::create();
        $version = $query->select('w.workflow_id')->from('workflow w ')->where('w.workflow_name=?', $name)
                        ->addWhere('w.workflow_version=?', $version)
                        ->execute()->toArray();
        return $version[0]['workflow_id'];
    }

    public static function getWorkFlowIdWorkFlow($idWorkFlow) {
        $query = Doctrine_Query::create();
        $datos = $query->select('w.workflow_name,w.workflow_version')
                        ->from('workflow w ')
                        ->where('w.workflow_id=?', $idWorkFlow)
                        ->execute()->toArray();
        return $datos;
    }

}

//fin clase 
?>


