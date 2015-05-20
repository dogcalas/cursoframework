<?php

class ZendExt_WF_WFObject_Entidades_RedefinableHeader extends ZendExt_WF_WFObject_Base_Complex {

    private $PublicationStatus;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'RedefinableHeader';
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Author($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Version($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_CodePage($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_CountryKey($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Responsibles($this));

        $publicationStatusChoices = array('UNDER_REVISION', 'RELEASED', 'UNDER_TEST');
        $this->PublicationStatus = new ZendExt_WF_WFObject_Base_SimpleChoice('PublicationStatus', $publicationStatusChoices, NULL);
    }

    /*     * ********************PublicationStatus******************************* */

    public function getPublicationStatus() {
        return $this->PublicationStatus->getSelectedItem();
    }

    public function setPublicationStatus($publicationStatus) {
        $this->PublicationStatus->selectItem($publicationStatus);
    }

    /*     * ********************Author******************************* */

    public function getAuthor() {
        return $this->get('Author');
    }

    public function setAuthor($lInfo) {
        $this->layoutInfo = $lInfo;
    }

    /*     * ********************Version******************************* */

    public function getVersion() {
        return $this->get('Version');
    }

    public function setVersion($lInfo) {
        $this->layoutInfo = $lInfo;
    }

    /*     * ********************CodePage******************************* */

    public function getCodePage() {
        return $this->get('CodePage');
    }

    public function setCodePage($codePage) {
        $this->layoutInfo = $codePage;
    }

    /*     * ********************CountryKey******************************* */

    public function getCountryKey() {
        return $this->get('CountryKey');
    }

    public function setCountryKey($lInfo) {
        $this->layoutInfo = $lInfo;
    }

    /*     * ********************Responsible******************************* */

    public function getResponsibles() {
        return $this->get('Responsibles');
    }

    public function setResponsible($lInfo) {
        $this->layoutInfo = $lInfo;
    }

    public function toArray() {
        $array = array(
            'PublicationStatus' => $this->getPublicationStatus(),
            'Author' => $this->getAuthor()->toArray(),
            'Version' => $this->getVersion()->toArray(),
            'CodePage' => $this->getCodePage()->toArray(),
            'CountryKey' => $this->getCountryKey()->toArray(),
            'Responsibles' => $this->getResponsibles()->toArray()
        );
        return $array;
    }

}

?>
