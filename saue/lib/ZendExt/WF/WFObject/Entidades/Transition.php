<?php

class ZendExt_WF_WFObject_Entidades_Transition extends ZendExt_WF_WFObject_Base_Complex {

    private $From;
    private $To;
    private $Quantity;

    /*
     * No es un atributo de una transicion segun las
     * especificaciones, pero dado que las transiciones
     * pueden ser de los tipos:
     * [CONDITION, OTHERWISE,...]
     * se agrega a fin de saber si una transition deberia
     * adicionar una instancia de Condition o no, amen de que
     * las transiciones pueden o no tener una Condition
     */
    private $ConditionType;
    private $Condition;

    public function __construct($parent, $fillStructure = TRUE) {
        parent::__construct($parent, $fillStructure);

        $this->tagName = 'Transition';
        $this->Condition = NULL;
    }

    /*
     * Setters
     */

    public function setQuantity($_quantity) {
        $this->Quantity = $_quantity;
    }

    public function setFrom($_from) {
        $this->From = $_from;
    }

    public function setTo($_to) {
        $this->To = $_to;
    }

    public function setConditionType($_conditionType) {
        if ($_conditionType !== $this->getConditionType()) {
            $this->ConditionType->selectItem($_conditionType);
            if ($this->ConditionType->getSelectedItem() !== 'NONE') {
                if ($this->Condition === NULL) {
                    $this->addCondition();
                }
            }            
        }
    }

    public function addCondition() {
        $this->Condition = new ZendExt_WF_WFObject_Entidades_Condition($this);
        //$this->Condition->setType($this->getConditionType());
    }

    /*
     * Getters
     */

    public function getCondition() {
        //return $this->get('Condition');
        return $this->Condition;
    }

    public function getDescription() {
        return $this->get('Description');
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

    public function getConnectorGraphicsInfos() {
        return $this->get('ConnectorGraphicsInfos');
    }

    public function getQuantity() {
        return $this->Quantity;
    }

    public function getFrom() {
        return $this->From;
    }

    public function getTo() {
        return $this->To;
    }

    public function getConditionType() {
        return $this->ConditionType->getSelectedItem();
    }

    public function isConditional() {
        return $this->Condition !== NULL && $this->Condition->getType() === 'CONDITION';
    }

    /*
     * Abstractions
     */

    public function clonar() {
        /*
         * llamado desde translatorzc para clonar transiciones
         */
        $clone = new ZendExt_WF_WFObject_Entidades_Transition($this->parent/*, FALSE*/);
        
        //$_condition = $this->getCondition();

        /*if ($_condition !== null) {
            $_conditionTypeChoices = array('NONE', 'CONDITION', 'OTHERWISE', 'EXCEPTION', 'DEFAULTEXCEPTION');
            $clone->ConditionType = new ZendExt_WF_WFObject_Base_SimpleChoice('ConditionType', $_conditionTypeChoices, NULL, 0);
        }*/



        $conditionType = $this->getConditionType();

        $clone->setConditionType($conditionType);
        
        if ($conditionType !== 'NONE') {
            $condition = $this->getCondition();
            if($condition !== null){
                $condition->setType($conditionType);;
                $_condition = $condition->clonar();
                //$clone->Condition = $_condition;            
                print_r('analizar clonar transition ');die;
            }
        }


        $from = $this->getFrom();
        $to = $this->getTo();
        $id = $this->getId();

        $clone->setFrom($from);
        $clone->setTo($to);
        $clone->setId($id);

        //die('cloned');
        /*
         * Falta clonar el resto de los elementos...
         */
        return $clone;
    }

    public function fillStructure() {
        $_conditionTypeChoices = array('NONE', 'CONDITION', 'OTHERWISE', 'EXCEPTION', 'DEFAULTEXCEPTION');
        $this->ConditionType = new ZendExt_WF_WFObject_Base_SimpleChoice('ConditionType', $_conditionTypeChoices, NULL, 0);

        //modified on May 8th, 2013
        //$this->addCondition();

        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ExtendedAttributes($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Assignments($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfos($this));
    }

    public function toArray() {
        $array = array(
            'From' => $this->getFrom(),
            'Id' => $this->getId(),
            'To' => $this->getTo(),
            'Name' => $this->getName(),
            'Quantity' => $this->getQuantity(),
            'Condition' => $this->getCondition()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'ExtendedAttributes' => $this->getExtendedAttributes()->toArray(),
            'Assignments' => $this->getAssignments()->toArray(),
            'Object' => $this->getObject()->toArray(),
            'ConnectorGraphicsInfos' => $this->getConnectorGraphicsInfos()->toArray()
        );
        return $array;
    }

}

?>
