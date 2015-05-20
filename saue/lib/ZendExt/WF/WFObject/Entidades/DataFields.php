<?php

class ZendExt_WF_WFObject_Entidades_DataFields extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'DataFields';
    }

    public function clonar() {
        $clone = new ZendExt_WF_WFObject_Entidades_DataFields($this->parent);
        for ($i = 0; $i < $this->count(); $i++) {
            $clone->add($this->get($i)->clonar());
        }
        return $clone;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_DataField($this);
    }
    
     public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('DataFields' => $result);
        return $array;
    }

}

?>
