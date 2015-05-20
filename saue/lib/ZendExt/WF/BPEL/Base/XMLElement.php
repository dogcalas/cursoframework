<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**

 *
 * 
 */
abstract class ZendExt_WF_BPEL_Base_XMLElement {

    //put your code here
    protected $tagName;
    protected $parent;
    protected $attributes;

    public function __construct($parent, $tagName = null) {
        $this->parent = $parent;
        $this->tagName = $tagName;

        $this->initAttributes();
    }

    /*
     * Setters
     */

    public function setTagName($ptagName) {
        $this->tagName = $ptagName;
    }

    /*
     * Getters
     */

    public function getTagName() {
        return $this->tagName;
    }

    public function getParent() {
        return $this->parent;
    }
    
    public function __get($attribName){
        if (array_key_exists($attribName, $this->attributes)) {
            return $this->getAttribute($attribName);
        }
    }
    
    private function getAttribute($attrib) {
        return $this->attributes[$attrib]->getValue();
    }

    /*
     * Abstractions and virtual functions
     */

    protected function addAttribute($attrib){
        $this->attributes[$attrib->getTagName()] = $attrib;
    }


    public abstract function clonar();

    public abstract function isEmpty();

    public function toArray() {
        return;
    }

    public function toName() {
        return;
    }

    public function initAttributes() {
        return;
    }
    
    public function getAttributes(){
        return $this->attributes;
    }

}

?>
