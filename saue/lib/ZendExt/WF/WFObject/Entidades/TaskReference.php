<?php

class ZendExt_WF_WFObject_Entidades_TaskReference extends ZendExt_WF_WFObject_Entidades_Task {

    private $TaskRef;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskReference';
    }

    public function getTaskRef() {
        return $this->TaskRef;
    }

    public function setTaskRef($TaskRef) {
        $this->TaskRef = $TaskRef;
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

    public function toArray() {
        $array = array(
            'TaskRef' => $this->getTaskRef()
        );
        return $array;
    }

}

?>
