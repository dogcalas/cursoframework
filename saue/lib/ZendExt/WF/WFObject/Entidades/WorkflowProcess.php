<?php

class ZendExt_WF_WFObject_Entidades_WorkflowProcess extends ZendExt_WF_WFObject_Base_Complex {

    private $AccessLevel;
    private $ProcessType;
    private $Status;
    private $SuppressJoinFailure;
    private $EnableInstanceCompensation;
    private $AdHoc;
    private $AdHocOrdering;
    private $AdHocCompletionCondition;
    private $DefaultStartActivitySetId;
    private $DefaultStartActivityId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'WorkflowProcess';
    }

    public function getAccessLevel() {
        return $this->AccessLevel->getSelectedItem();
    }

    public function setAccessLevel($accessLevel) {
        $this->AccessLevel->selectItem($accessLevel);
    }

    public function getProcessType() {
        return $this->ProcessType->getSelectedItem();
    }

    public function setProcessType($processType) {
        $this->ProcessType->selectItem($processType);
    }

    public function getStatus() {
        return $this->Status->getSelectedItem();
    }

    public function setStatus($status) {
        $this->Status->selectItem($status);
    }

    public function getSuppressJoinFailure() {
        return $this->SuppressJoinFailure->getSelectedItem();
    }

    public function setSuppressJoinFailure($supressJoinFailure) {
        $this->SuppressJoinFailure->selectItem($supressJoinFailure);
    }

    public function getEnableInstanceCompensation() {
        return $this->EnableInstanceCompensation->getSelectedItem();
    }

    public function setEnableInstanceCompensation($enableInstanceCompensation) {
        $this->EnableInstanceCompensation->selectItem($enableInstanceCompensation);
    }

    public function getAdHoc() {
        return $this->AdHoc->getSelectedItem();
    }

    public function setAdHoc($adHoc) {
        $this->AdHoc->selectItem($adHoc);
    }

    public function getAdHocOrdering() {
        return $this->AdHocOrdering->getSelectedItem();
    }

    public function setAdHocOrdering($adHocOrdering) {
        $this->AdHocOrdering->selectItem($adHocOrdering);
    }

    public function getAdHocCompletionCondition() {
        return $this->AdHocCompletionCondition;
    }

    public function setAdHocCompletionCondition($adHocCompletionCondition) {
        $this->AdHocCompletionCondition = $adHocCompletionCondition;
    }

    public function clonar() {
        return;
    }

    public function getDefaultStartActivitySetId() {
        return $this->DefaultStartActivitySetId;
    }

    public function setDefaultStartActivitySetId($defaultStartActivitySetId) {
        $this->DefaultStartActivitySetId = $defaultStartActivitySetId;
    }

    public function getDefaultStartActivityId() {
        return $this->DefaultStartActivityId;
    }

    public function setDefaultStartActivityId($defaultStartActivityId) {
        $this->DefaultStartActivityId = $defaultStartActivityId;
    }

    public function getActivities() {
        return $this->get('Activities');
    }

    public function getParticipants() {
        return $this->get('Participants');
    }

    public function getApplications() {
        return $this->get('Applications');
    }

    public function getDataFields() {
        return $this->get('DataFields');
    }

    public function getTransitions() {
        return $this->get('Transitions');
    }

    public function getProcessHeader() {
        return $this->get('ProcessHeader');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function getRedefinableHeader() {
        return $this->get('RedefinableHeader');
    }

    public function getFormalParameters() {
        return $this->get('FormalParameters');
    }

    public function getInputSets() {
        return $this->get('InputSets');
    }

    public function getOutputSets() {
        return $this->get('OutputSets');
    }

    public function getAssignments() {
        return $this->get('Assignments');
    }

    public function getPartnerLinks() {
        return $this->get('PartnerLinks');
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_ProcessHeader($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_RedefinableHeader($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_FormalParameters($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_InputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_OutputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Participants($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Applications($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_DataFields($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Activities($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Transitions($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Assignments($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_PartnerLinks($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));

        $this->AccessLevel = new ZendExt_WF_WFObject_Base_SimpleChoice('AccessLevel', array('PUBLIC', 'PRIVATE'), NULL);

        $processTypeChoices = array('None', 'Private', 'Abstract', 'Collaboration');
        $this->ProcessType = new ZendExt_WF_WFObject_Base_SimpleChoice('ProcessType', $processTypeChoices, NULL);

        $statusChoices = array('None', 'Ready', 'Active', 'Cancelled', 'Aborting', 'Aborted', 'Completing', 'Completed');
        $this->Status = new ZendExt_WF_WFObject_Base_SimpleChoice('Status', $statusChoices, NULL);

        $this->AdHocOrdering = new ZendExt_WF_WFObject_Base_SimpleChoice('AdHocOrdering', array('Parallel', 'Sequential'), NULL);

        $this->SuppressJoinFailure = new ZendExt_WF_WFObject_Base_SimpleChoice('SuppressJoinFailure', array('TRUE', 'FALSE'), NULL);
        $this->EnableInstanceCompensation = new ZendExt_WF_WFObject_Base_SimpleChoice('EnableInstanceCompensation', array('TRUE', 'FALSE'), NULL);
        $this->AdHoc = new ZendExt_WF_WFObject_Base_SimpleChoice('AdHoc', array('TRUE', 'FALSE'), NULL);

        return;
    }

    public function toArray() {
        $array = array(
            'ProcessHeader' => $this->getProcessHeader()->toArray(),
            'RedefinableHeader' => $this->getRedefinableHeader()->toArray(),
            'FormalParameters' => $this->getFormalParameters()->toArray(),
            'InputSets' => $this->getInputSets()->toArray(),
            'OutputSets' => $this->getOutputSets()->toArray(),
            'Participants' => $this->getParticipants()->toArray(),
            'Applications' => $this->getApplications()->toArray(),
            'DataFields' => $this->getDataFields()->toArray(),
            'Activities' => $this->getActivities()->toArray(),
            'Transitions' => $this->getTransitions()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray(),
            'Assignments' => $this->getAssignments()->toArray(),
            'PartnerLinks' => $this->getPartnerLinks()->toArray(),
            'Object' => $this->getObject()->toArray(),
            'AccessLevel' => $this->getAccessLevel(),
            'ProcessType' => $this->getProcessType(),
            'Status' => $this->getStatus(),
            'AdHocOrdering' => $this->getAdHocOrdering(),
            'SuppressJoinFailure' => $this->getSuppressJoinFailure(),
            'EnableInstanceCompensation' => $this->getEnableInstanceCompensation(),
            'AdHoc' => $this->getAdHoc()
        );
        return $array;
    }

}

?>
