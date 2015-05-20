
<?php

class ZendExt_WF_WFObject_Entidades_DataType extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent,$fillStructure = TRUE) {
        parent::__construct($parent,$fillStructure);
        $this->tagName = 'DataType';
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataTypes($this));
        return;
    }

    public function getDataTypes() {
        return $this->get('DataTypes');
    }

    public function clonar() {
        $clone = new ZendExt_WF_WFObject_Entidades_DataType($this->parent, FALSE);
        foreach ($this->items as $cloneElement) {            
            $clone->add($cloneElement->clonar());
        }
        return $clone;
    }

    public function toArray() {
        $array = array(
            'DataTypes' => $this->getDataTypes()->toArray()
        );
        return $array;
    }

}
?>

