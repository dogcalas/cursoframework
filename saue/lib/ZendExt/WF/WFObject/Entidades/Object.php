<?php

class ZendExt_WF_WFObject_Entidades_Object extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Object';
    }

    public function getCategories() {
        return $this->get('Categories');
    }

    public function getDocumentation() {
        return $this->get('Documentation');
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Categories($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Documentation($this));
        return;
    }

    
    public function toArray() {
        $array = array(
            'Id'=>  $this->getId(),
            'Name'=>  $this->getName(),
            'Categories'=>  $this->getCategories()->toArray(),
            'Documentation'=>  $this->getDocumentation()->toArray()
        );
        return $array;
        
    }

}

?>
