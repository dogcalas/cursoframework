<?php

class ZendExt_WF_WFObject_Entidades_Application extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Application';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));

        $options = array(
            new ZendExt_WF_WFObject_Entidades_FormalParameters($this),
            new ZendExt_WF_WFObject_Entidades_ExternalReference($this)
        );

        $this->add(new ZendExt_WF_WFObject_Base_ComplexChoice('ApplicationType', $options, $this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        return;
    }

    public function getDescription() {
        return $this->get(Description);
    }

    /* public function getType() {
      return $this->get("Type");
      } */

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function getApplicationType() {
        return $this->get('ApplicationType')->getSelectedItem();
    }
    
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'Description' => $this->getDescription()->toArray(),
            'ApplicationType' => $this->getApplicationType()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray()
        );
        return $array;
    }

}

?>
