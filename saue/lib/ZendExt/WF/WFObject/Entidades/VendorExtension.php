<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VendorExtension
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_VendorExtension extends ZendExt_WF_WFObject_Base_Complex {

    private $ToolId;
    private $schemaLocation;
    private $extensionDescription;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'VendorExtension';
    }

    public function setToolId($_toolId) {
        $this->ToolId = $_toolId;
    }

    public function getToolId() {
        return $this->ToolId;
    }

    public function setschemaLocation($_schemaLocation) {
        $this->schemaLocation = $_schemaLocation;
    }

    public function getschemaLocation() {
        return $this->schemaLocation;
    }

    public function setextensionDescription($_extensionDescription) {
        $this->extensionDescription = $_extensionDescription;
    }

    public function getextensionDescription() {
        return $this->extensionDescription;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */
        return;
    }

}

?>
