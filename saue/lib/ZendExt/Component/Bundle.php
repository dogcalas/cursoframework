<?php

class ZendExt_Component_Bundle {

    private $id;
    private $state;
    private $path;
    private $name;
    private $version;
    private $listaService;
    private $listaDependencias;
    private $listaSource;
    private $listaObserver;

    function __construct($id, $state, $path) {
        $this->id = $id;
        if ($state == null || $state == '')
            $state = 'unresolved';
        $this->state = $state;
        $this->path = $path;
    }

    public function getListaSourceEvent() {
        return $this->listaSource;
    }

    public function getListaObserver() {
        return $this->listaObserver;
    }

    public function ADDLObserver($observer) {
        $this->listaObserver[] = $observer;
    }

    public function ADDLSourceEvent($event) {
        $this->listaSource[] = $event;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getState() {
        return $this->state;
    }

    public function getPath() {
        return $this->path;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getName() {
        return $this->name;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getListaService() {
        return $this->listaService;
    }

    public function getListaDependencias() {
        return $this->listaDependencias;
    }

    public function ADDLDependencias($service) {
        $this->listaDependencias[] = $service;
    }

    public function getId() {
        return $this->id;
    }

    public function ADDlService($service) {
        $this->listaService[] = $service;
    }

}

?>
