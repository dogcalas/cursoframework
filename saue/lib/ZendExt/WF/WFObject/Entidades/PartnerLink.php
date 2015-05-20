<?php

class ZendExt_WF_WFObject_Entidades_PartnerLink extends ZendExt_WF_WFObject_Base_Complex {

    private $PartnerLinkTypeId;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'PartnerLink';
    }

    /*
     * Getters
     */
    public function getPartnerLinkTypeId() {
        return $this->PartnerLinkTypeId;
    }

    public function getMyRole() {
        return $this->get('MyRole');
    }

    public function getPartnerRole() {
        return $this->get('PartnerRole');
    }

    /*
     * Setters
     */
    public function setPartnerLinkTypeId($_partnerLinkTypeId) {
        $this->PartnerLinkTypeId = $_partnerLinkTypeId;
    }

    /*
     * Abstractions
     */
    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_MyRole($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_PartnerRole($this));
        return;
    }

    /*
      public function getRoleName(){
      return $this->get('RoleName');
      }
      public function getServiceName(){
      return $this->get('ServiceName');
      }
      public function getPortName(){
      return $this->get('PortName');
     *  public function getPartnerLinkTypeId(){
      return $this->get('PartnerLinkTypeId');
      } */

    public function toArray() {
        $array = array(
            'Name' => $this->getName(),
            'Id' => $this->getId(),
            /* 'PartnerLinkTypeId'=>  $this->getPartnerLinkTypeId(), */
            'MyRole' => $this->getMyRole()->toArray(),
            'PartnerRole' => $this->getPartnerRole()->toArray(),
                /* 'RoleName'=>  $this->getRoleName(),
                  'ServiceName'=>  $this->getServiceName(),
                  'PortName'=>  $this->getPortName() */
        );
        return $array;
    }

}

?>
