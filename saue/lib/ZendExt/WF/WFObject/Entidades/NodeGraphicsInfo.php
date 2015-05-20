<?php

class ZendExt_WF_WFObject_Entidades_NodeGraphicsInfo extends ZendExt_WF_WFObject_Base_Complex {

    private $ToolId;
    private $IsVisible;
    private $Page;
    private $PageId;
    private $LaneId;
    private $Height;
    private $Width;
    private $BorderColor;
    private $FillColor;
    private $Shape;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'NodeGraphicsInfo';
    }

    /*
     * Getters
     */

    public function getCoordinates() {
        return $this->get('Coordinates');
    }

    public function getToolId() {
        return $this->ToolId;
    }

    public function getPage() {
        return $this->Page;
    }

    public function getPageId() {
        return $this->PageId;
    }

    public function getLaneId() {
        return $this->LaneId;
    }

    public function getHeight() {
        return $this->Height;
    }

    public function getWidth() {
        return $this->Width;
    }

    public function getBorderColor() {
        return $this->BorderColor;
    }

    public function getFillColor() {
        return $this->FillColor;
    }

    public function getShape() {
        return $this->Shape;
    }

    public function getIsVisible() {
        return $this->IsVisible;
    }

    /*
     * Setters
     */

    public function setShape($_shape) {
        $this->Shape = $_shape;
    }

    public function setFillColor($_fillColor) {
        $this->FillColor = $_fillColor;
    }

    public function setBorderColor($_borderColor) {
        $this->BorderColor = $_borderColor;
    }

    public function setWidth($_width) {
        $this->Width = $_width;
    }

    public function setHeight($_height) {
        $this->Height = $_height;
    }

    public function setLaneId($_laneId) {
        $this->LaneId = $_laneId;
    }

    public function setPageId($_pageId) {
        $this->PageId = $_pageId;
    }

    public function setPage($_page) {
        $this->Page = $_page;
    }

    public function setIsVisible($_isVisible) {
        $this->IsVisible = $_isVisible;
    }

    public function setToolId($_toolId) {
        $this->ToolId = $_toolId;
    }


    public function toArray() {
        $array = array(
            'ToolId' => $this->getToolId(),
            'IsVisible' => $this->getIsVisible(),
            'Page' => $this->getPage(),
            'PageId' => $this->getPageId(),
            'LaneId' => $this->getLaneId(),
            'Height' => $this->getHeight(),
            'Width' => $this->getWidth(),
            'BorderColor' => $this->getBorderColor(),
            'FillColor' => $this->getFillColor(),
            'Shape' => $this->getShape(),
            'Coordinates' => $this->getCoordinates()->toArray()
        );
        return $array;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Coordinates($this));
        return;
    }

}

?>
