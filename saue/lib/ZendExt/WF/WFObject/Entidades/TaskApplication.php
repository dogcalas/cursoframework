<?php

class ZendExt_WF_WFObject_Entidades_TaskApplication extends ZendExt_WF_WFObject_Base_Complex {

    private $PackageRef;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskApplication';
    }

    /*
     * Setters
     */
    public function setPackageRef($_packageRef) {
        $this->PackageRef = $_packageRef;
    }

    /*
     * Getters
     */
    public function getPackageRef() {
        return $this->PackageRef;
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getParametersType() {
        return $this->get('ParametersType');
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('ParametersType', array(
                    new ZendExt_WF_WFObject_Entidades_ActualParameters($this),
                    new ZendExt_WF_WFObject_Entidades_DataMapping($this)
                        ), $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'PackageRef' => $this->getPackageRef(),
            'ParametersType' => $this->getParametersType()->getSelectedItem(),
            'Description' => $this->getDescription()->toArray()
        );
        return $array;
    }

}

?>
