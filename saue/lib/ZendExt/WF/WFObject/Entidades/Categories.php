<?php

class ZendExt_WF_WFObject_Entidades_Categories extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Categories';
    }

    public function clonar() {
        $thisClone = new ZendExt_WF_WFObject_Entidades_Categories();
        for($i = 0; $i < $this->count(); $i++){
            $category = $this->get($i);
            $thisClone->add($category->clonar());
        }
        return $thisClone;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_Category($this);
    }
    
   

        public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('Categories' => $result);
        return $array;
    }

}

?>
