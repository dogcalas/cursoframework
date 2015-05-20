<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaskZendAction
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_TaskZendAction extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    //Se refiere a una accion de un controlador
    private $zendAction;
    private $idRol;
    
    private $SystemId;
    private $Controller;
    private $Action;
    
    private $idsistema;
    private $idfuncionalidad;
    private $denominacion;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskZendAction';
    }

    /*
     * setters
     */
    public function setZendAction($_zendAction) {
        $this->zendAction = $_zendAction;
    }

    public function setIdRol($_idRol) {
        $this->idRol = $_idRol;
    }
    
    public function setSystemId($_systemId) {
        $this->SystemId = $_systemId;
    }
    
    public function setController($_controller) {
        $this->Controller = $_controller;
    }
    
    public function setAction($_action) {
        $this->Action = $_action;
    }    
    
    public function setIdSistema($idsistema){
        $this->idsistema = $idsistema;
    }
    
    public function setIdFuncionalidad($idfuncionalidad){
        $this->idfuncionalidad = $idfuncionalidad;
    } 
    
    public function setDenominacion($denominacion){
        $this->denominacion = $denominacion;
    }     

    
    /*
     * getters
     */
    public function getZendAction() {
        return $this->zendAction;
    }

    public function getIdRol() {
        return $this->idRol;
    }
    
    public function getSystemId() {
        return $this->SystemId;
    }
    
    public function getController() {
        return $this->Controller;
    }
    
    public function getAction() {
        return $this->Action;
    }    
    
    public function getIdSistema(){
        return $this->idsistema;
    }
    
    public function getIdFuncionalidad(){
        return $this->idfuncionalidad;
    } 
    
    public function getDenominacion(){
        return $this->denominacion;
    }    

    public function getVariables() {
        return $this->get('Variables');
    }
    
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Variables($this));
        return;
    }
    
    public function toName() {
        return 'Zend Action';
    }

}

?>
