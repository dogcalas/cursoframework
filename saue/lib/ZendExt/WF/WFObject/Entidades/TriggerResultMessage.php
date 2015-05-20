<?php

class ZendExt_WF_WFObject_Entidades_TriggerResultMessage extends ZendExt_WF_WFObject_Base_Complex {

    private $CatchThrow;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerResultMessage';
    }

    public function getCatchThrow() {
        return $this->CatchThrow->getSelectedItem();
    }

    public function setCatchThrow($_catchThrow) {
        $this->CatchThrow->selectItem($_catchThrow);
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType($this,'Message'));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));
        
        $catchThrowChoices = array('CATCH','THROW');
        $this->CatchThrow = new ZendExt_WF_WFObject_Base_SimpleChoice('CatchTrow', $catchThrowChoices, NULL);
    }

    public function getMessage() {
        return $this->get('Message');
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    public function toArray() {
        $array = array(
            'CatchThrow' => $this->getCatchThrow(),
            'Message' => $this->getMessage() - toArray(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray()
        );
        return $array;
    }

}

?>
