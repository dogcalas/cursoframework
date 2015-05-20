<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Route extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    public function desassembleClass() {
        /*
         * <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
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
        $thisObjectTag = $doc->createElement("Route");

        $myGatewayType = $this->object->getGatewayType();
        $thisObjectTag->setAttribute('GatewayType', $myGatewayType);

        if ($myGatewayType === 'Exclusive') {
            $myXORType = $this->object->getXORType();
            $thisObjectTag->setAttribute('XORType', $myXORType);

            $myExclusiveType = $this->object->getExclusiveType();
            $thisObjectTag->setAttribute('ExclusiveType', $myExclusiveType);
        }


        $instantiate = $this->object->getInstantiate();
        if (!empty($instantiate)) {
            $thisObjectTag->setAttribute('Instantiate', $instantiate);
        }
        $markerVisible = $this->object->getMarkerVisible();
        if (!empty($markerVisible)) {
            $thisObjectTag->setAttribute('MarkerVisible', $markerVisible);
        }
        $IncomingCondition = $this->object->getInstantiate();
        if (!empty($IncomingCondition)) {
            $$thisObjectTag->setAttribute('IncomingCondition', $IncomingCondition);
        }
        $OutgoingCondition = $this->object->getMarkerVisible();
        if (!empty($OutgoingCondition)) {
            $thisObjectTag->setAttribute('OutgoingCondition', $OutgoingCondition);
        }

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?> 