<?php

class ZendExt_WF_WFObject_Entidades_ActualParameters extends ZendExt_WF_WFObject_Base_Collections {

    public function clonar() {
        return;
    }

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ActualParameters';
    }

    /* public function getActualParameter(){
      return $this->get('ActualParameter');
      } */

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_ExpressionType('ActualParameter', $this);
    }
    
   
    
    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('ActualParameters' => $result);
        return $array;
    }

}

?>
