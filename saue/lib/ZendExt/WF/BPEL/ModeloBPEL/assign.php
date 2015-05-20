<?php

class ZendExt_WF_BPEL_ModeloBPEL_assign extends ZendExt_WF_BPEL_ModeloBPEL_tActivity {

    private $validate;
    
    public function __construct($parent) {
        parent::__construct($parent, 'assign');
    }

    public function fillStructure() {
        //parent::fillStructure();

        //$this->add(new ZendExt_WF_BPEL_Base_unboundChoices($this, array('copy','extensionAssignOperation'), 'objects'));
    }

    public function clonar() {
        return;
    }
    
    public function getObjects(){
        return $this->get('objects');
    }
    
    public function createObject($objectType) {
        $objectsList = $this->getObjects();
        /*
         * Primero seleccionamos el tipo de objeto
         * que queremos crear...
         */
        $objectsList->select($objectType);
        /*
         * luego lo creamos...
         */
        $object = $objectsList->create();
        /*
         * y finalmente lo adicionamos
         */
        $objectsList->add($object);
    }
    
    public function initAttributes() {
        $this->validate = new ZendExt_WF_BPEL_Base_attribute($this, $attributeName = 'validate' ,$isRequired = false, $value = null, $type = 'boolean', false);
    }
}

?>
