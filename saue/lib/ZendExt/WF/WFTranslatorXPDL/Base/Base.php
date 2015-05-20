<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author yriverog
 */
abstract class ZendExt_WF_WFTranslatorXPDL_Base_Base {

    protected $object;
    protected $attributes;
    private $index;

    public function __construct($object, $dessasemble = TRUE) {
        $this->object = $object;
        $this->attributes = array();
        $this->index = 0;
        if ($object != NULL) {
            if ($dessasemble === TRUE) {
                $this->desassembleClass($this->object);
            }
        }
    }

    public function setObject($object) {
        $this->object = $object;
    }

    public function addAttribute($attribute) {
        if (empty($attribute->object)) {
            return;
        } else {
            if ($attribute->object->isEmpty() === FALSE) {
                $this->attributes[$this->index++] = $attribute;
            }
        }
    }

    public function fromXPDL($objectTag) {
        $attribs = $objectTag->attributes;
        if ($attribs->length > 0) {
            for ($i = 0; $i < $attribs->length; $i++) {
                $node = $attribs->item($i);
                $prefFuncName = 'set';
                $fullFuncName = $prefFuncName . $node->nodeName;
                $this->object->$fullFuncName($node->nodeValue);
            }
        }
        $this->readStructure($objectTag);
        return $this->object;
    }

    public function readStructure($objectTag) {
        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $node = $objectTag->childNodes->item($i);
            if ($node->nodeType == 3) {
                $nodeValue = $node->nodeValue;
                $uno = 'set';
                $dos = 'Value';
                $fullFunc = $uno . $dos;
                $this->object->$fullFunc($nodeValue);
            } else {
                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $nodeName = $node->nodeName;
                $prefFuncName = 'get';
                $fullFuncName = $prefFuncName . $node->nodeName;

                $cObject = $this->object->$fullFuncName();

                $fullClassName = $prefClassName . $nodeName;

                $newTagToRead = new $fullClassName($cObject, FALSE);

                if ($cObject instanceof ZendExt_WF_WFObject_Base_Collections) {
                    $this->treatCollection($cObject, $node);
                } else {
                    $newTagToRead->fromXPDL($node);
                }
            }
        }
    }

    public function treatCollection($collection, $node) {
        for ($i = 0; $i < $node->childNodes->length; $i++) {
            $child = $node->childNodes->item($i);
            if ($child->nodeType === XML_ELEMENT_NODE) {
                
                if($collection->hasDecision()){
                    $nodeName = $child->nodeName;
                    $collection->decide(substr($nodeName, 8));
                }
                
                $collectionObject = $collection->createObject();

                $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                $nodeName = $child->nodeName;
                $fullClassName = $prefClassName . $nodeName;
                
                $newTagToRead = new $fullClassName($collectionObject, FALSE);

                $newTagToRead->fromXPDL($child);                

                $collection->add($collectionObject);
            }
        }
    }

    public abstract function desassembleClass();

    public abstract function toXPDL($doc, &$objectTag);
}

?>
