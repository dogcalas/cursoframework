<?php

class ZendExt_WF_WFObject_Entidades_ActivitySet extends ZendExt_WF_WFObject_Base_Complex {

    private $AdHoc;
    private $AdHocOrdering;
    private $AdHocCompletionCondition;
    private $DefaultStartActivityId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ActivitySet';
    }

    /*
     * Setters
     */

    public function setAdHocOrdering($_adHocOrdering) {
        $this->AdHocOrdering->selectItem($_adHocOrdering);
    }

    public function setStartQuantity($_startQuantity) {
        $this->startQuantity = $_startQuantity;
    }

    public function setAdHoc($_adHoc) {
        $this->AdHoc = $_adHoc;
    }

    public function setDefaultStartActivityId($param) {
        $this->DefaultStartActivityId = $param;
    }

    public function setAdHocCompletionCondition($_adHocCompletionCondition) {
        $this->AdHocCompletionCondition = $_adHocCompletionCondition;
    }

    /*
     * Getters
     */

    public function getAdHoc() {
        return $this->AdHoc;
    }

    public function getAdHocOrdering() {
        return $this->AdHocOrdering->getSelectedItem();
    }

    public function getAdHocCompletionCondition() {
        return $this->AdHocCompletionCondition;
    }

    public function getDefaultStartActivityId() {
        return $this->DefaultStartActivityId;
    }

    public function getActivities() {
        return $this->get('Activities');
    }

    public function getTransitions() {
        return $this->get('Transitions');
    }

    public function getObject() {
        return $this->get('Object');
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Activities($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Transitions($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));

        $adHocOrderingChoices = array('Sequential', 'Parallel');
        $this->AdHocOrdering = new ZendExt_WF_WFObject_Base_SimpleChoice('AdHocOrdering', $adHocOrderingChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'AdHoc' => $this->getAdHoc(),
            'AdHocOrdering' => $this->getAdHocOrdering(),
            'AdHocCompletionCondition' => $this->getAdHocCompletionCondition(),
            'DefaultStartActivityId' => $this->getDefaultStartActivityId(),
            
            'Activities' => $this->getActivities()->toArray(),
            'Transitions' => $this->getTransitions()->toArray(),
            'Object' => $this->getObject()->toArray()
        );
        return $array;
    }

}

?>
