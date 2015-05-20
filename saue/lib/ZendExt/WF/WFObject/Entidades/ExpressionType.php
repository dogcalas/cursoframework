<?php

class ZendExt_WF_WFObject_Entidades_ExpressionType extends ZendExt_WF_WFObject_Base_Complex {

    private $ScriptType;
    private $ScriptVersion;
    private $ScriptGrammar;

    public function __construct($parent, $tagName = 'ExpressionType') {
        parent::__construct($parent);
        $this->tagName = $tagName;
    }

    public function getScriptType() {
        return $this->ScriptType;
    }

    public function setScriptType($ScriptType) {
        $this->ScriptType = $ScriptType;
    }

    public function getScriptVersion() {
        return $this->ScriptVersion;
    }

    public function setScriptVersion($ScriptVersion) {
        $this->ScriptVersion = $ScriptVersion;
    }

    public function getScriptGrammar() {
        return $this->ScriptGrammar;
    }

    public function setScriptGrammar($ScriptGrammar) {
        $this->ScriptGrammar = $ScriptGrammar;
    }

    public function clonar() {
        $scriptType = $this->getScriptType();
        $scriptVersion = $this->getScriptVersion();
        $scriptGrammar = $this->getScriptGrammar();
        
        $expressionType = new ZendExt_WF_WFObject_Entidades_ExpressionType($this->parent, $this->tagName);
        
        $expressionType->setScriptGrammar($scriptGrammar);
        $expressionType->setScriptType($scriptType);
        $expressionType->setScriptVersion($scriptVersion);
        
        return $expressionType;
        
    }

    public function fillStructure() {
        /*
         * <xsd:choice minOccurs="0" maxOccurs="unbounded">
         *	<xsd:any processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         * </xsd:choice>
         */
        return;
    }
    
    public function toArray() {
        $array = array(
            'ScriptType' => $this->getScriptType(),
            'ScriptVersion' => $this->getScriptVersion(),
            'ScriptGrammar' => $this->getScriptGrammar(),
            
        );
        return $array;
    }

}

?>
