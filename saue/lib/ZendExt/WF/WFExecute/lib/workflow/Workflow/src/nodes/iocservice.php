<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of iocservice
 *
 * @author yriverog
 */
class ezcWorkflowIOCServiceTask implements ezcWorkflowServiceObject {
    private $IOCService;
    
    public function __construct() {
        $this->IOCService = NULL;
    }

    public function __toString() {
        die('taskiocservice como cadena');
    }

    public function execute(/*\ezcWorkflowExecution*/ $execution) {
        die('execute taskiocservice'); 
    }
    
    public function setIOCservice($_iocService) {
        $this->IOCService = $_iocService;
    }
}

?>
