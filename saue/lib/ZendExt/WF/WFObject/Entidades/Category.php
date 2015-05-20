<?php

class ZendExt_WF_WFObject_Entidades_Category extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Category';
    }

    public function clonar() {
        return new ZendExt_WF_WFObject_Entidades_Category(NULL); 
    }

    public function fillStructure() {
        return;
    }
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName()
        );
        return $array;
    }
    
    

}

?>
