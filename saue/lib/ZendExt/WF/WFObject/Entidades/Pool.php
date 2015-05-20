<?php

class ZendExt_WF_WFObject_Entidades_Pool extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    private $Orientation;
    private $Process;
    private $Participant;
    private $BoundaryVisible;
    private $MainPool;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Pool';        
        $this->MainPool = FALSE;
    }

    /*
     * Setters
     */
    public function setOrientation($pOrientation) {
        $this->Orientation->selectItem($pOrientation);
    }

    public function setProcess($_process) {
        $this->Process = $_process;
    }

    public function setParticipant($_participant) {
        $this->Participant = $_participant;
    }

    public function setBoundaryVisible($_boundaryVisible) {
        $this->BoundaryVisible = $_boundaryVisible;
    }

    public function setMainPool($_mainPool) {
        $this->MainPool = $_mainPool;
    }

    /*
     * Getters
     */
    public function getOrientation() {
        return $this->Orientation->getSelectedItem();
    }

    public function getProcess() {
        return $this->Process;
    }

    public function getParticipant() {
        return $this->Participant;
    }

    public function getBoundaryVisible() {
        return $this->BoundaryVisible;
    }
    
    public function getMainPool() {
        return $this->MainPool;
    }    

    public function getLanes() {
        return $this->get('Lanes');
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getNodeGraphicsInfos() {
        return $this->get('NodeGraphicsInfos');
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Lanes($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_NodeGraphicsInfos($this));

        $orientationChoices = array('HORIZONTAL', 'VERTICAL');
        $this->Orientation = new ZendExt_WF_WFObject_Base_SimpleChoice('Orientation', $orientationChoices, $this);
    }

    public function toArray() {
        return array(
            $this->getLanes()->toArray(),
            $this->getObject()->toArray(),
            $this->getNodeGraphicsInfos()->toArray()
        );
    }

}

?>
