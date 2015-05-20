<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubFlow
 *
 * @author yriverog
 */
class ZendExt_WF_WFTranslatorXPDL_Clases_SubFlow extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $parameter = $this->object->getParameters()->getSelectedItem();

        if ($parameter instanceof ZendExt_WF_WFObject_Entidades_ActualParameters) {
            $myActualParameters = new ZendExt_WF_WFTranslatorXPDL_Clases_ActualParameters($parameter);
            $this->addAttribute($myActualParameters);
        } else {
            $myDataMappings = new ZendExt_WF_WFTranslatorXPDL_Clases_DataMappings($parameter);
            $this->addAttribute($myDataMappings);
        }

        $endPoint = $this->object->getEndPoint();
        $myEndPoint = new ZendExt_WF_WFTranslatorXPDL_Clases_EndPoint($endPoint);
        $this->addAttribute($myEndPoint);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("SubFlow");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Execution', $this->object->getExecution());
        $thisObjectTag->setAttribute('View', $this->object->getView());
        $thisObjectTag->setAttribute('PackageRef', $this->object->getPackageRef());
        $thisObjectTag->setAttribute('InstanceDataField', $this->object->getInstanceDataField());
        $thisObjectTag->setAttribute('StartActivitySetId', $this->object->getStartActivitySetId());
        $thisObjectTag->setAttribute('StartActivityId', $this->object->getStartActivityId());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
        return;
    }

}

?>
