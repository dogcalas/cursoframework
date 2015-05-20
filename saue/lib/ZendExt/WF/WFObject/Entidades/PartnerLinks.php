<?php

class ZendExt_WF_WFObject_Entidades_PartnerLinks extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'PartnerLinks';
    }

    public function clonar() {
        return;
    }

    public function getPartnerLink() {
        return $this->get('PartnerLink');
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_PartnerLink($this);
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('PartnerLinks' => $result);
        return $array;
    }

}

?>