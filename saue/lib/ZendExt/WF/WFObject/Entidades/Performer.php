<?php

class ZendExt_WF_WFObject_Entidades_Performer extends ZendExt_WF_WFObject_Base_SimpleElement {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Performer";
    }

    /*
     * Setters
     */
    public function setPerformer($_performer) {
        $this->setValue($_performer);
    }

    /*
     * Getter
     */

    public function getPerformer() {
        return $this->getValue();
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

}

?>
