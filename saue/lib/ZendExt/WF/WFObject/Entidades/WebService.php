<?php

class ZendExt_WF_WFObject_Entidades_WebService extends ZendExt_WF_WFObject_Base_Complex {

    private $InputMsgName;
    private $OutputMsgName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = "WebService";
    }

    public function getInputMsgName() {
        return $this->InputMsgName;
    }

    public function setInputMsgName($_inputMsgName) {
        $this->InputMsgName = $_inputMsgName;
    }

    public function getOutputMsgName() {
        return $this->OutputMsgName;
    }

    public function setOutputMsgName($_outputMsgName) {
        $this->OutputMsgName = $_outputMsgName;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceFaultCatch($this));
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    public function toArray() {
        $array = array(
            'InputMsgName' => $this->getInputMsgName(),
            'OutputMsgName' => $this->getOutputMsgName(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray()
        );
        return $array;
    }

}

?>
