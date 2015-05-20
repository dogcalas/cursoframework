<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Observer
 *
 * @author dogy
 */
class ZendExt_Component_ObserverEvent {

    private $impl; //Implementacion del observer
    private $source; //EVento que se observa
    private $added; //Booleno que almacena si el observador esta agregado

    public function getAdded() {
        return $this->added;
    }

    public function setAdded($added) {
        $this->added = $added;
    }

    function __construct($impls, $source) {
        $this->source = $source;
        $this->impl = $impls;
        $this->added = false;
    }

    public function getImpls() {
        return $this->impl;
    }

    public function setImpls($impls) {
        $this->impl = $impls;
    }

    public function getSource() {
        return $this->source;
    }

    public function setSource($source) {
        $this->source = $source;
    }

}

?>
