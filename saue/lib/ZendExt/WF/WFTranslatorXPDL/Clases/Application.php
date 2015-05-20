<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Application extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        $objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);

        $objApplicationType = $this->object->getApplicationType();
        $myApplicationType = new ZendExt_WF_WFTranslatorXPDL_Clases_ApplicationType($objApplicationType);
        $this->addAttribute($myApplicationType); 

        /* $this->object->getApplicationT()->select(1);
          $objApplicationOption = $this->object->getApplicationT()->getSelectedItem();
          //print_r($objApplicationOption);die;
          if ($objApplicationOption instanceof ZendExt_WF_WFObject_Entidades_FormalParameters) {
          $myFormalParameters = new ZendExt_WF_WFTranslatorXPDL_Clases_FormalParameters($objApplicationOption,$bool);
          $this->addAttribute($myFormalParameters);
          }
          if ($objApplicationOption instanceof ZendExt_WF_WFObject_Entidades_ExternalReference) {
          $myExternalReference = new ZendExt_WF_WFTranslatorXPDL_Clases_ExternalReference($objApplicationOption,$bool);
          $this->addAttribute($myExternalReference);
          } */
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Application");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    public function fromXPDL($objectTag) {
        $attribs = $objectTag->attributes;

        if ($attribs->length > 0) {
            for ($i = 0; $i < $attribs->length; $i++) {
                $node = $attribs->item($i);
                $prefFuncName = 'set';
                $fullFuncName = $prefFuncName . $node->nodeName;
                $this->object->$fullFuncName($node->nodeValue);
            }
        }

        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $Nonde = $objectTag->childNodes->item($i);
            $NodeName = $Nonde->nodeName;

            if ($NodeName == 'FormalParameters' || $NodeName == 'ExternalReference') {
                $Act_Type = $this->object->getApplicationT();
                $Act_Type->selectItem($NodeName);
                $cObj = $Act_Type->getSelectedItem();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $ClassName = $prefClassName . $NodeName;
                $newTag = new $ClassName($cObj);
                $newTag->fromXPDL($Nonde);
            } else {
                $prefFuncName = 'get';
                $fullFuncName = $prefFuncName . $NodeName;
                $cObject = $this->object->$fullFuncName();
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $ClassName = $prefClassName . $NodeName;
                $newTag = new $ClassName($cObject);
                $newTag->fromXPDL($Nonde);
            }
        }
    }

}

?>