<?php

class ZendExt_WF_WFObject_Entidades_Package extends ZendExt_WF_WFObject_Base_Complex {

    private $Language;
    private $queryLanguage;
    private $ActiveProcess;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Package';
    }

    public function fillStructure() {

        $this->add(new ZendExt_WF_WFObject_Entidades_PackageHeader($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_RedefinableHeader($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ConformanceClass($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Script($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExternalPackages($this));
        /*  $this->add(new ZendExt_WF_WFObject_Entidades_TypeDeclarations($this)); */
        $this->add(new ZendExt_WF_WFObject_Entidades_Participants($this));
        /* $this->add(new ZendExt_WF_WFObject_Entidades_Applications($this)); */
        $this->add(new ZendExt_WF_WFObject_Entidades_DataFields($this));
        /* $this->add(new ZendExt_WF_WFObject_Entidades_PartnerLinkTypes($this));
          $this->add(new ZendExt_WF_WFObject_Entidades_Pages($this)); */
        $this->add(new ZendExt_WF_WFObject_Entidades_Pools($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageFlows($this));
        /* $this->add(new ZendExt_WF_WFObject_Entidades_Associations($this));
          $this->add(new ZendExt_WF_WFObject_Entidades_Artifacts($this)); */
        $this->add(new ZendExt_WF_WFObject_Entidades_WorkflowProcesses($this));
        /* $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
         */
    }

    public function getActiveProcess() {
        return $this->ActiveProcess;
    }

    public function getActiveProcessId() {
        return $this->ActiveProcess;
    }

    public function getLanguage() {
        return $this->Language;
    }

    public function setActiveProcess($activeProcess) {
        $this->ActiveProcess = $activeProcess;
    }

    public function setLanguage($language) {
        $this->Language = $language;
    }

    public function setqueryLanguage($_queryLanguage) {
        $this->queryLanguage = $_queryLanguage;
    }

    public function getqueryLanguage() {
        return $this->queryLanguage;
    }

    public function getPackageHeader() {
        return $this->get('PackageHeader');
    }

    public function getWorkflowProcesses() {
        return $this->get('WorkflowProcesses');
    }

    public function getRedefinableHeader() {
        return $this->get('RedefinableHeader');
    }

    public function getConformanceClass() {
        return $this->get('ConformanceClass');
    }

    public function getScript() {
        return $this->get('Script');
    }

    public function getExternalPackages() {
        return $this->get('ExternalPackages');
    }

    public function getTypeDeclarations() {
        return $this->get('TypeDeclarations');
    }

    public function getParticipants() {
        return $this->get('Participants');
    }

    public function getPools() {
        return $this->get('Pools');
    }

    public function getApplications() {
        return $this->get('Applications');
    }

    public function getDataFields() {
        return $this->get('DataFields');
    }

    public function getPartnerLinkTypes() {
        return $this->get('PartnerLinkTypes');
    }

    public function getPages() {
        return $this->get('Pages');
    }

    public function getMessageFlows() {
        return $this->get('MessageFlows');
    }

    public function getAssociations() {
        return $this->get('Associations');
    }

    public function getArtifacts() {
        return $this->get('Artifacts');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function clonar() {
        return NULL;
    }

    public function toArray() {
        $array = array(
            'Language' => $this->getLanguage(),
            'queryLanguage' => $this->getqueryLanguage(),
            'ActiveProcess' => $this->getActiveProcess(),
            'PackageHeader' => $this->getPackageHeader()->toArray(),
            'RedefinableHeader' => $this->getRedefinableHeader()->toArray(),
            'ConformanceClass' => $this->getConformanceClass()->toArray(),
            'Script' => $this->getScript()->toArray(),
            'ExternalPackages' => $this->getExternalPackages()->toArray(),
            'Participants' => $this->getParticipants()->toArray(),
            'DataFields' => $this->getDataFields()->toArray(),
            'Pools' => $this->getPools()->toArray(),
            'MessageFlows' => $this->getMessageFlows()->toArray(),
            'WorkflowProcesses' => $this->getWorkflowProcesses()->toArray()
        );
        return $array;
    }

}

?>
