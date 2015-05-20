<?php

class ZendExt_WF_WFObject_Entidades_LayoutInfo extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $PixelsPerMillimeter;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "LayoutInfo";
    }

    public function getPixelsPerMillimeter() {
        return $this->PixelsPerMillimeter;
    }

    public function setPixelsPerMillimeter($param) {
        $this->PixelsPerMillimeter = $param;
    }

    public function clonar() {
        return;
    }
    
    public function toArray() {
        $array = array(
            'PixelsPerMillimeter' => $this->getPixelsPerMillimeter()
        );
        return $array;
    }

}

?>
