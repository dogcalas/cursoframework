<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TriggerRule
 *
 * @author yriverog
 */
class ZendExt_WF_WFObject_Entidades_TriggerRule extends ZendExt_WF_WFObject_Base_Complex {

    private $RuleName;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TriggerRule';
    }

    public function setRuleName($_ruleName) {
        $this->RuleName = $_ruleName;
    }

    public function getRuleName() {
        return $this->RuleName;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */
        return;
    }

}

?>
