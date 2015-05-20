<?php

class ZendExt_WF_WFObject_Entidades_ResultError extends ZendExt_WF_WFObject_Base_Complex {

    private $ErrorCode;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ResultError';
    }

    public function getErrorCode() {
        $this->ErrorCode;
    }

    public function setErrorCode($ErrorCode) {
        $this->ErrorCode = $ErrorCode;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

    public function toArray() {
        $array = array(
            'ErrorCode' => $this->getErrorCode()
        );
        return $array;
    }

}

?>
