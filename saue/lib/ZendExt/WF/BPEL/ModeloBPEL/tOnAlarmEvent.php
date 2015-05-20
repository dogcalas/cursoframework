<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tOnAlarmEvent
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_ModeloBPEL_tOnAlarmEvent extends ZendExt_WF_BPEL_ModeloBPEL_tExtensibleElements {

    //put your code here
    public function __construct($parent, $tagName = null) {
        parent::__construct($parent, $tagName);
    }

    public function fillStructure() {
        parent::fillStructure();

        /*
         * Missing:
          <xsd:choice>
          <xsd:sequence>
          <xsd:group ref="forOrUntilGroup" minOccurs="1"/>
          <xsd:element ref="repeatEvery" minOccurs="0"/>
          </xsd:sequence>
          <xsd:element ref="repeatEvery" minOccurs="1"/>
          </xsd:choice>
         */
        $this->add(new ZendExt_WF_BPEL_ModeloBPEL_scope($this));
    }

}

?>
