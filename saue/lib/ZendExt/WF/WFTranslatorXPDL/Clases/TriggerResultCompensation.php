<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_TriggerResultCompensation extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

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
        $thisObjectTag = $doc->createElement("TriggerResultCompensation");

        $thisObjectTag->setAttribute('ActivityId', $this->object->getActivityId());


        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}
?>

