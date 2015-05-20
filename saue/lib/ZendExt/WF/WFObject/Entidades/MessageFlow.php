<?php

class ZendExt_WF_WFObject_Entidades_MessageFlow extends ZendExt_WF_WFObject_Base_Complex {

    private $source;
    private $target;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'MessageFlow';
    }

    /*
     * Getters
     */

    public function getSource() {
        return $this->source;
    }

    public function getTarget() {
        return $this->target;
    }

    public function getMessage() {
        return $this->get('Message');
    }

    public function getObject() {
        return $this->get('Object');
    }

    public function getConnectorGraphicsInfos() {
        return $this->get('ConnectorGraphicsInfos');
    }

    /*
     * Setters
     */

    public function setTarget($_target) {
        $this->target = $_target;
    }

    public function setSource($_source) {
        $this->source = $_source;
    }

    /*
     * Abstract
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType($this,'Message'));
        $this->add(new ZendExt_WF_WFObject_Entidades_Object($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfos($this));
        return;
    }

    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName(),
            'Source' => $this->getSource(),
            'Target' => $this->getTarget(),
            'MessageType' => $this->getMessage()->toArray(),
            'Object' => $this->getObject()->toArray(),
            'ConnectorGraphicsInfos' => $this->getConnectorGraphicsInfos()->toArray()
        );
        return $array;
    }

}

?>
