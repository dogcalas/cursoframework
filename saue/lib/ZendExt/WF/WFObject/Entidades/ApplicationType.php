<?php

class ZendExt_WF_WFObject_Entidades_ApplicationType extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "ApplicationType";
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $applicationTypeChoices = array(
            new ZendExt_WF_WFObject_Entidades_Ejb($this),
            new ZendExt_WF_WFObject_Entidades_Pojo($this),
            new ZendExt_WF_WFObject_Entidades_Xslt($this),
            new ZendExt_WF_WFObject_Entidades_WebService($this),
            new ZendExt_WF_WFObject_Entidades_BusinessRule($this),
            new ZendExt_WF_WFObject_Entidades_Form($this)
        );
        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('ApplicationType', $applicationTypeChoices, $this));
    }

    public function getApplicationType() {
        return $this->get('ApplicationType');
    }
    
    
    public function toArray() {
        $array = array(
            'ApplicationType' => $this->getApplicationType()->getSelectedItem()->toArray()
        );
        return $array;
    }

}

?>
