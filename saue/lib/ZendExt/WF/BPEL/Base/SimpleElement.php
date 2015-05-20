<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SimpleElement
 *
 * 
 */
abstract class ZendExt_WF_BPEL_Base_SimpleElement extends ZendExt_WF_BPEL_Base_XMLElement {
    //put your code here
    protected $value;
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }
    public function setValue($_value) {
        $this->value = $_value;
    }
    public function getValue() {
        return $this->value;
    }
    public function toArray() {
        $array = array(
            0   => $this->tagName,
            1   => $this->value   
            );
        return $array;
    }
    public function isEmpty() {
        return empty($this->value);
    }
    
    public function clonar() {
        $classPreffix = "ZendExt_WF_WFObject_Entidades_";
        $classSuffix = $this->getTagName();
        $className = $classPreffix.$classSuffix;
        $classInstance = new $className($this->parent);        
        $classInstance->setValue($this->getValue());
        return $classInstance;
    }
}

?>
