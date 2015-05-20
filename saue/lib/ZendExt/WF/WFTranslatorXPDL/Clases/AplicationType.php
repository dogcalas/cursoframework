<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ApplicationType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleXPDL($Expdlversion) {
        return;
    }

    function desassembleClass() {
        $this->object->getAplicationTypeOptions()->select(3);
        $objType=$this->object->getAplicationTypeOptions->getSelectedItem(); 
        print_r($objType);die;
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_BasicType) {
            $myBasicType = new ZendExt_WF_WFTranslatorXPDL_Clases_BasicType($objType);
            $this->addAttribute($myBasicType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_DeclaredType) {
            $myDeclaredType = new ZendExt_WF_WFTranslatorXPDL_Clases_DeclaredType($objType);
            $this->addAttribute($myDeclaredType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_SchemaType) {
            $mySchemaType = new ZendExt_WF_WFTranslatorXPDL_Clases_SchemaType($objType);
            $this->addAttribute($mySchemaType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_ExternalReference) {
            $myExternalReference = new ZendExt_WF_WFTranslatorXPDL_Clases_ExternalReference($objType);
            $this->addAttribute($myExternalReference);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_RecordType) {
            $myRecordType = new ZendExt_WF_WFTranslatorXPDL_Clases_RecordType($objType);
            $this->addAttribute($myRecordType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_EnumerationType) {
            $myEnumerationType = new ZendExt_WF_WFTranslatorXPDL_Clases_EnumerationType($objType);
            $this->addAttribute($myEnumerationType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_ArrayType) {
            $myArrayType = new ZendExt_WF_WFTranslatorXPDL_Clases_ArrayType($objType);
            $this->addAttribute($myArrayType);
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_ListType) {
            $myListType = new ZendExt_WF_WFTranslatorXPDL_Clases_ListType($objType);
            $this->addAttribute($myListType);
        }
    }

    /* public function fromXPDL($objectTag,$myclase)                 


      } */

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ApplicationType");

        //$thisObjectTag->setAttribute('Id', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('Name', 'falta  capturar Id'/* $this->object->getValue() */);
        //$thisObjectTag->setAttribute('ReadOnly', $this->object->getReadOnly() );
        //$thisObjectTag->setAttribute('IsArray', $this->object->getIsArray() );
        //$thisObjectTag->setAttribute('Correlation',$this->object->getCorrelation() );
        //$thisObjectTag->appendChild($doc->createTextNode($this->object->getValue()));

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
