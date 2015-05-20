<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubFlow
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_SubFlow extends ZendExt_WF_WFObject_Base_Complex {

    private $Execution;
    private $View;
    private $PackageRef;
    private $InstanceDataField;
    private $StartActivitySetId;
    private $StartActivityId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'SubFlow';
    }

    /*
     * Setters
     */

    public function setExecution($_execution) {
        $this->Execution->selectItem($_execution);
    }

    public function setView($_view) {
        $this->View->selectItem($_view);
    }

    public function setPackageRef($_packageRef) {
        $this->PackageRef = $_packageRef;
    }

    public function setStartActivitySetId($_startActivitySetId) {
        $this->StartActivitySetId = $_startActivitySetId;
    }

    public function setActivityId($_startActivityId) {
        $this->StartActivityId = $_startActivityId;
    }

    public function setInstanceDataField($_instanceDataField) {
        $this->InstanceDataField = $_instanceDataField;
    }

    /*
     * Getters
     */

    public function getExecution() {
        return $this->Execution->getSelectedItem();
    }

    public function getView() {
        return $this->View->getSelectedItem();
    }

    public function getPackageRef() {
        return $this->PackageRef;
    }

    public function getStartActivitySetId() {
        return $this->StartActivitySetId;
    }

    public function getStartActivityId() {
        return $this->StartActivityId;
    }

    public function getInstanceDataField() {
        return $this->InstanceDataField;
    }
    
    public function getParameters() {
        return $this->get('Parameters');
    }  
    
    public function getEndPoint() {
        return $this->get('EndPoint');
    }     

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $parameters = new ZendExt_WF_WFObject_Base_ComplexChoice('Parameters', array(
                    new ZendExt_WF_WFObject_Entidades_ActualParameters($this),
                    new ZendExt_WF_WFObject_Entidades_DataMappings($this)
        ), $this);
        
        $this->add($parameters);
        $this->add(new ZendExt_WF_WFObject_Entidades_EndPoint($this));
        
        $executionChoices = array('ASYNCHR', 'SYNCHR');
        $this->Execution = new ZendExt_WF_WFObject_Base_SimpleChoice('Execution', $executionChoices, NULL);
        $viewChoices = array('Collapsed', 'Expanded');
        $this->View = new ZendExt_WF_WFObject_Base_SimpleChoice('View', $viewChoices, NULL);
    }

}

?>
