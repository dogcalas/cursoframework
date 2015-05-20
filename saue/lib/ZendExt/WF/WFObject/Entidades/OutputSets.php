<?php

class ZendExt_WF_WFObject_Entidades_OutputSets extends ZendExt_WF_WFObject_Base_Collections {

    //put your code here
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'OutputSets';
    }

    public function clonar() {
        
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_OutputSet($this);
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('OutputSets' => $result);
        return $array;
    }

}

?>
