<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WorkflowManager
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_WorkflowManager {

    //put your code here
    private $workflowProcess = null;

    public function __construct($workflowProcess = null) {
        if ($workflowProcess !== null) {
            $this->workflowProcess = $workflowProcess;
        }
    }

    public function setWorkflowProcess($workflowProcess) {
        if ($this->workflowProcess !== null) {
            $this->workflowProcess = $workflowProcess;
        }
    }

    public function getActivities() {
        return $this->workflowProcess->getActivities();
    }

    public function getTransitions() {
        return $this->workflowProcess->getTransitions();
    }

    public function isEvent($activityId) {
        $activities = $this->getActivities();
        $activity = $activities->findById($activityId);
        if ($activity !== null) {
            return ($activity->getActivityType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_Event);
        }
    }

    public function isEndEvent($idActivity) {
        $activity = $this->getActivityById($idActivity);
        $isEvent = $this->isEvent($idActivity);
        if ($isEvent) {
            return $activity->getActivityType()->getSelectedItem()->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_EndEvent;
        }
    }

    public function hasStartEvent() {
        $activities = $this->getActivities();
        $countActivities = $activities->count();
        for ($i = 0; $i < $countActivities; $i++) {
            $activity = $activities->get($i);
            $activityType = $activity->getActivityType()->getSelectedItem();
            if ($activityType instanceof ZendExt_WF_WFObject_Entidades_Event) {
                $eventType = $activityType->getEventType()->getSelectedItem();
                if ($eventType->getTagName() === 'StartEvent') {
                    return true;
                }
            }
        }
        return false;
    }

    public function hasEndEvent() {
        $activities = $this->getActivities();
        $countActivities = $activities->count();
        for ($i = 0; $i < $countActivities; $i++) {
            $activity = $activities->get($i);
            $activityType = $activity->getActivityType()->getSelectedItem();
            if ($activityType instanceof ZendExt_WF_WFObject_Entidades_Event) {
                $eventType = $activityType->getEventType()->getSelectedItem();
                if ($eventType->getTagName() === 'EndEvent') {
                    return true;
                }
            }
        }
        return false;
    }

    public function findEndEvent() {
        $activities = $this->getActivities();
        $countActivities = $activities->count();
        for ($i = 0; $i < $countActivities; $i++) {
            $activity = $activities->get($i);
            $activityType = $activity->getActivityType()->getSelectedItem();
            if ($activityType instanceof ZendExt_WF_WFObject_Entidades_Event) {
                $eventType = $activityType->getEventType()->getSelectedItem();
                if ($eventType->getTagName() === 'EndEvent') {
                    return $activity;
                }
            }
        }
        throw new Exception('Not found');
    }

    public function findFirstActivity() {
        $tos = array();
        for ($i = 0; $i < $this->getTransitions()->count(); $i++) {
            $tos[] = $this->getTransitions()->get($i)->getTo();
        }
        $ids = array();
        for ($i = 0; $i < $this->getActivities()->count(); $i++) {
            $ids[] = $this->getActivities()->get($i)->getId();
        }
        $notContained = array_diff($ids, $tos);

        return $notContained;
    }

    public function findOutgoingTransitionsFromActivity($idActivity) {
        $result = array();
        $transitions = $this->workflowProcess->getTransitions();
        for ($i = 0; $i < $transitions->count(); $i++) {
            $transition = $transitions->get($i);
            if ($transition->getFrom() === $idActivity) {
                $result[] = $transition;
            }
        }
        return $result;
    }

    public function findIncomingTransitionsToActivity($idActivity) {
        $result = array();
        $transitions = $this->workflowProcess->getTransitions();
        for ($i = 0; $i < $transitions->count(); $i++) {
            $transition = $transitions->get($i);
            if ($transition->getTo() === $idActivity) {
                $result[] = $transition;
            }
        }
        return $result;
    }

    public function getActivityById($idActivity) {
        return $this->getActivities()->findById($idActivity);
    }

    public function getNextActivity($idActivity/*, $visitedTransitions*/) {
        $outgoingTransitions = $this->findOutgoingTransitionsFromActivity($idActivity);
        if (!empty($outgoingTransitions)) {
            //$diff = array_diff($outgoingTransitions, $visitedTransitions);
            $to = $outgoingTransitions[0]->getTo();
            $return = $this->getActivityById($to);
            return $return;
        }
        return null;
        
    }

    public function getTransitionById($idTransition) {
        return $this->getTransitions()->findById($idTransition);
    }

}

?>
