<?php

class ZendExt_WF_WFObject_Entidades_ParticipantType extends ZendExt_WF_WFObject_Base_Complex {

    private $type;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ParticipantType';
    }

    /*
     * Getters
     */
    public function getType() {
        return $this->type->getSelectedItem();
    }

    /*
     * Setters
     */
    public function setType($_type) {
        $this->type->selectItem($_type);
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $typeChoices = array('RESOURCE_SET', 'RESOURCE', 'ROLE', 'ORGANIZATIONAL_UNIT', 'HUMAN', 'SYSTEM');
        $this->type = new ZendExt_WF_WFObject_Base_SimpleChoice("ParticipantType", $typeChoices, NULL);
        return;
    }

    public function toArray() {
        $array = array(
            'Type' => $this->getType(),
                /* 'ParticipantType'=>  $this->getParticipantType(); */
        );
        return $array;
    }

}

?>
