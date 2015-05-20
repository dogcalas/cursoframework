<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UnionType
 *
 * @author yriverog
 */
class ZendExt_WF_WFTranslatorXPDL_Clases_UnionType extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    public function __construct($object) {
        parent::__construct($object);
    }

    public function desassembleClass() {
        $member = $this->object->getMember();
        $myMember = new ZendExt_WF_WFTranslatorXPDL_Clases_Member($member);
        $this->addAttribute($myMember);
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("UnionType");

        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);        
    }

}

?>
