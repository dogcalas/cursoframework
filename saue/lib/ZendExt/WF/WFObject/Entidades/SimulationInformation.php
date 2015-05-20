<?php

class ZendExt_WF_WFObject_Entidades_SimulationInformation extends ZendExt_WF_WFObject_Base_Complex {

    private $Instantiation;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'SimulationInformation';
    }

    public function getTimeEstimation() {
        return $this->get('TimeEstimation');
    }

    public function getCost() {
        return $this->get('Cost');
    }

    public function getInstantiation() {
        return $this->Instantiation->getSelectedItem();
    }

    public function setInstantiation($_instantiation) {
        $this->Instantiation->selectItem($_instantiation);
    }


    public function toArray() {
        $array = array(
            'Instantiation' => $this->getInstantiation(),
            'Cost' => $this->getCost()->toArray(),
            'TimeEstimation' => $this->getTimeEstimation()->toArray()
        );
        return $array;
    }


    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Cost($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TimeEstimation($this));
        
        $instantiationChoices = array('ONCE','MULTIPLE');
        $this->Instantiation = new ZendExt_WF_WFObject_Base_SimpleChoice('Instantiation', $instantiationChoices, NULL);
    }

}

?>
