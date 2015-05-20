<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Role extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    function desassembleClass() {
        /*
         * <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         */
        $count = $this->object->count();
        $allsPrefix = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
        for($i = 0; $i < $count; ++$i){
            $itemPrefix = 'get';
            $obj = $this->object->get($i);
            $functionSuffix = $obj->getTagName();
            $function = $itemPrefix.$functionSuffix;
            $objectInstance = $this->object->$function();
            $myObjectInstanceType = $allsPrefix.$functionSuffix;
            $myObjectInstance = new $myObjectInstanceType($objectInstance);
            $this->addAttribute($myObjectInstance);
        }
        return;
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Role");
        
        $thisObjectTag->setAttribute('portType', $this->object->getPortType());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>