<?php

class ZendExt_WF_WFObject_Entidades_ArrayType extends ZendExt_WF_WFObject_Base_SimpleElement {

    private $LowerIndex;
    private $UpperIndex;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "ArrayType";
    }

    public function clonar() {
        return;
    }

    public function getUpperIndex() {
        return $this->UpperIndex;
    }

    public function setUpperIndex($param) {
        $this->UpperIndex = $param;
    }

    public function getLowerIndex() {
        return $this->LowerIndex;
    }

    public function setLowerIndex($param) {
        $this->LowerIndex = $param;
    }
    
    
    
    public function toArray() {
        $array = array(
            'UpperIndex' => $this->getUpperIndex(),
            'LowerIndex' => $this->getLowerIndex(),
        );
        return $array;
    }

}

?>
