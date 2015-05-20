<?php

class ZendExt_WF_WFObject_Entidades_ProcessHeader extends ZendExt_WF_WFObject_Base_Complex {

    private $DurationUnit;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ProcessHeader';
    }

    public function clonar() {
        return;
    }

    public function getDurationUnit() {
        return $this->DurationUnit->getSelectedItem();
    }

    public function setDurationUnit($durationUnit) {
        $this->DurationUnit->selectItem($durationUnit);
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Created($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Description($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Priority($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Limit($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ValidFrom($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ValidTo($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_TimeEstimation($this));

        $durationUnitChoices = array("Y", "M", "D", "h", "m", "s");
        $this->DurationUnit = new ZendExt_WF_WFObject_Base_SimpleChoice('DurationUnit', $durationUnitChoices, null);
        return;
    }

    public function getCreated() {
        return $this->get('Created');
    }

    public function getDescription() {
        return $this->get('Description');
    }

    public function getLimit() {
        return $this->get('Limit');
    }

    public function getValidFrom() {
        return $this->get('ValidFrom');
    }

    public function getValidTo() {
        return $this->get('ValidTo');
    }

    public function getTimeEstimation() {
        return $this->get('TimeEstimation');
    }

    public function getPriority() {
        return $this->get('Priority');
    }

    public function toArray() {
        $array = array(
            'DurationUnit' => $this->getDurationUnit(),
            'Created' => $this->getCreated()->toArray(),
            'Description' => $this->getDescription()->toArray(),
            'Priority' => $this->getPriority()->toArray(),
            'Limit' => $this->getLimit()->toArray(),
            'ValidFrom' => $this->getValidFrom()->toArray(),
            'ValidTo' => $this->getValidTo()->toArray(),
            'TimeEstimation' => $this->getTimeEstimation()->toArray(),
        );
        return $array;
    }

}

?>
