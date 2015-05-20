<?php

class ZendExt_WF_WFObject_Entidades_Lane extends ZendExt_WF_WFObject_Base_Complex {

    private $ParentLane;
    private $ParentPool;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Lane';
    }

    public function clonar() {
        return;
    }

    public function setParentLane($parameters) {
        $this->ParentLane = $parameters;
    }

    public function setParentPool($parameters) {
        $this->ParentPool = $parameters;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_NodeGraphicsInfos($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Performers($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_NestedLane($this));
        return;
    }

    public function getNodeGraphicsInfos() {
        return $this->get('NodeGraphicsInfos');
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getPerformers() {
        return $this->get('Performers');
    }

    public function getNestedLane() {
        return $this->get('NestedLane');
    }

    public function getParentLane() {
        return $this->ParentLane;
    }

    public function getParentPool() {
        return $this->ParentPool;
    }

    public function toArray() {
        $array = array(
            /*  'LaneId'=>  $this->getId() */
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'ParentLane' => $this->getParentLane(),
            'ParentPool' => $this->getParentPool(),
            'Object' => $this->getObject()->toArray(),
            'NodeGraphicsInfos' => $this->getNodeGraphicsInfos()->toArray(),
            'Performers' => $this->getPerformers()->toArray(),
            'NestedLane' => $this->getNestedLane()->toArray()
        );
        return $array;
    }

}

?>
