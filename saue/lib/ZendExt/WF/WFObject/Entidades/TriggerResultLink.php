<?php

class ZendExt_WF_WFObject_Entidades_TriggerResultLink extends ZendExt_WF_WFObject_Base_Complex {

    private $CatchThrow;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerResultLink';
    }

    public function getCatchThrow() {
        return $this->CatchThrow->getSelectedItem();
    }

    public function setCatchThrow($_catchThrow) {
        $this->CatchThrow->selectItem($_catchThrow);
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
        $catchThrowChoices = array('CATCH','THROW');
        $this->CatchThrow = new ZendExt_WF_WFObject_Base_SimpleChoice('CatchTrow', $catchThrowChoices, NULL);
    }

    public function toArray() {
        $array = array(
            'CatchThrow' => $this->getCatchThrow(),
            'Name' => $this->getName()
        );
        return $array;
    }

}

?>
