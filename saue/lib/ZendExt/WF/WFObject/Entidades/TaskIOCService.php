<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskIOCService
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_TaskIOCService extends ZendExt_WF_WFObject_Base_Complex {
    
    private $IOCService;
    
    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskIOCService';
    }
    
    public function toName() {
        return 'IOCServiceTask';
    }
    
    public function setIOCService($_iocService) {
        $this->IOCService = $_iocService;
    }
    
    public function getIOCService() {
        return $this->IOCService;
    }    

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }
}


?>
