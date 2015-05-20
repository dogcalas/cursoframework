<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_WorkflowProcess extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $objProcessHeader = $this->object->getProcessHeader();
        $myProcessHeader = new ZendExt_WF_WFTranslatorXPDL_Clases_ProcessHeader($objProcessHeader);
        $this->addAttribute($myProcessHeader);

        $objRedefinableHeader = $this->object->getRedefinableHeader();
        $myRedefinableHeader = new ZendExt_WF_WFTranslatorXPDL_Clases_RedefinableHeader($objRedefinableHeader);
        $this->addAttribute($myRedefinableHeader);

        $objFormalParameters = $this->object->getFormalParameters();
        $myFormalParameters = new ZendExt_WF_WFTranslatorXPDL_Clases_FormalParameters($objFormalParameters);
        $this->addAttribute($myFormalParameters);

        $objInputSets = $this->object->getInputSets();
        $myInputSets = new ZendExt_WF_WFTranslatorXPDL_Clases_InputSets($objInputSets);
        $this->addAttribute($myInputSets);

        $objOutputSets = $this->object->getOutputSets();
        $myOutputSets = new ZendExt_WF_WFTranslatorXPDL_Clases_OutputSets($objOutputSets);
        $this->addAttribute($myOutputSets);

        $objActivities = $this->object->getActivities();
        $myActivities = new ZendExt_WF_WFTranslatorXPDL_Clases_Activities($objActivities);
        $this->addAttribute($myActivities);

        $objTransitions = $this->object->getTransitions();
        $myTransitions = new ZendExt_WF_WFTranslatorXPDL_Clases_Transitions($objTransitions);
        $this->addAttribute($myTransitions);

        $objExtendedAttributes = $this->object->getExtendedAttributes();
        $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
        $this->addAttribute($myExtendedAttributes);

        $objAssignments = $this->object->getAssignments();
        $myAssignments = new ZendExt_WF_WFTranslatorXPDL_Clases_Assignments($objAssignments);
        $this->addAttribute($myAssignments);

        $objPartnerLinks = $this->object->getPartnerLinks();
        $myPartnerLinks = new ZendExt_WF_WFTranslatorXPDL_Clases_PartnerLinks($objPartnerLinks);
        $this->addAttribute($myPartnerLinks);

        $objObject = $this->object->getObject();
        $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
        $this->addAttribute($myObject);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("WorkflowProcess");

        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->getName());
        $thisObjectTag->setAttribute('AccessLevel', $this->object->getAccessLevel());
        $thisObjectTag->setAttribute('ProcessType', $this->object->getProcessType());
        $thisObjectTag->setAttribute('SuppressJoinFailure', $this->object->getSuppressJoinFailure());
        $thisObjectTag->setAttribute('EnableInstanceCompensation', $this->object->getEnableInstanceCompensation());

        $isAdhoc = $this->object->getAdHoc();
        if ($isAdhoc == 'TRUE') {
            $thisObjectTag->setAttribute('AdHoc', $this->object->getAdHoc());
            $thisObjectTag->setAttribute('AdHocOrdering', $this->object->getAdHocOrdering());
            $thisObjectTag->setAttribute('AdHocCompletionCondition', $this->object->getAdHocCompletionCondition());
        }

        $thisObjectTag->setAttribute('DefaultStartActivitySetId', 'falta  capturar DefaultStartActivitySetId'/* $this->object->getValue() */);
        $thisObjectTag->setAttribute('DefaultStartActivityId', 'falta  capturar DefaultStartActivityId'/* $this->object->getValue() */);

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    /* public function fromXPDL($objectTag) {
      $attribs = $objectTag->attributes;

      if ($attribs->length > 0) {
      for ($i = 0; $i < $attribs->length; $i++) {
      $node = $attribs->item($i);
      $prefFuncName = 'set';
      $fullFuncName = $prefFuncName . $node->nodeName;
      $this->object->$fullFuncName($node->nodeValue);
      }
      }

      for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
      $Nonde = $objectTag->childNodes->item($i);
      $NodeName = $Nonde->nodeName;

      if ($NodeName == 'Participants' || $NodeName == 'Applications' || $NodeName == 'DataFields') {

      $Act_Type = $this->object->getWorkflowProcessType();
      $Act_Type->selectItem($NodeName);
      $cObj = $Act_Type->getSelectedItem();
      $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
      $ClassName = $prefClassName . $NodeName;
      $newTag = new $ClassName($cObj);
      $newTag->fromXPDL($Nonde);
      } else {
      $prefFuncName = 'get';
      $fullFuncName = $prefFuncName . $NodeName;
      $cObject = $this->object->$fullFuncName();
      $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
      $ClassName = $prefClassName . $NodeName;
      $newTag = new $ClassName($cObject);
      $newTag->fromXPDL($Nonde);
      }
      }
      } */
}

?>
