<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Package extends ZendExt_WF_WFTranslatorXPDL_Base_Base {
    public function __construct($object, $dessasemble = TRUE) {
        parent::__construct($object, $dessasemble);
    }
    public function desassembleClass() {
        $objPackageHeader = $this->object->getPackageHeader();
        $myPackageHeader = new ZendExt_WF_WFTranslatorXPDL_Clases_PackageHeader($objPackageHeader);

        $objRedefinableHeader = $this->object->getRedefinableHeader();
        $myRedefinableHeader = new ZendExt_WF_WFTranslatorXPDL_Clases_RedefinableHeader($objRedefinableHeader);

        $objConformanceClass = $this->object->getConformanceClass();
        $myConformanceClass = new ZendExt_WF_WFTranslatorXPDL_Clases_ConformanceClass($objConformanceClass);

        $objScript = $this->object->getScript();
        $myScript = new ZendExt_WF_WFTranslatorXPDL_Clases_Script($objScript);

        $objExternalPackages = $this->object->getExternalPackages();
        $myExternalPackages = new ZendExt_WF_WFTranslatorXPDL_Clases_ExternalPackages($objExternalPackages);

        $objTypeDeclarations = $this->object->getTypeDeclarations();
        $myTypeDeclarations = new ZendExt_WF_WFTranslatorXPDL_Clases_TypeDeclarations($objTypeDeclarations);

        $objParticipants = $this->object->getParticipants();
        $myParticipants = new ZendExt_WF_WFTranslatorXPDL_Clases_Participants($objParticipants);

        $objApplications = $this->object->getApplications();
        $myApplications = new ZendExt_WF_WFTranslatorXPDL_Clases_Applications($objApplications);

        $objDataFields = $this->object->getDataFields();
        $myDataFields = new ZendExt_WF_WFTranslatorXPDL_Clases_DataFields($objDataFields);

        $objPartnerLinkTypes = $this->object->getPartnerLinkTypes();
        $myPartnerLinkTypes = new ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinkTypes($objPartnerLinkTypes);

        $objPages = $this->object->getPages();
        $myPages = new ZendExt_WF_WFTranslatorXPDL_Clases_Pages($objPages);

        $objPools = $this->object->getPools();
        $myPools = new ZendExt_WF_WFTranslatorXPDL_Clases_Pools($objPools);

        $objMessageFlows = $this->object->getMessageFlows();
        $myMessageFlows = new ZendExt_WF_WFTranslatorXPDL_Clases_MessageFlows($objMessageFlows);

        $objAssociations = $this->object->getAssociations();
        $myAssociations = new ZendExt_WF_WFTranslatorXPDL_Clases_Associations($objAssociations);
        
        $objArtifacts = $this->object->getArtifacts();
        $myArtifacts = new ZendExt_WF_WFTranslatorXPDL_Clases_Artifacts($objArtifacts);


        $objWorkflowProcesses = $this->object->getWorkflowProcesses();
        $myWorkflowProcesses = new ZendExt_WF_WFTranslatorXPDL_Clases_WorkflowProcesses($objWorkflowProcesses);
        
        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);

        $this->addAttribute($myPackageHeader);
        $this->addAttribute($myRedefinableHeader);
        $this->addAttribute($myConformanceClass);
        $this->addAttribute($myScript);
        $this->addAttribute($myExternalPackages);
        $this->addAttribute($myTypeDeclarations);
        $this->addAttribute($myParticipants);
        $this->addAttribute($myApplications);
        $this->addAttribute($myDataFields);
        $this->addAttribute($myPartnerLinkTypes);
        $this->addAttribute($myPages);
        $this->addAttribute($myPools);
        $this->addAttribute($myMessageFlows);
        $this->addAttribute($myAssociations);
        $this->addAttribute($myArtifacts);
        $this->addAttribute($myWorkflowProcesses);
        $this->addAttribute($myExtendedAttributes);
    }

    public function toXPDL($doc, &$objectTag) {
        if ($objectTag == null) {
            $objectTag = $doc->createElement("Package");
            
            $id = $this->object->getId();
            $name = $this->object->getName();
            $ActiveProcess = $this->object->getActiveProcess();
            $queryLanguage = $this->object->getqueryLanguage();
            $Language = $this->object->getLanguage();
            
            $objectTag->setattribute("Id", $id);
            $objectTag->setattribute("Name", $name);
            $objectTag->setattribute("ActiveProcess", $ActiveProcess);
            $objectTag->setattribute("queryLanguage", $queryLanguage);
            $objectTag->setattribute("Language", $Language);
            $doc->appendChild($objectTag);
        }

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $objectTag);
        }
    }

}

?>
