<?php
abstract class ZendExt_WF_WFObject_Base_Complex extends ZendExt_WF_WFObject_Base_Bfcace {

    //put your code here
    protected $Id;
    protected $Name;

    public function __construct($parent, $fillStructure = TRUE) {
        parent::__construct($parent);

        if($fillStructure === TRUE)
            $this->fillStructure();
    }

    public function add($item) {
        $this->items[$item->getTagName()] = $item;
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
        return $this->Name;
    }

    public function setName($pName) {
        $this->Name = $pName;
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
