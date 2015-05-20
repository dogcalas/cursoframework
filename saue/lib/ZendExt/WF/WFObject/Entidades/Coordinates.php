<?php

class ZendExt_WF_WFObject_Entidades_Coordinates extends ZendExt_WF_WFObject_Base_Complex {

    private $xCoordinate;
    private $yCoordinate;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Coordinates';
    }

    /*
     * Setters
     */
    public function setXCoordinate($pxCoordinate) {
        $this->xCoordinate = $pxCoordinate;
    }

    public function setYCoordinate($pyCoordinate) {
        $this->yCoordinate = $pyCoordinate;
    }

    /*
     * Getters
     */
    public function getXCoordinate() {
        return $this->xCoordinate;
    }

    public function getYCoordinate() {
        return $this->yCoordinate;
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
            'XCoordinate' => $this->getXCoordinate(),
            'YCoordinate' => $this->getYCoordinate()
        );
        return $array;
    }

}

?>
