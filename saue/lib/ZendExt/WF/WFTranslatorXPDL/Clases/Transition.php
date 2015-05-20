<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Transition extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {        
        $objCondition = $this->object->getCondition();
        $objDescription = $this->object->getDescription();
        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $objAssignments = $this->object->getAssignments();
        $objObject = $this->object->getObject();
        $objConnectorGraphicsInfos = $this->object->getConnectorGraphicsInfos();


        $myCondition = new ZendExt_WF_WFTranslatorXPDL_Clases_Condition($objCondition);
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
        $myAssignments = new ZendExt_WF_WFTranslatorXPDL_Clases_Assignments($objAssignments);
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $myConnectorGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_ConnectorGraphicsInfos($objConnectorGraphicsInfos);

        $this->addAttribute($myCondition);
        $this->addAttribute($myDescription);
        $this->addAttribute($myExtendedAttributes);
        $this->addAttribute($myAssignments);
        $this->addAttribute($myObject);
        $this->addAttribute($myConnectorGraphicsInfos);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Transition");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('From', $this->object->getFrom());
        $thisObjectTag->setAttribute('To', $this->object->getTo());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('Quantity', $this->object->getQuantity());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    //Commented or uncommented?
    public function readStructure($objectTag) {
        if ($objectTag->hasChildNodes()) {
            if ($objectTag->nodeType === XML_ELEMENT_NODE) {
                $condition = $objectTag->getElementsByTagName("Condition");
                if($condition->length !== 0){
                    $this->object->addCondition();
                }
                parent::readStructure($objectTag);
            }
        }
    }

}

?>
