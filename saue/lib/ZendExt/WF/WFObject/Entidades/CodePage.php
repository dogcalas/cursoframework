<?php

class ZendExt_WF_WFObject_Entidades_CodePage extends ZendExt_WF_WFObject_Base_SimpleElement{
    //put your code here
    
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "CodePage";
    }

    public function clonar() {
        return;
    }
    
    public function setCodePage($CodePage) {
        $this->setValue($CodePage);
    }
    public function getCodePage() {
        return $this->getValue();
    }
    
    
    public function toArray() {
        $array = array(
            'CodePage' => $this->getCodePage()
        );
        return $array;
    }
}

?>
