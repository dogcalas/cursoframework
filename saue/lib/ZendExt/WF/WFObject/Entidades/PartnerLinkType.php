<?php

class ZendExt_WF_WFObject_Entidades_PartnerLinkType extends ZendExt_WF_WFObject_Base_Complex {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'PartnerLinkType';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Role($this));
        return;
    }

    public function getRole() {
        return $this->get('Role');
    }

    /*  public function getportType() {
      return $this->get('portType');
      } */

    public function toArray() {
        $array = array(
            /* 'portType' => $this->getportType(), */
            'Name' => $this->getName(),
            'Id' => $this->getId(),
            'Role' => $this->getRole()->toArray()
        );
        return $array;
    }

}

?>
