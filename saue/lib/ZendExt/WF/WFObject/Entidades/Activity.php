<?php

class ZendExt_WF_WFObject_Entidades_Activity extends ZendExt_WF_WFObject_Base_Complex {

    private $isForCompensation;
    private $isATransaction;
    private $startActivity;
    private $Status;
    private $StartMode;
    private $FinishMode;
    private $startQuantity;
    private $completionQuantity;
    private $activityType;

    /* private $Id_action;
      private $Id_rol;
      private $Servicio_dir; */

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Activity';
    }

    /*
     * setters
     */
    
    //inflados
    public function setId_action($idAction) {
        $this->Id_action = $idAction;
    }

    public function setId_rol($idRol) {
        $this->Id_rol = $idRol;
    }

    public function setServicio_dir($servicioDir) {
        $this->Servicio_dir = $servicioDir;
    }

    //Originales
    public function setStatus($status) {
        $this->Status->selectItem($status);
    }

    public function setStartMode($startMode) {
        $this->StartMode->selectItem($startMode);
    }

    public function setFinishMode($finishMode) {
        $this->FinishMode->selectItem($finishMode);
    }

    public function setIsForCompensation($_isForCompensation) {
        $this->isForCompensation = $_isForCompensation;
    }

    public function setStartActivity($_startActivity) {
        $this->startActivity = $_startActivity;
    }

    public function setStartQuantity($startQtity) {
        $this->startQuantity = $startQtity;
    }

    public function setCompletionQuantity($completionQtity) {
        $this->completionQuantity = $completionQtity;
    }

    public function setIsATransaction($_isATransaction) {
        $this->isATransaction = $_isATransaction;
    }
    /*
     * getters
     */
    
    //inflados
    public function getServicio_dir() {
        return $this->Servicio_dir;
    }

    public function getId_rol() {
        return $this->Id_rol;
    }

    public function getId_action() {
        return $this->Id_action;
    }

    //originales
    public function getIsForCompensation() {
        return $this->isForCompensation;
    }

    public function getStartActivity() {
        return $this->startActivity;
    }

    public function getStatus() {
        return $this->Status->getSelectedItem();
    }

    public function getStartMode() {
        return $this->StartMode->getSelectedItem();
    }

    public function getFinishMode() {
        return $this->FinishMode->getSelectedItem();
    }

    public function getStartQuantity() {
        return $this->startQuantity;
    }

    public function getCompletionQuantity() {
        return $this->completionQuantity;
    }

    public function getIsATransaction() {
        return $this->isATransaction;
    }
    
    public function getDescription() {
        return $this->get('Description');
    }

    public function getLimit() {
        return $this->get('Limit');
    }

    public function getActivityType() {
        return $this->activityType;
    }

    public function getImplementation() {
        return $this->get('Implementation');
    }

    public function getTransaction() {
        return $this->get('Transaction');
    }

    public function getPriority() {
        return $this->get('Priority');
    }

    public function getSimulationInformation() {
        return $this->get('SimulationInformation');
    }

    public function getIcon() {
        return $this->get('Icon');
    }

    public function getDocumentation() {
        return $this->get('Documentation');
    }

    public function getTransitionRestrictions() {
        //print_r('called');die;
        return $this->get('TransitionRestrictions');
    }

    public function getExtendedAttributes() {
        return $this->get('ExtendedAttributes');
    }

    public function getAssignments() {
        return $this->get('Assignments');
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getNodeGraphicsInfos() {
        return $this->get('NodeGraphicsInfos');
    }

    public function getIORules() {
        return $this->get('IORules');
    }

    public function getVariables() {
        return $this->get('Variables');
    }
    
    /*
     * Overriden methods
     */
    public function toName() {
        return $this->activityType->getSelectedItem()->toName();
    }
    public function clonar() {
        return;
    }
    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Limit($this));
        /*$this->add(new ZendExt_WF_WFObject_Entidades_Transaction($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Performers($this));*/
        $this->add(new ZendExt_WF_WFObject_Entidades_Priority($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_SimulationInformation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Icon($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Documentation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TransitionRestrictions($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        /*$this->add(new ZendExt_WF_WFObject_Entidades_DataFields($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_InputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_OutputSets($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_IORules($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Loop($this));*/
        $this->add(new ZendExt_WF_WFObject_Entidades_Assignments($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_NodeGraphicsInfos($this));

        $options = array(
            new ZendExt_WF_WFObject_Entidades_Event($this),
            new ZendExt_WF_WFObject_Entidades_Route($this),
            new ZendExt_WF_WFObject_Entidades_Implementation(
                    array(
                        new ZendExt_WF_WFObject_Entidades_No($this),
                        new ZendExt_WF_WFObject_Entidades_Task($this),
                        new ZendExt_WF_WFObject_Entidades_SubFlow($this),
                        new ZendExt_WF_WFObject_Entidades_Reference($this))
                    , $this)
        );
        $this->activityType = new ZendExt_WF_WFObject_Base_ComplexChoice(NULL, $options, $this);

        $statusChoices = array('None', 'Ready', 'Active', 'Cancelled', 'Aborting', 'Aborted', 'Completing', 'Completed');
        $this->Status = new ZendExt_WF_WFObject_Base_SimpleChoice('Status', $statusChoices, NULL);

        $startModeChoices = array('Automatic', 'Manual');
        $this->StartMode = new ZendExt_WF_WFObject_Base_SimpleChoice('StartMode', $startModeChoices, NULL);

        $finishModeChoices = array('Automatic', 'Manual');
        $this->FinishMode = new ZendExt_WF_WFObject_Base_SimpleChoice('FinishMode', $finishModeChoices, NULL);
    }

    public function toArray() {

        $array = array(
        'Id' => $this->getId(),
        'IsForCompensation' => $this->getIsForCompensation(),
        'Name' => $this->getName(),
        'StartActivity' => $this->getStartActivity(),
        'Status' => $this->getStatus(),
        'StartMode' => $this->getStartMode(),
        'FinishMode' => $this->getFinishMode(),
        'StartQuantity' => $this->getStartQuantity(),
        'CompletionQuantity' => $this->getCompletionQuantity(),
        'IsATransaction' => $this->getIsATransaction(),
        'ActivityType' => $this->getActivityType(),

        'Description' => $this->getDescription()->toArray(),
        'Limit' => $this->getLimit()->toArray(),
        'Priority' => $this->getPriority()->toArray(),
        'TransitionRestrictions' => $this->getTransitionRestrictions()->toArray(),
        'ExtendedAttributes' => $this->getExtendedAttributes()->toArray(),
        'Assignments' => $this->getAssignments()->toArray(),
        'SimulationInformation()' => $this->getSimulationInformation()->toArray(),
        'Object' => $this->getObject()->toArray(),
        'Icon'=>  $this->getIcon()->toArray(),
        'Documentation'=>  $this->getDocumentation()->toArray(),
        'NodeGraphicsInfos'=>  $this->getNodeGraphicsInfos()->toArray()

        );
        return $array;
    }

}

?>
