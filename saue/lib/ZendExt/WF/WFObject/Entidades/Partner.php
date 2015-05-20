<?php

class ZendExt_WF_WFObject_Entidades_Partner extends ZendExt_WF_WFObject_Base_Complex {

    private $OperationName;
    private $PartnerLinkId;
    private $RoleType;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Partner';
    }

    /*
     * Setters
     */
    public function setOperationName($_operationName) {
        $this->OperationName = $_operationName;
    }

    public function setPartnerLinkId($_partnerLinkId) {
        $this->PartnerLinkId = $_partnerLinkId;
    }

    public function setRoleType($_roleType) {
        $this->RoleType->selectItem($_roleType);
    }

    /*
     * Getters
     */
    public function getOperationName() {
        return $this->OperationName;
    }

    public function getPartnerLinkId() {
        return $this->PartnerLinkId;
    }

    public function getRoleType() {
        return $this->RoleType->getSelectedItem();
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $roleTypeChoices = array('MyRole', 'PartnerRole');
        $this->RoleType = new ZendExt_WF_WFObject_Base_SimpleChoice('RoleType', $roleTypeChoices, NULL);
        return;
    }

}

?>
