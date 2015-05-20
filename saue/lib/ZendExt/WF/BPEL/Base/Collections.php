<?php

abstract class ZendExt_WF_BPEL_Base_Collections extends ZendExt_WF_BPEL_Base_Bfcace {

    //put your code here
    private $indice;

    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
        $this->indice = 0;
    }

    public function add($item) {
        if (is_array($item)) {
            foreach ($item as $value) {
                $this->add($value);
            }
        } else {
            $this->items[$this->indice++] = $item;
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
        return NULL;
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
