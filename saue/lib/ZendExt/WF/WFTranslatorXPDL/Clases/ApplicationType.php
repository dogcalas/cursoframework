<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_ApplicationType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    
    function desassembleClass() {
        $objType = $this->object->getApplicationType()->getSelectedItem(); 
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_Ejb) {
            $myEjb = new ZendExt_WF_WFTranslatorXPDL_Clases_Ejb($objType);
            $this->addAttribute($myEjb);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_Pojo) {
            $myPojo = new ZendExt_WF_WFTranslatorXPDL_Clases_Pojo($objType);
            $this->addAttribute($myPojo);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_Xslt) {
            $myXslt = new ZendExt_WF_WFTranslatorXPDL_Clases_Xslt($objType);
            $this->addAttribute($myXslt);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_Script) {
            $myScript = new ZendExt_WF_WFTranslatorXPDL_Clases_Script($objType);
            $this->addAttribute($myScript);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_WebService) {
            $myWebService = new ZendExt_WF_WFTranslatorXPDL_Clases_WebService($objType);
            $this->addAttribute($myWebService);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_BusinessRule) {
            $myBusinessRule = new ZendExt_WF_WFTranslatorXPDL_Clases_BusinessRule($objType);
            $this->addAttribute($myBusinessRule);
            return;
        }
        if ($objType instanceof ZendExt_WF_WFObject_Entidades_Form) {
            $myForm = new ZendExt_WF_WFTranslatorXPDL_Clases_Form($objType);
            $this->addAttribute($myForm);
            return;
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
