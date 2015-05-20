<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of service
 *
 * @author yriverog
 */
class ezcWorkflowServiceTask implements ezcWorkflowServiceObject {
    
    //put your code here
    public function __toString() {
        die('taskservice como cadena');
    }

    public function execute(/*\ezcWorkflowExecution*/ $execution) {
       echo('execute taskservice'); 
    }
}

?>
