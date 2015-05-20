<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EnumerationValue
 *
 * @author yriverog
 */
class ZendExt_WF_WFTranslatorXPDL_Clases_EnumerationValue extends ZendExt_WF_WFTranslatorXPDL_Base_Base{
    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
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
        $thisObjectTag = $doc->createElement("EnumerationValue");
        $thisObjectTag->setAttribute('Name', $this->object->getName());

        /*
         * Posiblemente no contenga nada, ya que en el .xsd se define:
         * <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         */
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }
}

?>
