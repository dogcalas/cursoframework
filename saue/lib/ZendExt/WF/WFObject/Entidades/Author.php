<?php

 
class ZendExt_WF_WFObject_Entidades_Author extends ZendExt_WF_WFObject_Base_SimpleElement{
      
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Author";
    }

    public function clonar() {
        return;
    }
    
    public function setAuthor($Author) {
        $this->setValue($Author);
    }
    public function getAuthor() {
        return $this->getValue();
    }
    
    
    public function toArray() {
        $array = array(
            'Author' => $this->getAuthor()
        );
        return $array;
    }
}

?>
