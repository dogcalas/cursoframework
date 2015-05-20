<?php

class Script {

    private $_nombre, $_ubicacion;

    public function  __construct($nombre, $ubicacion) {
        $this->_nombre = $nombre;
        $this->_ubicacion = $ubicacion;
    }

    public function  __destruct() {
        ;
    }

    public function setNombre($value) {
        $this->_nombre = $value;
    }

    public function getNombre() {
        return $this->_nombre;
    }

    public function getUbicacion() {
        return $this->_ubicacion;
    }

    public function setUbicacion($value) {
        $this->_ubicacion = $value;
    }
}
?>
