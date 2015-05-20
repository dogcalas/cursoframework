<?php

class ZendExt_WF_WFObject_Entidades_Assignment extends ZendExt_WF_WFObject_Base_Complex {

    private $AssignTime;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Assignment';
    }

    /*
     * Getters
     */
    public function getAssignTime() {
        return $this->AssignTime->getSelectedItem();
    }
    
    public function getTarget() {
        return $this->get('Target');
    }

    public function getExpression() {
        return $this->get('Expression');
    }
    
    /*
     * Setters
     */

    public function setAssignTime($assignTime) {
        $this->AssignTime->selectItem($assignTime);
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this, 'Target'));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExpressionType($this, 'Expression'));

        $assignTimeChoices = array('Start', 'End');
        $this->AssignTime = new ZendExt_WF_WFObject_Base_SimpleChoice('AssignTime', $assignTimeChoices, NULL);
        return;
    }
    
    public function toArray() {
        $array = array(
            'AssignTime' => $this->getAssignTime(),
        );
        return $array;
    }
}

?>
