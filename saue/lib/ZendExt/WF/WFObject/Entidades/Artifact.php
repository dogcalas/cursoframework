<?php

class ZendExt_WF_WFObject_Entidades_Artifact extends ZendExt_WF_WFObject_Base_Complex {

    private $artifactType;
    private $textAnnotation;
    private $group;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Artifact';
    }

    public function getArtifactType() {
        return $this->artifactType->getSelectedItem();
    }

    public function setArtifactType($_artifactType) {
        $this->artifactType->selectItem($_artifactType);
    }

    public function getTextAnnotation() {
        return $this->textAnnotation;
    }

    public function setTextAnnotation($_textAnnotation) {
        $this->textAnnotation = $_textAnnotation;
    }

    public function getGroup0() {
        return $this->group;
    }

    public function setGroup($_group) {
        $this->group = $_group;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Group($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_DataObject($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_NodeGraphicsInfos($this));
        
        $artifactTypeChoices = array('DataObject','Group','Annotation');
        $this->artifactType = new ZendExt_WF_WFObject_Base_SimpleChoice("ArtifactType", $artifactTypeChoices, NULL);
        return;
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getGroup() {
        return $this->get('Group');
    }

    public function getDataObject() {
        return $this->get('DataObject');
    }

    public function getNodeGraphicsInfos() {
        return $this->get('NodeGraphicsInfos');
    }
    
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'ArtifactType' => $this->getArtifactType(),
            'TextAnnotation' => $this->getTextAnnotation(),
            'Group' => $this->getGroup()->toArray(),
            'Object' => $this->getObject()->toArray(),
            'DataObject' => $this->getDataObject()->toArray(),
            'NodeGraphicsInfos' => $this->getNodeGraphicsInfos()->toArray()
        );
        return $array;
    }

}

?>
