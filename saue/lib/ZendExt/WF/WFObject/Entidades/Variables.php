
<?php

class ZendExt_WF_WFObject_Entidades_Variables extends ZendExt_WF_WFObject_Base_Collections {

    private $variableCreate;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Variables';

        $optionsTipo = array('Entrada', 'Salida');
        $this->variableCreate = new ZendExt_WF_WFObject_Base_SimpleChoice('Tipo', $optionsTipo, $this);
    }

    public function clonar() {
        
    }

    public function hasDecision() {
        return TRUE;
    }

    public function decide($decision) {
        $this->variableCreate->selectItem($decision);
    }

    public function createObject() {
        $returnResult = NULL;
        if ($this->variableCreate->getSelectedItem() === 'Entrada') {
            $returnResult = new ZendExt_WF_WFObject_Entidades_VariableEntrada($this);
        } else {
            $returnResult = new ZendExt_WF_WFObject_Entidades_VariableSalida($this);
        }
        return $returnResult;
    }

    public function getInputVariables() {
        $result = array();
        for ($i = 0; $i < $this->count(); $i++) {
            $variable = $this->get($i);
            if ($variable->getDireccion() === 'Entrada') {
                $result[] = $variable;
            }
        }
        return $result;
    }

    public function getOutputVariables() {
        $result = array();
        for ($i = 0; $i < $this->count(); $i++) {
            $variable = $this->get($i);
            if ($variable->getDireccion() === 'Salida') {
                $result[] = $variable;
            }
        }
        return $result;        
    }

    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('Variables' => $result);
        return $array;
    }

}
?>

