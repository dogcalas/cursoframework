<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TaskApplication extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objTypeTaskApplication = $this->object->getParametersType()->getSelectedItem();

        if ($objTypeTaskApplication instanceof ZendExt_WF_WFObject_Entidades_ActualParameters) {
            $myActualParameters = new ZendExt_WF_WFTranslatorXPDL_Clases_ActualParameters($objTypeTaskApplication);
            $this->addAttribute($myActualParameters);
        } else
        if ($objTypeTaskApplication instanceof ZendExt_WF_WFObject_Entidades_DataMapping) {
            $myDataMapping = new ZendExt_WF_WFTranslatorXPDL_Clases_DataMapping($objTypeTaskApplication);
            $this->addAttribute($myDataMapping);
        }

        $objDescription = $this->object->getDescription();
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $this->addAttribute($myDescription);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TaskApplication");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('PackageRef', $this->object->getPackageRef());

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

            if ($NodeName == 'ActualParameters' || $NodeName == 'DataMapping') {

                $Act_Type = $this->object->getParametersType();
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