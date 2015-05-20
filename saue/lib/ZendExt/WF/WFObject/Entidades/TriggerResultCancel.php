<?php

class ZendExt_WF_WFObject_Entidades_TriggerResultCancel extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerResultCancel';
        
        /*
         * No tiene mas informacion
         */
    }

    public function clonar() {
        return;
    }

}

?>
