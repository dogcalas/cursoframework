<?php

class ZendExt_WF_WFObject_Entidades_TriggerResultCompensation extends ZendExt_WF_WFObject_Base_Complex {

    private $ActivityId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerResultCompensation';
    }

    public function getActivityId() {
        return $this->ActivityId;
    }

    public function setActivityId($ActivityId) {
        $this->ActivityId = $ActivityId;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */
        return;
    }

}

?>
