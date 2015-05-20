<?php

class ZendExt_WF_WFObject_Entidades_Ejb extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "Ejb";
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_JndiName($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_HomeClass($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Method($this));
        return;
    }

    public function getJndiName() {
        return $this->get('JndiName');
    }

    public function getHomeClass() {
        return $this->get('HomeClass');
    }

    public function getMethod() {
        return $this->get('Method');
    }

    public function toArray() {
        $array = array(
            'JndiName' => $this->getJndiName()->toArray(),
            'HomeClass' => $this->getHomeClass()->toArray(),
            'Method' => $this->getMethod()->toArray()
        );
        return $array;
    }

}

?>
