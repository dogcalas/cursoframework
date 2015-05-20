<?php

class ZendExt_WF_WFObject_Entidades_Script extends ZendExt_WF_WFObject_Base_Complex {

    private $Type;
    private $Version;
    private $Grammar;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Script';
    }

    public function setType($_type) {
        $this->Type = $_type;
    }

    public function setVersion($_version) {
        $this->Version = $_version;
    }

    public function setGrammar($_grammar) {
        $this->Grammar = $_grammar;
    }

    public function getType() {
        return $this->Type;
    }

    public function getVersion() {
        return $this->Version;
    }

    public function getGrammar() {
        return $this->Grammar;
    }

    public function clonar() {
        return;
    }


    public function toArray() {
        $array = array(
            'Type' => $this->getType(),
            'Version' => $this->getVersion(),
            'Grammar' => $this->getGrammar()
        );
        return $array;
    }


    public function fillStructure() {
        return;
    }
}

?>
