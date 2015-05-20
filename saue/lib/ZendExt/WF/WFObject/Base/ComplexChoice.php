<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComplexChoice
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Base_ComplexChoice extends ZendExt_WF_WFObject_Base_Choice {

    public function __construct($tagName, $choices, $parent, $selectedIndex = -1) {
        parent::__construct($tagName, $parent, $selectedIndex);

        foreach ($choices as $value) {
            self::addChoice($value);
        }
    }

    //put your code here
    public function addChoice($choice) {
        if (!is_string($choice)) {
            $this->add($choice);
        }
    }

    public function get($_itemName) {
        try {
            $this->selectItem($_itemName);
            return $this->getSelectedItem();
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function selectItem($itemName) {
        $i = 0;
        $oneSelected = FALSE;
        foreach ($this->choices as $value) {
            if ($value->getTagName() == $itemName) {
                $this->select($i);
                $oneSelected = TRUE;
                break;
            }
            else
                $i++;
        }
        if ($oneSelected === FALSE) {
            //print_r($this->parent->getTagName());die;
            throw new Exception($itemName . ' no es una opción válida.');
        }
    }

    /*
     * Overriden methods
     */

    public function toName() {
        return $this->getSelectedItem()->toName();
    }

}

?>
