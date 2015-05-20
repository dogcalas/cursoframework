<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Diagrama_Proceso_Negocio
 *
 * @author Jose
 */
class ZendExt_WF_WFObject_Complex_PackageType extends ZendExt_WF_WFObject_Complex_ComplexElement{
    //atributos
    private $id;
    private $name;
    private $language;
    private $queryLanguage;
    
    private $packageHeader;


    public function __construct() {
        $this->packageHeader = new ZendExt_WF_WFObject_PackageHeader();        
    }

    //set
    public function setId($id){
        $this->id = $id;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setLanguage($language){
        $this->language = $language;
    }
    public function setQueryLanguage($qLanguage){
        $this->queryLanguage = $qLanguage;
    }
    
    //get
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getLanguage(){
        return $this->language;
    }
    public function getQueryLanguage(){
        return $this->queryLanguage;
    }
    
}

?>
