
<?php

class ZendExt_WF_WFObject_Entidades_Form extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Form';
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_FormLayout($this));
        return;
    }

    public function clonar() {

        return;
    }

    public function getFormLayout() {
        return $this->get('FormLayout');
    }

    public function toArray() {
        $array = array(
            'FormLayout' => $this->getFormLayout()->toArray()
        );
        return $array;
    }

}

?>
