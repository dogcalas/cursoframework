<?php

class ZendExt_WF_WFObject_Entidades_Output extends ZendExt_WF_WFObject_Base_Complex {

    private $artifactId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Output';
    }

    /*
     * Getters
     */
    public function getArtifactId() {
        return $this->artifactId;
    }

    /*
     * Setters
     */
    public function setArtifactId($_artifactId) {
        $this->artifactId = $_artifactId;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function toArray() {
        $array = array(
            'ArtifactId' => $this->getArtifactId()
        );
        return $array;
    }

}

?>
