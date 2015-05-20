<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseForCollectionAndComplexElements
 *
 * @author yriverog
 */
abstract class ZendExt_WF_WFObject_Base_Bfcace extends ZendExt_WF_WFObject_Base_XMLElement
{
    protected $items = array();
    protected $myArray = array();

    public function __construct($parent) {
        parent::__construct($parent);        
    }
    public abstract function add($item);
    public abstract function get($index);
    public abstract function count();
}

?>
