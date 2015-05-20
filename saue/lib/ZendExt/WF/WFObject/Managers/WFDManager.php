<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WFDManager
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Managers_WFDManager {
    private $workflowProcessDefinition = NULL;
    
    public function __construct($_workflowProcessDefinition = NULL) {
        $this->workflowProcessDefinition = $_workflowProcessDefinition;
    }
    public function addWorkflowProcessDefinition($_workflowProcessDefinition) {
        if($this->workflowProcessDefinition === NULL){
            $this->workflowProcessDefinition = $_workflowProcessDefinition;
        }
    }
    /*
     * returns true if a start event has been set
     */
    public function hasStartEvent(){
        $activities = $this->workflowProcessDefinition->getActivities();
        for($i = 0; $i < $activities->count(); $i++){
            $activity = $activities->get($i)->getActivityType()->getSelectedItem();
            if($activity instanceof ZendExt_WF_WFObject_Entidades_Event){
                $eventType = $activity->getEventType()->getSelectedItem();
                $isStartEvent = $eventType instanceof ZendExt_WF_WFObject_Entidades_StartEvent;
                return $isStartEvent;
            }
        }
        return FALSE;
    }
    
    public function getStartEvent() {
        if($this->workflowProcessDefinition === NULL){
            throw new Exception('workflowProcessDefinition is null');
        }  else {
            $activities = $this->workflowProcessDefinition->getActivities();
            $returnActivity = NULL;
            for($i = 0; $i < $activities->count(); $i++){
                $activity = $activities->get($i)->getActivityType()->getSelectedItem();
                if($activity instanceof ZendExt_WF_WFObject_Entidades_Event){
                    $eventType = $activity->getEventType()->getSelectedItem();
                    $isStartEvent = $eventType instanceof ZendExt_WF_WFObject_Entidades_StartEvent;
                    if ($isStartEvent) {
                        $returnActivity = $activity;
                        break;                        
                    }
                }
            }            
            return $returnActivity;
        }
    }
    
    public function getStartActivities() {
        $hasStartEvent = $this->hasStartEvent();
        if($hasStartEvent){
            throw new Exception('Existe un evento de inicio definido.');
        }  else {
            $returnActivities = array();            
            $activities = $this->getActivities();
            
            for($i = 0; $i < $activities->count(); $i++){
                $activity = $activities->get($i);
                if($activity->getActivityType()->getSelectedItem()){
                    $foundTransitions = $this->findTransitionsToNode($activity->getId());                
                    if(count($foundTransitions) === 0){
                        $returnActivities[] = $activity;
                    }
                }
            }
            if(!$hasStartEvent && count($returnActivities) === 0){
                throw new Exception('No existe un evento de inicio, y todas las actividades tienen flujos de secuencia entrante.');
            }  else {
                return $returnActivities;
            }
        }
    }
    public function findTransitionsToNode($_targetNodeId) {
        $transitions = $this->getTransitions();
        $countTransitions = $transitions->count();
        $foundTransitions = array();
        for($i = 0; $i < $countTransitions; $i++){
            $_transition = $transitions->get($i);
            if($_transition->getTo() == $_targetNodeId){
                $foundTransitions[] = $_transition;
            }
        }
        return $foundTransitions;
    }
    
    public function getTransitionsById(ZendExt_WF_WFObject_Entidades_TransitionRefs $_transitionRefs) {
        /*
         * $_transitionRefs is a collection of references to transitions
         */
        if($_transitionRefs instanceof ZendExt_WF_WFObject_Base_Collections){
            $countTransitionRefs = $_transitionRefs->count();
            $_transitions = array();
            for($i = 0; $i < $countTransitionRefs; $i++){
                $_transitions[] = $this->getTransitions()->findById($_transitionRefs->get($i)->getId());
            }            
            return $_transitions;
        }  else {
            throw new Exception('Invalid argument.');
        }
    }   
    
    public function getTransitionRestrictions($activityId) {
        $activity = $this->getActivities()->findById($activityId);
        if($activity->getTransitionRestrictions()->isEmpty()){
            return NULL;
        }  else {
            return $activity->getTransitionRestrictions();
        }
    }
    
    public function getActivities(){
        return $this->workflowProcessDefinition->getActivities();
    }
    
    public function getTransitions(){
        return $this->workflowProcessDefinition->getTransitions();
    }    
}

?>
