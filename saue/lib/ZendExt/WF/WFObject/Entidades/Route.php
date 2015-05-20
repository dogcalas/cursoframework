<?php

class ZendExt_WF_WFObject_Entidades_Route extends ZendExt_WF_WFObject_Base_Complex {

    private $GatewayType;
    private $XORType;
    private $ExclusiveType;
    private $Instantiate;
    private $MarkerVisible;
    private $IncomingCondition;
    private $OutgoingCondition;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Route";
    }

    /*
     * Getters
     */

    public function getGatewayType() {
        return $this->GatewayType->getSelectedItem();
    }

    public function getXORType() {
        return $this->XORType->getSelectedItem();
    }

    public function getExclusiveType() {
        return $this->ExclusiveType->getSelectedItem();
    }

    public function getInstantiate() {
        return $this->Instantiate;
    }

    public function getMarkerVisible() {
        return $this->MarkerVisible;
    }

    public function getIncomingCondition() {
        return $this->IncomingCondition;
    }

    public function getOutgoingCondition() {
        return $this->OutgoingCondition;
    }

    /*
     * Setters
     */

    public function setGatewayType($gt) {
        return $this->GatewayType->selectItem($gt);
    }

    public function setXORType($xortype) {
        return $this->XORType->selectItem($xortype);
    }

    public function setExclusiveType($et) {
        return $this->ExclusiveType->selectItem($et);
    }
    
    public function setInstantiate($_instantiate) {
        $this->Instantiate = $_instantiate;
    }



    public function toArray() {
        $array = array(
            'GatewayType' => $this->getGatewayType(),
            'XORType' => $this->getXORType(),
            'ExclusiveType' => $this->getExclusiveType()
                /* 'Instantiate'=>  $this->getInstantiate(),
                  'MarkerVisible'=>  $this->getMarkerVisible(),
                  'IncomingCondition'=>  $this->getIncomingCondition(),
                  'OutgoingCondition'=>  $this->getOutgoingCondition() */
        );
        return $array;
    }


    public function setMarkerVisible($_markerVisible) {
        $this->MarkerVisible = $_markerVisible;
    }

    public function setIncomingCondition($_incomingCondition) {
        $this->IncomingCondition = $_incomingCondition;
    }

    public function setOutgoingCondition($_outgoingConfition) {
        $this->OutgoingCondition = $_outgoingConfition;
    }    

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $arrayGatewayTypesOptions = array('Exclusive','Inclusive','Complex','Parallel');
        $this->GatewayType = new ZendExt_WF_WFObject_Base_SimpleChoice('GatewayType', $arrayGatewayTypesOptions, $this);

        $arrayXORTypesOptions = array('Data', 'Event');
        $this->XORType = new ZendExt_WF_WFObject_Base_SimpleChoice('XORType', $arrayXORTypesOptions, $this);

        $arrayExclusiveTypesOptions = array('Data', 'Event');
        $this->ExclusiveType = new ZendExt_WF_WFObject_Base_SimpleChoice('ExclusiveType', $arrayExclusiveTypesOptions, $this);
        return;
    }
    
    public function toName() {
        return 'Route';
    }
}

?>
