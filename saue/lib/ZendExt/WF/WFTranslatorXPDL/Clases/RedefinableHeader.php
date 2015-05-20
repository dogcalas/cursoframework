<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_RedefinableHeader extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {

        parent::__construct($object);
    }

    public function desassembleClass() {
        $author = $this->object->getAuthor();
        $version = $this->object->getVersion();
        $codePage = $this->object->getCodePage();
        $countryKey = $this->object->getCountryKey();
        $responsibles = $this->object->getResponsibles();

        $myAuthor = new ZendExt_WF_WFTranslatorXPDL_Clases_Author($author);
        $myVersion = new ZendExt_WF_WFTranslatorXPDL_Clases_Version($version);
        $myCodePage = new ZendExt_WF_WFTranslatorXPDL_Clases_Codepage($codePage);
        $myCountryKey = new ZendExt_WF_WFTranslatorXPDL_Clases_Countrykey($countryKey);
        $myResponsibles = new ZendExt_WF_WFTranslatorXPDL_Clases_Responsibles($responsibles);

        $this->addAttribute($myAuthor);
        $this->addAttribute($myVersion);
        $this->addAttribute($myCodePage);
        $this->addAttribute($myCountryKey);
        $this->addAttribute($myResponsibles);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("RedefinableHeader");
        $thisObjectTag->setAttribute('PublicationStatus', $this->object->getPublicationStatus());

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }
        $objectTag->appendChild($thisObjectTag);
    }

}

?>