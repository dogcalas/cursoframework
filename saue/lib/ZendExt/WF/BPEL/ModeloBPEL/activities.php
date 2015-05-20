<?php

class ZendExt_WF_BPEL_ModeloBPEL_activities extends ZendExt_WF_BPEL_Base_Collections {

    public function __construct($parent) {

        parent::__construct($parent, 'activities');
    }

    public function clonar() {
        $clone = new ZendExt_WF_BPEL_ModeloBPEL_activities(null);
        /*for ($i = 0; $i < ; $i++) {
            
        }*/
        return;
    }

    public function createObject() {
        return new ZendExt_WF_BPEL_ModeloBPEL_activity($this);
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('Activities' => $result);
        return $array;
    }

}

?>
