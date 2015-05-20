<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Implementation extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    public function desassembleClass() {
        $objTypeImplementation = $this->object->getSelectedItem();
        if ($objTypeImplementation instanceof ZendExt_WF_WFObject_Entidades_No) {
            $myNo = new ZendExt_WF_WFTranslatorXPDL_Clases_No($objTypeImplementation);
            $this->addAttribute($myNo);
            return;
        }
        if ($objTypeImplementation instanceof ZendExt_WF_WFObject_Entidades_Task) {
            $myTask = new ZendExt_WF_WFTranslatorXPDL_Clases_Task($objTypeImplementation);
            $this->addAttribute($myTask);
            return;
        }
        if ($objTypeImplementation instanceof ZendExt_WF_WFObject_Entidades_Reference) {
            $myReference = new ZendExt_WF_WFTranslatorXPDL_Clases_Reference($objTypeImplementation);
            $this->addAttribute($myReference);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Implementation");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    public function fromXPDL($objectTag) {
        /*
         * Must contain only one child, but (I) won't check that.
         */

        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $node = $objectTag->childNodes->item($i);
            $nodeName = $node->nodeName;
            
            /*
             * items = ['No','Task',...];
             */
            $this->object->selectItem($nodeName);
            
            /*
             * la manejadora del tipo de implementacion
             */
            $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
            $className = $prefClassName . $nodeName;
            
            /*
             * instanciacion de la clase manejadora
             */
            $newTag = new $className($this->object->getSelectedItem(),FALSE);
            
            
            /*
             * llamada recurrente
             */
            $newTag->fromXPDL($node);
        }
    }

}

?> 