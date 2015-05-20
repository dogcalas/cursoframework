<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Service extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }


    function desassembleClass() {        
          $objEndPoint= $this->object->getEndPoint();  
          $myEndPoint = new ZendExt_WF_WFTranslatorXPDL_Clases_EndPoint($objEndPoint);
          $this->addAttribute($myEndPoint);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Service");
        
        $thisObjectTag->setAttribute('ServiceName', $this->object-> getServiceName());
        $thisObjectTag->setAttribute('PortName', $this->object-> getPortName());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

}

?>
