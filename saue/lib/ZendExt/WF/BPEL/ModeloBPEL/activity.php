<?php

class ZendExt_WF_BPEL_ModeloBPEL_activity extends ZendExt_WF_BPEL_Base_ComplexChoice {

    public function __construct($parent) {
        parent::__construct($parent, 'activity');
    }

    public function initialize() {
        $activityChoices = array("assign", "compensate", "compensateScope", "empty", "exit",
            "extensionActivity", "flow", "forEach", "if", "invoke", "pick", "receive",
            "repeatUntil", "reply", "rethrow", "scope", "sequence", "throw", "validate",
            "wait", "while");
        
        foreach ($activityChoices as $value) {
            $this->addChoice($value);
        }
    }

    public function addChoice($choice) {
        $this->add($choice);
    }
    
    public function create() {
        $selected = $this->getSelectedItem();
        $classPreffix = 'ZendExt_WF_BPEL_ModeloBPEL_';
        
        $className = $classPreffix.$selected;
        
        $classInstance = new $className($this);
        $this->choices[$selected] = $classInstance;
    }

}

?>