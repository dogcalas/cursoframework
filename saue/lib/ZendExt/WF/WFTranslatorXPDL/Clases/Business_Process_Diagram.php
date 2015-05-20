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
class ZendExt_WF_WFObject_BusinessDiagramProcess {
    //put your code here
    private $id;
    private $name;
    private $version;
    private $xpdlVersion;
    private $author;
    private $language;
    private $expresionLanguage;
    private $queryLanguage;
    private $creationDate;
    private $modificationDate;
    private $pools;
    private $documentation;
    private $vendor;
    private $description;
    private $priorityUnit;
    private $costUnit;
    private $vendorExtensions;
    private $layoutInfo;
    private $publicationStatus;
    private $codePage;
    private $countryKey;
    private $reponsibles;
    private $graphConformance;
    private $bpmnPortability;

    
    //set
    public function setId($id){
        $this->id = $id;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setVersion($version){
        $this->version = $version;
    }
    public function setAuthor($author){
        $this->author = $author;
    }
    public function setLanguage($language){
        $this->language = $language;
    }
    public function setExpressLanguage($eLanguage){
        $this->expresionLanguage = $eLanguage;
    }
    public function setQueryLanguage($qLanguage){
        $this->queryLanguage = $qLanguage;
    }
    public function setCreationDate($cDate){
        $this->creationDate = $cDate;
    }
    public function setModificationDate($mDate){
        $this->modificationDate = $mDate;
    }
    public function setPools($pools){
        $this->pools = $pools;
    }
    public function setDocumentation($documentation){
        $this->documentation = $documentation;
    }
    public function setVendor($vendor){
        $this->vendor = $vendor;
    }
    public function setDescription($description){
        $this->description = $description;
    }
    public function setPriorityUnit($pUnit){
        $this->priorityUnit = $pUnit;
    }
    public function setCostUnit($cUnit){
        $this->costUnit = $cUnit;
    }
    public function setVendorExtensions($vExtensions){
        $this->vendorExtensions = $vExtensions;
    }
    public function setLayoutInfo($lInfo){
        $this->layoutInfo = $lInfo;
    }
    public function setPublicationStatus($pStatus){
        $this->publicationStatus = $pStatus;
    }
    public function setXPDLVersion($xpdlVersion){
        $this->xpdlVersion = $xpdlVersion;
    }
    public function setCodePage($cPage){
        $this->codePage = $cPage;
    }
    public function setCountryKey($cKery){
        $this->countryKey = $cKery;
    }
    public function setResponsibles($responsibles){
        $this->reponsibles = $responsibles;
    }
    public function setGraphConformance($gConformance){
        $this->graphConformance = $gConformance;
    }
    public function setBPMNPortability($portability){
        $this->bpmnPortability = $portability;
    }

    //get
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getVersion(){
        return $this->version;
    }
    public function getAuthor(){
        return $this->author;
    }
    public function getLanguage(){
        return $this->language;
    }
    public function getExpressLanguage(){
        return $this->expresionLanguage;
    }
    public function getQueryLanguage(){
        return $this->queryLanguage;
    }
    public function getCreationDate(){
        return $this->creationDate;
    }
    public function getModificationDate(){
        return $this->modificationDate;
    }
    public function getPools(){
        return $this->pools;
    }
    public function getDocumentation(){
        return $this->documentation;
    }
    public function getVendor(){
        return $this->vendor;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getPriorityUnit(){
        return $this->priorityUnit;
    }
    public function getCostUnit(){
        return $this->costUnit;
    }
    public function getVendorExtensions(){
        return $this->vendorExtensions;
    }
    public function getLayoutInfo(){
        return $this->layoutInfo;
    }
    public function getPublicationStatus(){
        return $this->publicationStatus;
    }
    public function getXPDLVersion(){
        return $this->xpdlVersion;
    }
    public function getCodePage(){
        return $this->codePage;
    }
    public function getCountryKey(){
        return $this->countryKey;
    }
    public function getResponsibles(){
        return $this->reponsibles;
    }
    public function getGraphConformance(){
        return $this->graphConformance;
    }
    public function getBPMNCOnformance(){
        return $this->bpmnPortability;
    }
}

?>
