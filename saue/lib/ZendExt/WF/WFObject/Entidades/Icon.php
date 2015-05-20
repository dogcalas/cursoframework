<?php

class ZendExt_WF_WFObject_Entidades_Icon extends ZendExt_WF_WFObject_Base_Complex {

    private $XCOORD;
    private $YCOORD;
    private $WIDTH;
    private $HEIGHT;
    private $SHAPE;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Icon";
    }

    /*
     * Getters
     */

    public function getXCOORD() {
        return $this->XCOORD;
    }

    public function getYCOORD() {
        return $this->YCOORD;
    }

    public function getWIDTH() {
        return $this->WIDTH;
    }

    public function getHEIGHT() {
        return $this->HEIGHT;
    }

    public function getSHAPE() {
        return $this->SHAPE->getSelectedItem();
    }

    /*
     * Setters
     */

    public function setXCOORD($_xcoord) {
        $this->XCOORD = $_xcoord;
    }

    public function setYCOORD($_ycoord) {
        $this->YCOORD = $_ycoord;
    }

    public function setWIDTH($_width) {
        $this->WIDTH = $_width;
    }

    public function setHEIGHT($_height) {
        $this->HEIGHT = $_height;
    }

    public function setSHAPE($_shape) {
        $this->SHAPE->selectItem($_shape);
    }

    /*
     * Abstractions
     */
    public function fillStructure() {
        $shapeChoices = array('RoundRectangle', 'Rectangle', 'Ellipse', 'Diamond', 'Ellipse', 'UpTriangle', ' DownTriangle');
        $this->SHAPE = new ZendExt_WF_WFObject_Base_SimpleChoice('SHAPE', $shapeChoices, NULL);
        return;
    }

    public function clonar() {
        return;
    }
    
    public function toArray() {
        $array = array(
            'XCOORD' => $this->getXCOORD(),
            'YCOORD' => $this->getYCOORD(),
            'WIDTH' => $this->getWIDTH(),
            'HEIGHT' => $this->getHEIGHT(),
            'SHAPE' => $this->getSHAPE()
        );
        return $array;
    }

}

?>
