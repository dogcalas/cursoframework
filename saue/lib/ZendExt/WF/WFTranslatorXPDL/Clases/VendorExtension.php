<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_VendorExtension extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $bool) {
        parent::__construct($object, $bool);
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
        $thisObjectTag = $doc->createElement("VendorExtension");
        
        $thisObjectTag->setAttribute('ToolId', $this->object->getToolId());
        $thisObjectTag->setAttribute('schemaLocation', $this->object->getschemaLocation());
        $thisObjectTag->setAttribute('extensionDescription', $this->object->getextensionDescription());
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
