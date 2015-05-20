<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of baseXML
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_Base_baseXML {

    protected $bpelObject;
    protected $attributes;

    public function __construct($bpelObject, $desassembleObject = FALSE) {
        $this->bpelObject = $bpelObject;
        $this->attributes = array();

        if ($desassembleObject) {
            if ($this->bpelObject !== null) {
                $this->desassembleObject();
            }
        }
    }

    public function desassembleObject() {
        $classPreffix = 'ZendExt_WF_BPEL_ModeloBPELXML_';
        $temp = null;
        if ($this->bpelObject instanceof ZendExt_WF_BPEL_Base_Complex) {
            $items = $this->bpelObject->getItems();
            foreach ($items as $key => $object) {
                $classSuffix = $key;
                $className = $classPreffix . $classSuffix;
                $myClassInstance = new $className($object, true);
                $temp = $myClassInstance;
            }
        } else {
            if ($this->bpelObject instanceof ZendExt_WF_BPEL_Base_Collections) {
                $counter = $this->bpelObject->count();
                $_array = array();
                for ($i = 0; $i < $counter; $i++) {
                    $collectionElement = $this->bpelObject->get($i);
                    $collectionElementSuffix = $collectionElement->getTagName();
                    $collectionElementClassName = $classPreffix . $collectionElementSuffix;
                    $collectionElementClassInstance = new $collectionElementClassName($collectionElement, true);
                    $_array[] = $collectionElementClassInstance;
                }
                $temp = $_array;
            } else {
                if ($this->bpelObject instanceof ZendExt_WF_BPEL_Base_Choice) {
                    $selectedObject = $this->bpelObject->getSelectedObject();
                    $className = $classPreffix . $selectedObject->getTagName();
                    $myClassInstance = new $className($selectedObject, true);
                    $temp = $myClassInstance;
                }
            }
        }
        $this->addAttributes($temp);
    }
    
    public function getBPELObject(){
        return $this->bpelObject;
    }

    public function toXML(DOMDocument $domDocument, $domElement) {
        if ($this->bpelObject !== null) {
            $element = $domDocument->createElement($this->bpelObject->getTagName());
            $domElement->appendChild($element);

            $attributes = $this->bpelObject->getAttributes();

            if (count($attributes) !== 0) {
                foreach ($attributes as $attribName => $attribValue) {
                    $tempValue = $this->bpelObject->__get($attribName);
                    $element->setAttribute($attribName, $tempValue);
                }
            }
            
            if ($this->bpelObject instanceof ZendExt_WF_BPEL_Base_SimpleElement) {
                $element->appendChild($domDocument->createTextNode($this->bpelObject->getValue()));
            }

            for ($i = 0; $i < count($this->attributes); $i++) {
                $this->attributes[$i]->toXML($domDocument, $element);
            }
        }
    }

    protected function addAttributes($attribute) {
        if (!empty($attribute)) {
            if (is_array($attribute)) {
                foreach ($attribute as $item) {
                    $this->addAttributes($item);
                }
            } else {
                if ($attribute->getBPELObject() instanceof ZendExt_WF_BPEL_Base_SimpleElement) {
                    $this->attributes[] = $attribute;
                }  else {
                    if (!$attribute->getBPELObject()->isEmpty()) {
                      $this->attributes[] = $attribute; //no estaba  
                    }                    
                }   
            }
        }
    }

    //put your code here
}

?>
