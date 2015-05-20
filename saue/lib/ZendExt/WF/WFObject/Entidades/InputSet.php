<?php

class ZendExt_WF_WFObject_Entidades_InputSet extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    // private $input;
    // private $artifactInput;
    //private $propertyInput;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'InputSet';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Input($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ArtifactInput($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_PropertyInput($this));
        return;
    }

    public function getInput() {
        return $this->get('Input');
    }

    public function getArtifactInput() {
        return $this->get('ArtifactInput');
    }

    public function getPropertyInput() {
        return $this->get('PropertyInput');
    }

    public function toArray() {
        $array = array(
            'Input' => $this->getInput()->toArray(),
            'ArtifactInput' => $this->getArtifactInput()->toArray(),
            'PropertyInput' => $this->getPropertyInput()->toArray()
        );
        return $array;
    }

}

?>
