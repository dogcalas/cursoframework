<?php

class ZendExt_WF_WFObject_Entidades_NodeGraphicsInfos extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'NodeGraphicsInfos';
    }

    public function clonar() {
        return NULL;
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_NodeGraphicsInfo($this);
    }


    
    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('NodeGraphicsInfos' => $result);
        return $array;
    }

}

?>
