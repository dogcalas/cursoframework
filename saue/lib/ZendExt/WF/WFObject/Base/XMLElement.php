<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZendExt_WF_WFObject_Base_SimpleElement
 *
 * @author yriverog
 */
abstract class ZendExt_WF_WFObject_Base_XMLElement {

    //put your code here
    protected $tagName;
    protected $parent;
    protected $isRequired;

    public function __construct($parent) {
        $this->parent = $parent;
    }

	/*
	 * Setters
	 * */
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
    

    /*
     * Abstractions and virtual functions
     */
    
    public abstract function clonar();
    public abstract function isEmpty();
    
    public function toArray() {
        return;
    }

    public function toName() {
        return;
    }

}

?>
