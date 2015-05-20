<?php

class ZendExt_WF_WFObject_Entidades_TypeDeclaration extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {

        parent::__construct($parent);
        $this->tagName = 'TypeDeclaration';
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_DataTypes());
        $this->add(new ZendExt_WF_WFObject_Entidades_Description());
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes());
        return;
    }

    public function getDataTypes() {
        return $this->get('DataTypes');
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function clonar() {
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'DataTypes' => $this->getDataTypes()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray()
        );
        return $array;
    }

}

?>
