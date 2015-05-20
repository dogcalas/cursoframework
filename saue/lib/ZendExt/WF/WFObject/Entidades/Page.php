<?php

class ZendExt_WF_WFObject_Entidades_Page extends ZendExt_WF_WFObject_Base_Complex {

    private $Height;
    private $Width;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Page';
    }

    /*
     * Setters
     */
    public function setHeight($_height) {
        $this->Height = $_height;
    }

    public function setWidth($_width) {
        $this->Width = $_width;
    }

    /*
     * Getters
     */
    public function getHeight() {
        return $this->Height;
    }

    public function getWidth() {
        return $this->Width;
    }

    public function getId() {
        return $this->Id;
    }

    public function getName() {
        return $this->Name;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }


    public function toArray() {
        $array = array(
            'Name' => $this->getName(),
            'Id' => $this->getId(),
            'Height' => $this->getHeight(),
            'Width' => $this->getWidth()
        );
        return $array;
    }


    public function fillStructure() {
        
    }


}

?>
