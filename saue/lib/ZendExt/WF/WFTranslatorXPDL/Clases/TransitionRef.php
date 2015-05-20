<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRef extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */
        $count = $this->object->count();
        $allsPrefix = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
        for ($i = 0; $i < $count; ++$i) {
            $itemPrefix = 'get';
            $obj = $this->object->get($i);
            $functionSuffix = $obj->getTagName();
            $function = $itemPrefix . $functionSuffix;
            $objectInstance = $this->object->$function();
            $myObjectInstanceType = $allsPrefix . $functionSuffix;
            $myObjectInstance = new $myObjectInstanceType($objectInstance);
            $this->addAttribute($myObjectInstance);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("TransitionRef");

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

            if ($NodeName == 'Event' || $NodeName == 'BlockActivity' || $NodeName == 'Implementation') {
                $Act_Type = $this->object->getActivityType();
                $Act_Type->selectItem($NodeName); //problema con el metodo selectItem que no retorna el objeto 
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
