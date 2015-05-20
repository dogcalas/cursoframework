<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TranslatorBPEL
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_TranslatorBPEL {

    private $bpmnObject;
    private $wfManager;
    private $bpelProcess;
    private $visitedTransitions;
    private $synchronizers;

    public function __construct($bpmnObject) {
        $this->bpmnObject = $bpmnObject;
        $this->visitedTransitions = array();

        $this->synchronizers = array();
    }

    private function addSynchronizer($synchronizer) {
        if (is_array($synchronizer)) {
            foreach ($synchronizer as $sync) {
                $this->addSynchronizer($sync);
            }
        } else {
            if (!array_key_exists($synchronizer, $this->synchronizers)) {
                $this->synchronizers[$synchronizer] = $synchronizer;
            }
        }
    }

    private function isSynchronizer($synchronizer) {
        return array_key_exists($synchronizer, $this->synchronizers);
    }

    public function saveTo($path) {
        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->formatOutput = true;
        $bpelModelXML = new ZendExt_WF_BPEL_ModeloBPELXML_process($this->bpelProcess, true);
        $result = null;
        $bpelModelXML->toXML($domDocument, $result);
        $domDocument->save($path);
    }

    public function parse() {
        $this->processWorkflowProcesses($this->bpmnObject);
        return $this->bpelProcess;
    }

    private function processWorkflowProcesses($package) {
        $workflowProcesses = $package->getWorkflowProcesses();
        for ($i = 0; $i < $workflowProcesses->count(); $i++) {
            $workflowProcess = $workflowProcesses->get($i);
            $this->processWorkflowProcess($workflowProcess);
            return;
        }
    }

    private function processWorkflowProcess($workflowProcess) {
        $this->bpelProcess = new ZendExt_WF_BPEL_ModeloBPEL_process(null);
        $this->wfManager = new ZendExt_WF_BPEL_WorkflowManager($workflowProcess);
        $activities = $this->wfManager->getActivities();
        $transitions = $this->wfManager->getTransitions();

        $this->readWorkflowPatterns($activities, $transitions);
    }

    private function readWorkflowPatterns($activities, $transitions) {
        $firstActivity = $this->wfManager->findFirstActivity();
        $countActivities = count($firstActivity);
        if ($countActivities === 1) {
            if ($this->wfManager->hasStartEvent() && $this->wfManager->isEvent($firstActivity[0])) {
                if ($this->wfManager->hasEndEvent()) {
                    $endEvent = $this->wfManager->findEndEvent();
                    if ($endEvent !== NULL) {
                        $bpelActivity = $this->bpelProcess->getActivities()->createObject();
                        $bpelActivity->selectItem('sequence');
                        $this->bpelProcess->getActivities()->add($bpelActivity);
                        $xpdlActivity = $this->wfManager->getActivityById($firstActivity[0]);
                        $this->readPattern($xpdlActivity, $bpelActivity->getSelectedObject());
                    }
                }
            }
        } else {
            die('hacer otra cosa... translatorbpel');
        }
    }

    private function readPattern($xpdlActivity, $bpelActivity, $limit = null) {
        $bpelActivitySelectedObjectType = $bpelActivity->getTagName();
        switch ($bpelActivitySelectedObjectType) {
            case 'sequence':
                $this->populateSequence($xpdlActivity, $bpelActivity, $limit);
                break;
            case 'if':
                $this->populateIf($xpdlActivity, $bpelActivity);
                break;
            case 'flow':
                $this->populateControlFlow($xpdlActivity, $bpelActivity);
                break;
            default:
                break;
        }
    }

    /*
     * RAE:
     * secuencia.
      (Del lat. sequentĭa, continuación; de sequi, seguir).
      1. f. Continuidad, sucesión ordenada.
      2. f. Serie o sucesión de cosas que guardan entre sí cierta relación.
     *  3. ...
     */
    
    private function populateIf($xpdlActivity, $bpelIf){
        $outgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($xpdlActivity);
        $countOutgoingTransitions = count($outgoingTransitions);
        $ifCondition = $bpelIf->getCondition();
        $else = $bpelIf->getElse();
        $ifCondition->setValue('lolo');
    }

    private function populateSequence($xpdlActivity, $bpelSequence, $limit = null) {
        do {
            $bpel = $this->toBPEL($xpdlActivity, $bpelSequence);
            if ($bpel !== null) {
                if (is_array($bpel)) {
                    $length = count($bpel);
                    for ($i = 0; $i < $length; $i++) {
                        $bpelSequence->getActivities()->add($bpel[$i]);
                    }
                } else {
                    $bpelSequence->getActivities()->add($bpel);
                }
            } else {
                if($xpdlActivity === null){
                    break;                
                }
            }
            $xpdlActivity = $this->next($xpdlActivity);
            if ($limit !== null && $xpdlActivity->getId() === $limit) {
                break; 
            }
        } while ($xpdlActivity !== null);
    }

    private function populateControlFlow($xpdl, $bpelFlow) {
        $outgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($xpdl->getId());        
        
        $synchronizer = $this->findFlowLimit($xpdl, $outgoingTransitions);

        if ($synchronizer !== null) {
            $this->addSynchronizer($synchronizer);
        }

        $bpelActivities = array();

        for ($i = 0; $i < count($outgoingTransitions); $i++) {
            $mapped = false;
            
            $to = $outgoingTransitions[$i]->getTo();
            $xpdlActivity = $this->wfManager->getActivityById(strval($to));

            $next = $this->next($xpdlActivity);
            
            $xpdlActivityOutgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($xpdlActivity->getId());
            $count = count($xpdlActivityOutgoingTransitions);
            
            if ($next !== null) {
                if ($count > 1) {
                    $activity = $bpelFlow->getActivities()->createObject();
                    $activity->selectItem('sequence');
                    $this->readPattern($xpdlActivity, $activity->getSelectedObject(), $synchronizer);
                    $result = $activity->getSelectedObject();
                    if (is_array($result)) {
                        foreach ($result as $value) {
                            $bpelActivities[] = $value;
                        }
                    } else {
                        $bpelActivities[] = $result;
                    } 
                    $mapped = true;
                }  else {
                    if ($count !== 0 && $next->getId() !== $synchronizer) {
                        $activity = $bpelFlow->getActivities()->createObject();
                        $activity->selectItem('sequence');
                        $this->readPattern($xpdlActivity, $activity->getSelectedObject(), $synchronizer);
                        $result = $activity->getSelectedObject();
                        if (is_array($result)) {
                            foreach ($result as $value) {
                                $bpelActivities[] = $value;
                            }
                        } else {
                            $bpelActivities[] = $result;
                        } 
                        $mapped = true;
                    }
                }
            }
            if (!$mapped) {
                $bpelActivity = $this->toBPEL($xpdlActivity, $bpelFlow);
                if (is_array($bpelActivity)) {
                    foreach ($bpelActivity as $value) {
                        $bpelActivities[] = $value;
                    }
                } else {
                    $bpelActivities[] = $bpelActivity;
                }                
            }
        }

        $counterBPELActivities = count($bpelActivities);
        for ($i = 0; $i < $counterBPELActivities; $i++) {
            $bpelFlow->getActivities()->add($bpelActivities[$i]);
        }
    }

    /*
     * Mejorar esta funcion (findFlowLimit) en el sentido de que no podemos recorrer
     * el grafo completo cada vez que encontremos un flow, o sea, es preciso
     * considerar solo las tareas a las que con mas frecuencia tienden las actividades
     * salientes del flujo de trabajo. A modo de ejemplo, considerar una actividad A
     * de las que salen 2 actividades en paralelo (B y C), luego convergen en una actividad D,
     * detras de la que sigue una actividad E. Esta funcion considera para el analisis tambien
     * a la actividad E, lo cual es incorrecto, puesto que las actividades salientes B y C
     * convergen en D, la cual seria potencialmente el limite de la actividad A que origino
     * el flow.
     */

    private function findFlowLimit($xpdl, $outgoingTransitions) {
        $countOutgoingTransitions = count($outgoingTransitions);
        if ($countOutgoingTransitions > 1) {

            $toReturn = null;
            $tempTos = array();
            $tos = array();

            for ($i = 0; $i < count($outgoingTransitions); $i++) {
                $outgoingTransition = $outgoingTransitions[$i];
                $to = $outgoingTransition->getTo();
                $incomingTransitions = $this->wfManager->findIncomingTransitionsToActivity($to);
                if (count($incomingTransitions) > 1) {
                    /*
                     * Las bifurcaciones convergen en un punto, por lo tanto
                     * ese punto debe tener por lo menos dos flujos de secuencia
                     * entrantes... Ahora bien, puede ser que a un punto lleguen 
                     * dos flujos de secuencia(tenga flujos de secuencia entrantes),
                     * pero eso no significa que sea el punto de convergencia de la
                     * actividad $xpdl, por lo tanto, debo tratar de expandir las 
                     * posibilidades despues de la actividad convergente de flujos.
                     */
                    if (!array_key_exists($to, $tos)) {
                        $tos[$to] = array('counter' => 1);
                        $toActivity = $this->wfManager->getActivityById($to);
                        $tmpActivity = $this->next($toActivity);
                        if ($tmpActivity !== null) {
                            $to = $tmpActivity->getId();
                        }
                    } else {
                        $tos[$to]['counter']++;
                    }
                }
                $_outgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($to);
                foreach ($_outgoingTransitions as $_outgoingTransition) {
                    $outgoingTransitions[] = $_outgoingTransition;
                }
            }
            if (count($tos) !== 0) {
                foreach ($tos as $to => $value) {
                    $froms = $this->traverseBack($this->wfManager->findIncomingTransitionsToActivity(strval($to)), $xpdl->getId());
                    if (array_key_exists($xpdl->getId(), $froms)) {
                        $toReturn = $to;
                        break;
                    }
                }
            }
            return strval($toReturn);
        }
    }

    private function toBPEL($xpdl, $bpelActivity) {
        $result = null;
        $isRoute = false;
        $route = null;
        if (!isset($xpdl)) {
            return null;
        } else {
            if ($xpdl instanceof ZendExt_WF_WFObject_Entidades_Activity) {
                $activityType = $xpdl->getActivityType()->getSelectedItem();
                switch ($activityType->getTagName()) {
                    case 'Event': {
                            $eventType = $activityType->getEventType()->getSelectedItem();
                            if ($eventType->getTagName() === 'StartEvent') {
                                $result = $this->doStartEventBPELMapping($eventType, $bpelActivity);
                            } else {
                                if ($eventType->getTagName() === 'EndEvent') {
                                    $result = $this->doEndEventBPELMapping($eventType, $bpelActivity);
                                    return $result;
                                }
                            }
                        }
                        break;
                    case 'Implementation': {
                            $implementationType = $activityType->getSelectedItem();
                            if ($implementationType->getTagName() === 'Task') {
                                $result = $this->doImplementationBPELMapping($implementationType, $bpelActivity);
                            }
                        }
                        break;
                    case 'Route': {
                            $isRoute = true;
                            $route = $activityType;
                        }
                        break;
                    default:
                        break;
                }
            } else {
                return null;
            }

            $outgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($xpdl->getId());
            $countOutgoingTransitions = count($outgoingTransitions);
            
            //$incomingTransitions = $this->wfManager->findIncomingTransitionsToActivity($xpdl->getId());
            //$countincomingTransitions = count($incomingTransitions);

            if ($countOutgoingTransitions > 1) {
                if (!is_array($result) && $result !== null) {
                    $_result = array($result);
                    $result = $_result;
                }
                $behaviour = $this->validTransitions($outgoingTransitions);
                $_bpelActivity = $bpelActivity->getActivities();
                switch ($behaviour) {
                    case 1: {
                            if ($isRoute) {
                                if ($route !== null) {
                                    $gatewayType = $route->getGatewayType();
                                    switch ($gatewayType) {
                                        case 'Parallel': {
                                                $created = $_bpelActivity->createObject();
                                                $created->selectItem('flow');
                                                $created = $created->getSelectedObject();
                                                $this->readPattern($xpdl, $created);
                                                $result[] = $created;
                                            }
                                            break;
                                        default:
                                            throw new Exception('Revisar la actividad ' . $xpdl->getId() . ' del diagrama');
                                            break;
                                    }
                                } else {
                                    throw new Exception('Revisar la actividad ' . $xpdl->getId() . ' del diagrama');
                                }
                            } else {
                                $created = $_bpelActivity->createObject();
                                $created->selectItem('flow');
                                $created = $created->getSelectedObject();
                                $this->readPattern($xpdl, $created);
                                $result[] = $created;
                            }
                        }
                        break;
                    case 2: {
                            if ($isRoute) {
                                if ($route !== null) {
                                    $gatewayType = $route->getGatewayType();
                                    switch ($gatewayType) {
                                        case 'Exclusive': {
                                                $xortype = $route->getXORType();
                                                if ($xortype === 'Data' || $xortype === 'Event') {
                                                    if ($xortype === 'Data') {
                                                        $created = $_bpelActivity->createObject();
                                                        $created->selectItem('if');
                                                        $created = $created->getSelectedObject();
                                                        $this->readPattern($xpdl, $created);
                                                        $result[] = $created;
                                                    } else {
                                                        $created = $_bpelActivity->createObject();
                                                        $created->selectItem('pick');
                                                        $created = $created->getSelectedObject();
                                                        $this->readPattern($xpdl, $created);
                                                        $result[] = $created;
                                                    }
                                                } else {
                                                    throw new Exception('Error desconocido');
                                                }
                                            }
                                            break;
                                        case 'Inclusive': {
                                                
                                            }
                                            break;
                                        case 'Complex': {
                                                
                                            }
                                            break;
                                    }
                                } else {
                                    throw new Exception('Revisar la actividad ' . $xpdl->getId() . ' del diagrama');
                                }
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
            /*if ($countincomingTransitions > 1) {
                if ($isRoute) {
                    
                }
            }*/
            return $result;
        }
    }

    private function validTransitions($outgoingTransitions) {
        $counter = count($outgoingTransitions);
        $uncontrolled = 0;
        $conditional = 0;
        $default = 0;
        foreach ($outgoingTransitions as $transition) {
            if ($transition->isConditional()) {
                $condition = $transition->getCondition();
                if ($condition->getType() === 'OTHERWISE') {
                    $default++;
                } else {
                    if ($condition->getType() === 'CONDITION') {
                        $conditional++;
                    }
                }
            } else {
                $uncontrolled++;
            }
        }
        if ($uncontrolled === $counter) {
            return 1;
        } else {
            if ($conditional === $counter) {
                return 2;
            } else {
                if ($default > 1) {
                    return 0;
                } else {
                    if ($conditional + $default === $counter) {
                        return 3;
                    } else {
                        return 0;
                    }
                }
            }
        }
    }

    private function doStartEventBPELMapping($startEvent, $parent) {
        $result = null;
        $trigger = $startEvent->getTrigger();
        switch ($trigger) {
            case 'Message':
                $result = $parent->getActivities()->createObject();
                $result->selectItem('receive');
                break;
            default:
                break;
        }
        return $result;
    }

    private function doEndEventBPELMapping($endEvent, $parent) {
        $trigger = $endEvent->getTrigger();
        switch ($trigger) {
            case 'Message':
                $bpelActivity = $parent->getActivities()->createObject();
                $bpelActivity->selectItem('reply');
                $parent->getActivities()->add($bpelActivity);
                break;
            case 'Terminate':
                $bpelActivity = $parent->getActivities()->createObject();
                $bpelActivity->selectItem('terminate');
                $parent->getActivities()->add($bpelActivity);
                break;            
            default:
                break;
        }
    }

    private function doImplementationBPELMapping($implementationType, $parent) {
        $result = null;
        $taskType = $implementationType->getTaskType()->getSelectedItem();
        switch ($taskType->getTagName()) {
            case 'TaskZendAction':
                $result = $parent->getActivities()->createObject();
                $result->selectItem('invoke');
                break;
            default:
                break;
        }
        return $result;
    }

    private function traverseBack($incomingTransitions, $until) {
        //Esto va a funcionar en solo un escenario inicial
        $froms = array();
        for ($i = 0; $i < count($incomingTransitions); $i++) {
            $transition = $incomingTransitions[$i];
            $from = $transition->getFrom();
            $transitions = $this->wfManager->findIncomingTransitionsToActivity($from);
            for ($j = 0; $j < count($transitions); $j++) {
                $activityId = $this->wfManager->getActivityById($transitions[$j]->getFrom())->getId();
                if (array_key_exists($activityId, $froms)) {
                    $froms[$activityId]['counter']++;
                } else {
                    $_from = array('counter' => 0);
                    $froms[$activityId] = $_from;
                    if ($activityId !== $until) {
                        $_incomingTransitions = $this->wfManager->findIncomingTransitionsToActivity($activityId);
                        foreach ($_incomingTransitions as $_incomingTransition) {
                            $incomingTransitions[] = $_incomingTransition;
                        }
                    }
                }
            }
        }
        return $froms;
    }

    private function next($activity) {
        $activityId = $activity->getId();
        $outgoingTransitions = $this->wfManager->findOutgoingTransitionsFromActivity($activityId);
        $counterOutgoingTransitions = count($outgoingTransitions);
        if ($counterOutgoingTransitions === 0) {
            return null;
        } else {
            if ($counterOutgoingTransitions > 1) {
                $synchronizer = $this->findFlowLimit($activity, $outgoingTransitions);
                return $this->wfManager->getActivityById(strval($synchronizer));
            } else {
                if ($counterOutgoingTransitions === 1) {
                    return $this->wfManager->getNextActivity($activity->getId());
                }
            }
        }
    }

}

?>
