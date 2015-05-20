
<?php

class ZendExt_WF_WFObject_Entidades_DataTypes extends ZendExt_WF_WFObject_Base_ComplexChoice {

    public function __construct($parent, $choices = NULL, $selectedIndex = -1) {
        $options = array();
        if ($choices !== NULL) {
            $options = $choices;
        }else
        $options = array
            (
            /*new ZendExt_WF_WFObject_Entidades_SchemaType($this), //esta etiqueta no tiene ni atributos no secuencias en el XSD
            new ZendExt_WF_WFObject_Entidades_DeclaredType($this),*/
            new ZendExt_WF_WFObject_Entidades_BasicType($this)/*,
            new ZendExt_WF_WFObject_Entidades_ExternalReference($this),
            new ZendExt_WF_WFObject_Entidades_RecordType($this),
            new ZendExt_WF_WFObject_Entidades_UnionType($this),
            new ZendExt_WF_WFObject_Entidades_EnumerationType($this),
            new ZendExt_WF_WFObject_Entidades_ArrayType($this),
            new ZendExt_WF_WFObject_Entidades_ListType($this)*/
        );
        parent::__construct('DataTypes', $options, $parent,$selectedIndex);
    }    

    public function getDataType() {
        return $this->getSelectedItem();
    }
    
    public function clonar() {
        $cloneOptions = array();
        
        foreach ($this->choices as $choiceElement) {
            $cloneOptions[] = $choiceElement->clonar();
        }
        
        $clone = new ZendExt_WF_WFObject_Entidades_DataTypes($this->parent, $cloneOptions,$this->getSelectedIndex());
        return $clone;
    }

}

?>
