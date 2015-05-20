<?php

class ZendExt_WF_WFObject_Entidades_BlockActivity extends ZendExt_WF_WFObject_Base_Complex {

    private $ActivitySetId;
    private $StartActivityId;
    private $View;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'BlockActivity';
    }

    /*
     * Getters
     */
    public function getActivitySetId() {
        return $this->ActivitySetId;
    }

    public function getStartActivityId() {
        return $this->StartActivityId;
    }

    public function getView() {
        return $this->View->getSelectedItem();
    }

    /*
     * Setters
     */
    public function setView($_view) {
        $this->View->selectItem($_view);
    }

    public function setStartActivityId($_startActivityId) {
        $this->StartActivityId = $_startActivityId;
    }

    public function setActivitySetId($_activitySetId) {
        $this->ActivitySetId = $_activitySetId;
    }

    
    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $viewChoices = array('COLLAPSED', 'EXPANDED');
        $this->View = new ZendExt_WF_WFObject_Base_SimpleChoice('View', $viewChoices, NULL);

        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */        
        return;
    }
    
    public function toArray() {
        $array = array(
            'ActivitySetId' => $this->getActivitySetId(),
            'StartActivityId' => $this->getStartActivityId(),
            'View' => $this->getView()->getSelectedItem()
        );
        return $array;
    }

}

?>
