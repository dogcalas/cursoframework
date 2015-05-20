<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_PackageHeader extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $xpdlVersion = $this->object->getXPDLVersion();
        $vendor = $this->object->getVendor();
        $created = $this->object->getCreated();
        $modificationDate = $this->object->getModificationDate();
        $description = $this->object->getDescription();
        $documentation = $this->object->getDocumentation();
        $priorityUnit = $this->object->getPriorityUnit();
        $costUnit = $this->object->getCostUnit();
        $vendorExtensions = $this->object->getVendorExtensions();
        $layoutInfo = $this->object->getLayoutInfo();

        $myXPDLVersion = new ZendExt_WF_WFTranslatorXPDL_Clases_XPDLVersion($xpdlVersion);
        $myvendor = new ZendExt_WF_WFTranslatorXPDL_Clases_Vendor($vendor);
        $mycreated = new ZendExt_WF_WFTranslatorXPDL_Clases_Created($created);
        $myModificationDate = new ZendExt_WF_WFTranslatorXPDL_Clases_ModificationDate($modificationDate);
        $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($description);
        $myDocumentation = new ZendExt_WF_WFTranslatorXPDL_Clases_Documentation($documentation);
        $myPriorityUnit = new ZendExt_WF_WFTranslatorXPDL_Clases_PriorityUnit($priorityUnit);
        $myCostUnit = new ZendExt_WF_WFTranslatorXPDL_Clases_CostUnit($costUnit);
        $myVendorExtensions = new ZendExt_WF_WFTranslatorXPDL_Clases_VendorExtensions($vendorExtensions);
        $myLayoutInfo = new ZendExt_WF_WFTranslatorXPDL_Clases_LayoutInfo($layoutInfo);

        $this->addAttribute($myXPDLVersion);
        $this->addAttribute($myvendor);
        $this->addAttribute($mycreated);
        $this->addAttribute($myModificationDate);
        $this->addAttribute($myDescription);
        $this->addAttribute($myDocumentation);
        $this->addAttribute($myPriorityUnit);
        $this->addAttribute($myCostUnit);
        $this->addAttribute($myVendorExtensions);
        $this->addAttribute($myLayoutInfo);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("PackageHeader");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}

?>
