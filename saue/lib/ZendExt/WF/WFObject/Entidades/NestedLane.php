<?php

class ZendExt_WF_WFObject_Entidades_NestedLane extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $LaneId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'NestedLane';
    }

    public function getLaneId() {
        return $this->LaneId;
    }

    public function setLaneId($_laneId) {
        $this->LaneId = $_laneId;
    }

    public function clonar() {
        return;
    }

}

?>
