<?php

class ZendExt_WF_WFObject_Entidades_Association extends ZendExt_WF_WFObject_Base_Complex {

    private $Source;
    private $Target;
    private $associationDirection;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Association';
    }

    
    /*
     * Getters
     */
    public function getSource() {
        return $this->Source;
    }

    public function getTarget() {
        return $this->Target;
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getConnectorGraphicsInfos() {
        return $this->get('ConnectorGraphicsInfos');
    }

    public function getAssociationDirection() {
        return $this->associationDirection->getSelectedItem();
    }

    
    /*
     * Setters
     */
    public function setTarget($_target) {
        $this->Target = $_target;
    }

    public function setSource($_source) {
        $this->Source = $_source;
    }

    public function setAssociationDirection($_associationDirection) {
        $this->associationDirection->selectItem($_associationDirection);
    }

    
    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfos($this));

        $associationDirectionChoices = array('None', 'To', 'From', 'Both');
        $this->associationDirection = new ZendExt_WF_WFObject_Base_SimpleChoice('AssociationDirection', $associationDirectionChoices, NULL);
        return;
    }
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'Source' => $this->getSource(),
            'Target' => $this->getTarget(),
            'AssociationDirection' => $this->getAssociationDirection(),
            'Object' => $this->getObject()->toArray(),
            'ConnectorGraphicsInfos' => $this->getConnectorGraphicsInfos()->toArray()
        );
        return $array;
    }

}

?>
