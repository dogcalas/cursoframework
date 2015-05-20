<?php

/*
 * Esta clase ha sido generada por Doctrine Generator 
 */

class WorkflowModel extends ZendExt_Model {

    public function init() {
        parent::ZendExt_Model();
    }

    public function Guardar($Workflow) {
        //print_r($Workflow);die('guaradar');
        $Workflow->save();
    }

    public function Eliminar($Workflow) {
        $Workflow->delete();
    }

    public function get_name($workflow_id) {
        $obj = Workflow::Buscar($workflow_id);
        $work['name'] = $obj->workflow_name;
        $work['version'] = $obj->workflow_version;
        return $work;
    }

}

