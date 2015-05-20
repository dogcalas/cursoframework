    <?php
class ZendExt_WF_WFTranslatorXPDL_Clases_ValidTo extends   ZendExt_WF_WFTranslatorXPDL_Base_Base
{
    function __construct($object) {
        parent::__construct($object);
    }
    

     public function desassembleClass() {
         return;
    }
     
      public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("ValidTo");
        $thisObjectTag->appendChild($doc->createTextNode($this->object->getValidTo()));
        $objectTag->appendChild($thisObjectTag);
    }
}

?>
