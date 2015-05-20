<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * 
 */
abstract class ZendExt_WF_BPEL_Base_Bfcace extends ZendExt_WF_BPEL_Base_XMLElement
{
    protected $items = array();
    protected $myArray = array();

    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);        
    }
    public function getItems(){
        return $this->items;
    }
    public abstract function add($item);
    public abstract function get($index);
    public abstract function count();
}

?>
