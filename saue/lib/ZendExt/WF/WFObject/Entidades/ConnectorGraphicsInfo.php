<?php

class ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfo extends ZendExt_WF_WFObject_Base_Complex {

    private $toolId;
    private $isVisible;
    private $page;
    private $pageId;
    private $style;
    private $borderColor;
    private $fillColor;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ConnectorGraphicsInfo';
    }

    /*
     * Getters
     */
    public function getCoordinates() {
        return $this->get('Coordinates');
    }

    public function getIsVisible() {
        return $this->isVisible;
    }

    public function getToolId() {
        return $this->toolId;
    }

    public function getPage() {
        return $this->page;
    }

    public function getPageId() {
        return $this->pageId;
    }

    public function getStyle() {
        return $this->style;
    }

    public function getBorderColor() {
        return $this->borderColor;
    }

    public function getFillColor() {
        return $this->fillColor;
    }

    /*
     * Setters
     */
    public function setToolId($_toolId) {
        $this->toolId = $_toolId;
    }

    public function setIsVisible($_isVisible) {
        $this->isVisible = $_isVisible;
    }

    public function setPage($_page) {
        $this->page = $_page;
    }

    public function setPageId($_pageId) {
        $this->pageId = $_pageId;
    }

    public function setStyle($_style) {
        $this->style = $_style;
    }

    public function setBorderColor($_borderColor) {
        $this->borderColor = $_borderColor;
    }

    public function setFillColor($_fillColor) {
        $this->fillColor = $_fillColor;
    }

    /*
     * Abstractions
     */
    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Coordinates($this));
        return;
    }

    public function clonar() {
        return;
    }
    
    public function toArray() {
        $array = array(
            'ToolId' => $this->getToolId(),
            'IsVisible' => $this->getIsVisible(),
            'Page' => $this->getPage(),
            'PageId' => $this->getPageId(),
            'Style' => $this->getStyle(),
            'BorderColor' => $this->getBorderColor(),
            'FillColor' => $this->getFillColor(),
            
        );
        return $array;
    }

}

?>
