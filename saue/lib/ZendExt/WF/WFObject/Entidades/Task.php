<?php

class ZendExt_WF_WFObject_Entidades_Task extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Task';
    }

    /*
     * Getters
     */

    public function getTaskType() {
        return $this->get('TaskType');
    }

    public function getId() {
        return $this->parent->getId();
    }

    /*
     * Overriden methods
     */

    public function toName() {
        return $this->getTaskType()->toName();
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $option = array(
            new ZendExt_WF_WFObject_Entidades_TaskService($this),
            new ZendExt_WF_WFObject_Entidades_TaskIOCService($this),
            new ZendExt_WF_WFObject_Entidades_TaskZendAction($this)/*,
            new ZendExt_WF_WFObject_Entidades_TaskReceive($this),
            new ZendExt_WF_WFObject_Entidades_TaskManual($this),
            new ZendExt_WF_WFObject_Entidades_TaskReference($this),
            new ZendExt_WF_WFObject_Entidades_TaskScript($this),
            new ZendExt_WF_WFObject_Entidades_TaskSend($this),
            new ZendExt_WF_WFObject_Entidades_TaskUser($this),
            new ZendExt_WF_WFObject_Entidades_TaskApplication($this)*/
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('TaskType', $option, $this));
        return;
    }

    public function toArray() {
        $array = array(
            'TaskType' => $this->getTaskType()->getSelectedItem()
        );
        return $array;
    }

}

?>
