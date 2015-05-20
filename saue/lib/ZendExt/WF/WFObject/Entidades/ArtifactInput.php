<?php

class ZendExt_WF_WFObject_Entidades_ArtifactInput extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    private $artifactId;
    private $requiredForStart;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ArtifactInput';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function getArtifactId() {
        return $this->artifactId;
    }

    public function setArtifactId($_artifactId) {
        $this->artifactId = $_artifactId;
    }

    public function getRequiredForStart() {
        return $this->requiredForStart;
    }

    public function setRequiredForStart($_requiredForStart) {
        $this->requiredForStart = $_requiredForStart;
    }
    
    
    public function toArray() {
        $array = array(
            'ArtifactId' => $this->getArtifactId(),
            'RequiredForStart' => $this->getRequiredForStart()
        );
        return $array;
    }

}

?>
