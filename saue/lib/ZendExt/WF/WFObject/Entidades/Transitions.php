<?php

class ZendExt_WF_WFObject_Entidades_Transitions extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Transitions';
    }

    public function clonar() {
        $transitions = new ZendExt_WF_WFObject_Entidades_Transitions($this->parent);
        
        for($i = 0; $i < $this->count(); $i++){
            $transitions->add($this->get($i)->clonar());
        }
        return $transitions;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_Transition($this);
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('Transitions' => $result);
        return $array;
    }

}

?>
