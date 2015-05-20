<?php

class ZendExt_WF_BPEL_ModeloBPELXML_process extends ZendExt_WF_BPEL_Base_baseXML {

    public function __construct($bpelObject, $desassembleObject = FALSE) {
        parent::__construct($bpelObject, $desassembleObject);
    }

    public function toXML(DOMDocument $domDocument, $domElement) {
        if ($domElement === null) {
            $domElement = $domDocument->createElement('process');
            $domDocument->appendChild($domElement);
            $attributes = $this->bpelObject->getAttributes();

            if (count($attributes) !== 0) {
                foreach ($attributes as $attribName => $attribValue) {
                    $tempAttribValue = $this->bpelObject->__get($attribName);
                    $domElement->setAttribute($attribName, $tempAttribValue);
                }
            }
            for ($i = 0; $i < count($this->attributes); $i++) {
                $this->attributes[$i]->toXML($domDocument, $domElement);
            }
        }
    }

}

?>