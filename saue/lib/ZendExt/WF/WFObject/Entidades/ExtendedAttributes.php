<?php

class ZendExt_WF_WFObject_Entidades_ExtendedAttributes extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ExtendedAttributes';
    }

    public function clonar() {
        return;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_ExtendedAttribute($this);
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('ExtendedAttributes' => $result);
        return $array;
    }

}

?>
