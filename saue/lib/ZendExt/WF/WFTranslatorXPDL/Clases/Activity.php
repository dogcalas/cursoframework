<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Activity extends ZendExt_WF_WFTranslatorXPDL_Base_Base {
    /*
     * $activityTypeFlag :{0 = Event, 1 = Route, 2 = Implementation}
     */

    private $activityTypeFlag = -1;

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    function desassembleClass() {
        $objActivityType = $this->object->getActivityType()->getSelectedItem();

        $objTypeAsString = $objActivityType->getTagName();
        switch ($objTypeAsString) {
            case 'Event':
                $this->activityTypeFlag = 0;
                $this->doEvent($objActivityType);
                break;
            case 'Route':
                $this->activityTypeFlag = 1;
                $this->doRoute($objActivityType);
                break;
            case 'Implementation':
                $this->activityTypeFlag = 2;
                $this->doImplementation($objActivityType);
                break;
            default:
                break;
        }

        $this->getCommonAttribs();

        /* $objDescription = $this->object->getDescription();
          $objLimit = $this->object->getLimit();
          $objTransaction = $this->object->getTransaction();
          $objPerformers = $this->object->getPerformers();
          $objPerformer = $this->object->getPerformer();
          $objPriority = $this->object->getPriority();
          $objSimulationInformation = $this->object->getSimulationInformation();
          $objIcon = $this->object->getIcon();
          $objDocumentation = $this->object->getDocumentation(); */
        $objTransitionRestrictions = $this->object->getTransitionRestrictions();
        /* $objExtendedAttributes = $this->object->getExtendedAttributes();
          $objDataFields = $this->object->getDataFields();
          $objInputSets = $this->object->getInputSets();
          $objOutputSets = $this->object->getOutputSets();
          $objIORules = $this->object->getIORules();
          $objAssignments = $this->object->getAssignments();
          $objLoop = $this->object->getLoop();
          $objObject = $this->object->getObject();
          $objNodeGraphicsInfos = $this->object->getNodeGraphicsInfos();

          $myDescription = new ZendExt_WF_WFTranslatorXPDL_Clases_Description($objDescription);
          $myLimit = new ZendExt_WF_WFTranslatorXPDL_Clases_Limit($objLimit);
          $myTransaction = new ZendExt_WF_WFTranslatorXPDL_Clases_Transaction($objTransaction);
          $myPerformers = new ZendExt_WF_WFTranslatorXPDL_Clases_Performers($objPerformers);
          $myPerformer = new ZendExt_WF_WFTranslatorXPDL_Clases_Performer($objPerformer);
          $myPriority = new ZendExt_WF_WFTranslatorXPDL_Clases_Priority($objPriority);
          $mySimulationInformation = new ZendExt_WF_WFTranslatorXPDL_Clases_SimulationInformation($objSimulationInformation);
          $myIcon = new ZendExt_WF_WFTranslatorXPDL_Clases_Icon($objIcon);
          $myDocumentation = new ZendExt_WF_WFTranslatorXPDL_Clases_Documentation($objDocumentation); */
        $myTransitionRestrictions = new ZendExt_WF_WFTranslatorXPDL_Clases_TransitionRestrictions($objTransitionRestrictions);
        /* $myExtendedAttributes = new ZendExt_WF_WFTranslatorXPDL_Clases_ExtendedAttributes($objExtendedAttributes);
          $myDataFields = new ZendExt_WF_WFTranslatorXPDL_Clases_DataFields($objDataFields);
          $myInputSets = new ZendExt_WF_WFTranslatorXPDL_Clases_InputSets($objInputSets);
          $myOutputSets = new ZendExt_WF_WFTranslatorXPDL_Clases_OutputSets($objOutputSets);
          $myIORules = new ZendExt_WF_WFTranslatorXPDL_Clases_IORules($objIORules);
          $myAssignments = new ZendExt_WF_WFTranslatorXPDL_Clases_Assignments($objAssignments);
          $myLoop = new ZendExt_WF_WFTranslatorXPDL_Clases_Loop($objLoop);
          $myObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Object($objObject);
          $myNodeGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos($objNodeGraphicsInfos);

          $this->addAttribute($myDescription);
          $this->addAttribute($myLimit);
          $this->addAttribute($myTransaction);
          $this->addAttribute($myPerformers);
          $this->addAttribute($myPerformer);
          $this->addAttribute($myPriority);
          $this->addAttribute($mySimulationInformation);
          $this->addAttribute($myIcon);
          $this->addAttribute($myDocumentation); */
        $this->addAttribute($myTransitionRestrictions);
        /* $this->addAttribute($myExtendedAttributes);
          $this->addAttribute($myDataFields);
          $this->addAttribute($myInputSets);
          $this->addAttribute($myOutputSets);
          $this->addAttribute($myIORules);
          $this->addAttribute($myAssignments);
          $this->addAttribute($myLoop);
          $this->addAttribute($myObject);
          $this->addAttribute($myNodeGraphicsInfos); */
    }

    private function getCommonAttribs() {
        $objDocumentation = $this->object->getDocumentation();
        $myDocumentation = new ZendExt_WF_WFTranslatorXPDL_Clases_Documentation($objDocumentation);
        $this->addAttribute($myDocumentation);

        $objAssignments = $this->object->getAssignments();
        $myAssignments = new ZendExt_WF_WFTranslatorXPDL_Clases_Assignments($objAssignments);
        $this->addAttribute($myAssignments);

        $objNodeGraphicsInfos = $this->object->getNodeGraphicsInfos();
        $myNodeGraphicsInfos = new ZendExt_WF_WFTranslatorXPDL_Clases_NodeGraphicsInfos($objNodeGraphicsInfos);
        $this->addAttribute($myNodeGraphicsInfos);
    }

    private function doEvent($objEvent) {
        if ($objEvent instanceof ZendExt_WF_WFObject_Entidades_Event) {
            $myEvent = new ZendExt_WF_WFTranslatorXPDL_Clases_Event($objEvent);
            $this->addAttribute($myEvent);
        }
    }

    private function doImplementation($objImplementation) {
        if ($objImplementation instanceof ZendExt_WF_WFObject_Base_ComplexChoice && $objImplementation->getTagName() === 'Implementation') {
            $myImplementation = new ZendExt_WF_WFTranslatorXPDL_Clases_Implementation($objImplementation);
            $this->addAttribute($myImplementation);
        }
    }

    private function doRoute($objRoute) {
        if ($objRoute instanceof ZendExt_WF_WFObject_Entidades_Route) {
            $myRoute = new ZendExt_WF_WFTranslatorXPDL_Clases_Route($objRoute);
            $this->addAttribute($myRoute);
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Activity");
        $thisObjectTag->setAttribute('Id', $this->object->getId());
        $thisObjectTag->setAttribute('Name', $this->object->toName());

        switch ($this->activityTypeFlag) {
            case 0:
                $this->doEventXPDL();
                break;
            case 1:
                $this->doRouteXPDL();
                break;
            case 2:
                $this->doImplementationXPDL($thisObjectTag);
                break;
            default:
                throw new Exception('La actividad ' . $this->object->getName() . ' debe ser de tipo ROUTE, EVENT o Implementation.');
                break;
        }

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

    public function readStructure($objectTag) {
        /*
         * Must contain only one child, but (I) won't check that.
         */
        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $node = $objectTag->childNodes->item($i);
            $nodeName = $node->nodeName;

            if ($node->nodeType === XML_ELEMENT_NODE) {
                if ($this->isChoiceable($nodeName)) {
                    $this->object->getActivityType()->selectItem($nodeName);

                    $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                    $className = $prefClassName . $nodeName;
                    $newTag = new $className($this->object->getActivityType()->getSelectedItem(), FALSE);

                    $newTag->fromXPDL($node);
                }  else {
                    $funcPreffix = 'get';
                    $funcName = $funcPreffix.$nodeName;
                    $object = $this->object->$funcName();
                    $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';
                    $className = $prefClassName . $nodeName;
                    
                    $newTag = new $className($object, FALSE);

                    $newTag->fromXPDL($node);                    
                }
            }
        }
    }
    
    private function isChoiceable($choiceable) {
        return $choiceable == 'Event' || $choiceable == 'Implementation' || $choiceable == 'Route';
    }

    private function doEventXPDL() {
        return;
    }

    private function doRouteXPDL() {
        return;
    }

    private function doImplementationXPDL(&$thisObjectTag) {
        $thisObjectTag->setAttribute('IsForCompensation', $this->object->getisForCompensation());
        $thisObjectTag->setAttribute('StartMode', $this->object->getStartMode());
        $thisObjectTag->setAttribute('FinishMode', $this->object->getFinishMode());
        $thisObjectTag->setAttribute('StartQuantity', $this->object->getStartQuantity());
        $thisObjectTag->setAttribute('CompletionQuantity', $this->object->getCompletionQuantity());
    }

}

?>
