<?php

class ZendExt_Component_Service {

    protected $id;
    protected $impl;
    protected $interface;

    function __construct($id, $impl, $interface) {
        $this->id = $id;
        $this->impl = $impl;
        $this->interface = $interface;
    }

    public function setImpl($impl) {
        $this->impl = $impl;
    }

    public function getId() {
        return $this->id;
    }

    public function getImpl() {
        return $this->impl;
    }

    public function getInterface() {
        return $this->interface;
    }

}

?>