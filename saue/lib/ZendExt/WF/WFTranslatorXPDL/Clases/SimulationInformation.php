<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_SimulationInformation extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }


    function desassembleClass() {
        $objCost = $this->object->getCost();
        $objTimeEstimation = $this->object->getTimeEstimation();
        
        $myCost = new ZendExt_WF_WFTranslatorXPDL_Clases_Cost($objCost);
        $myTimeEstimation = new ZendExt_WF_WFTranslatorXPDL_Clases_TimeEstimation($objTimeEstimation);
        
        $this->addAttribute($myCost);
        $this->addAttribute($myTimeEstimation);
       
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("SimulationInformation");

        $thisObjectTag->setAttribute('Instantiation', $this->object->getInstantiation());
        
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }


}

?>
