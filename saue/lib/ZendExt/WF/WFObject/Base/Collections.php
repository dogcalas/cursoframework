<?php

abstract class ZendExt_WF_WFObject_Base_Collections extends ZendExt_WF_WFObject_Base_Bfcace {

    //put your code here
    private $indice;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->indice = 0;
    }

    public function add($item) {        
        if ($this->findById($item->getId()) === NULL) {
            $this->items[$this->indice++] = $item;
        }  else {
            print_r($item);
            throw new Exception('Ya fue adicionado ese elemento.');
        }        
    }

    public function count() {
        return $this->indice;
    }

    public function findById($itemId) {
        foreach ($this->items as $value) {
            if ($value->getId() === $itemId) {
                return $value;
            }
        }
        return null;
    }

    public function get($index) {
        if (array_key_exists($index, $this->items)) {
            return $this->items[$index];
        } else {
            throw new Exception('Out of range.');
        }
    }

    public function isEmpty() {
        return $this->count() === 0;
    }

    public function hasDecision() {
        return FALSE;
    }

    public abstract function createObject();
}

?>
