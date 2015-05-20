<?php

abstract class ZendExt_WF_BPEL_Base_Complex extends ZendExt_WF_BPEL_Base_Bfcace {

    //put your code here
    protected $Id;
    protected $name;

    public function __construct($parent, $tagName = null, $fillStructure = TRUE) {
        parent::__construct($parent, $tagName);

        if ($fillStructure === TRUE) {
            $this->fillStructure();
        }
    }

    public function add($item) {
        $tagName = $item->getTagName();
        if (!empty ($tagName)) {
            $this->items[$item->getTagName()] = $item;
        }  else {
            throw new Exception('tagName is Empty. in Complex');
        }
        
    }

    public function count() {
        return count($this->items);
    }

    public function get($index) {
        if (array_key_exists($index, $this->items)) {
            return $this->items[$index];
        }
        return NULL;
    }

    public function getId() {
        return $this->Id;
    }

    public function setId($pID) {
        $this->Id = $pID;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($pName) {
        $this->name = $pName;
    }

    public function addArray($array) {
        $this->myArray += $array;
    }

    public abstract function fillStructure();

    public function isEmpty() {
        return FALSE;
    }

}

?>
