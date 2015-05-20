<?php

class ZendExt_WF_WFObject_Entidades_ConformanceClass extends ZendExt_WF_WFObject_Base_Complex {

    private $GraphConformance;
    private $BPMNModelPortabilityConformance;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ConformanceClass';
    }

    public function setGraphConformance($gConformance) {
        $this->GraphConformance->selectItem($gConformance);
    }

    public function setBPMNModelPortabilityConformance($portability) {
        $this->BPMNModelPortabilityConformance->selectItem($portability);
    }

    public function getGraphConformance() {
        return $this->GraphConformance->getSelectedItem();
    }

    public function getBPMNModelPortabilityConformance() {
        return $this->BPMNModelPortabilityConformance->getSelectedItem();
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $graphConformanceChoices = array('FULL_BLOCKED', 'LOOP_BLOCKED', 'NON_BLOCKED');
        $this->GraphConformance = new ZendExt_WF_WFObject_Base_SimpleChoice('GraphConformance', $graphConformanceChoices, NULL);

        $BPMNModelPortabilityConformanceChoices = array('NONE', 'SIMPLE', 'STANDARD', 'COMPLETE');
        $this->BPMNModelPortabilityConformance = new ZendExt_WF_WFObject_Base_SimpleChoice('BPMNModelPortabilityConformance', $BPMNModelPortabilityConformanceChoices, NULL);
        return;
    }
    
    
    public function toArray() {
        $array = array(
            'GraphConformance' => $this->getGraphConformance(),
            'BPMNModelPortabilityConformance' => $this->getBPMNModelPortabilityConformance()
        );
        return $array;
    }

}

?>
