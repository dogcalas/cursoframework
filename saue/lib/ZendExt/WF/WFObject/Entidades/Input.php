<?php

class ZendExt_WF_WFObject_Entidades_Input extends ZendExt_WF_WFObject_Base_Complex {

    private $artifactId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Input';
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
    
    public function toArray() {
        $array = array(
            'ArtifactId' => $this->getArtifactId()
        );
        return $array;
    }

}

?>
