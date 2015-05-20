<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SimpleChoice
 *
 * 
 */
class ZendExt_WF_BPEL_Base_SimpleChoice extends ZendExt_WF_BPEL_Base_Choice{
    public function __construct($tagName, $choices, $parent, $selectedIndex = -1) {
        parent::__construct($tagName, $parent, $selectedIndex);
        
        foreach ($choices as $value) {
            self::addChoice($value);
        }        
    }

    //put your code here
    public function addChoice($choice) {
        if(is_string($choice)){
            $this->add($choice);
        }
    }
    public function selectItem($itemName) {
        $i = 0;
        $valSelected = FALSE;
        foreach ($this->choices as $value) {
            if ($value == $itemName) {
                $this->select($i);
                $valSelected = TRUE;
                break;
            }
            else $i++;
        }
        if(!$valSelected){
            throw new Exception('La opcion ' . $itemName . ' no es valida para (la etiqueta)/(el objeto) ' . $this->tagName);
        }
        else return;
    }
}

?>
