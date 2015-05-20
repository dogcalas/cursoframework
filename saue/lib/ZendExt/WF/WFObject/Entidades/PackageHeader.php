<?php

class ZendExt_WF_WFObject_Entidades_PackageHeader extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'PackageHeader';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {

        $this->add(new ZendExt_WF_WFObject_Entidades_XPDLVersion($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Vendor($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Created($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ModificationDate($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Documentation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_PriorityUnit($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_CostUnit($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_VendorExtensions($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_LayoutInfo($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_Vendor($this));
    }

    public function getXPDLVersion() {
        return $this->get('XPDLVersion');
    }

    public function setXPDLVersion($pxpdlVersion) {

        $this->xpdlVersion->setXPDLVersion($pxpdlVersion);
    }

    /*     * *********************VENDOR************************ */

    public function getVendor() {
        return $this->get('Vendor');
    }

    public function setVendor($vendor) {
        $this->vendor->setvendor($vendor);
    }

    /*     * *********************CREATED************************ */

    public function getCreated() {
        return $this->get('Created');
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    /*     * *********************MODF.DATE************************ */

    public function getModificationDate() {
        return $this->get('ModificationDate');
    }

    public function setModificationDate($mDate) {
        $this->modificationDate = $mDate;
    }

    /*     * *********************DESCRIPTION************************ */

    public function getDescription() {
        return $this->get('Description');
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    /*     * *********************DOCUMENTATION************************ */

    public function getDocumentation() {
        return $this->get('Documentation');
    }

    public function setDocumentation($documentation) {
        $this->documentation = $documentation;
    }

    /*     * *********************PriorityUnit************************ */

    public function getPriorityUnit() {
        return $this->get('PriorityUnit');
    }

    public function setPriorityUnit($pUnit) {
        $this->priorityUnit = $pUnit;
    }

    /*     * *********************COS.UNIT************************ */

    public function getCostUnit() {
        return $this->get('CostUnit');
    }

    public function setCostUnit($cUnit) {
        $this->costUnit = $cUnit;
    }

    /*     * ******************VendorExtensions******************* */

    public function getVendorExtensions() {
        return $this->get('VendorExtensions');
    }

    public function setVendorExtensions($vExtensions) {
        $this->vendorExtensions = $vExtensions;
    }

    /*     * ******************LayoutInfo******************* */

    public function getLayoutInfo() {
        return $this->get('LayoutInfo');
    }

    public function setLayoutInfo($lInfo) {
        $this->layoutInfo = $lInfo;
    }

    public function toArray() {
        $array = array(
            'XPDLVersion' => $this->getXPDLVersion()->toArray(),
            'Vendor' => $this->getVendor()->toArray(),
            'Created' => $this->getCreated()->toArray(),
            'ModificationDate' => $this->getModificationDate()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'Documentation' => $this->getDescription()->toArray(),
            'PriorityUnit' => $this->getPriorityUnit()->toArray(),
            'CostUnit' => $this->getCostUnit()->toArray(),
            'VendorExtensions' => $this->getVendorExtensions()->toArray(),
            'LayoutInfo' => $this->getLayoutInfo()->toArray()
        );
        return $array;
    }

}

?>
