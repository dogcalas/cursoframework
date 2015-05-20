<?php

class ZendExt_WF_WFObject_Entidades_Participant extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Participant';
    }

    /*
     * Getters
     */
    public function getParticipantType() {
        return $this->get('ParticipantType');
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getExternalReference() {
        return $this->get('ExternalReference');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }


    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'ParticipantType' => $this->getParticipantType()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'ExternalReference' => $this->getExternalReference()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray()
        );
        return $array;
    }


    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ParticipantType($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExternalReference($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        return;
    }

}

?>
