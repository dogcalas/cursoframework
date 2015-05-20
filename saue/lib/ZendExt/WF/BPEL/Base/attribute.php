<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of attribute
 *
 * @author lhia
 */
class ZendExt_WF_BPEL_Base_attribute extends ZendExt_WF_BPEL_Base_XMLElement {

    //put your code here
    private $is_Required;
    private $value;
    private $type;
    private $validTypes;
    private $default;

    public function __construct($parent, $attributeName = null, $isRequired = false, $value = null, $type = null, $default = null) {
        parent::__construct(null, $attributeName);

        $this->validTypes = array('string', 'float', 'int', 'bool');

        $this->is_Required = $isRequired;
        if ($type !== null) {
            $this->setType($type);
        }
        if ($value !== null) {
            $this->setValue($value);
        } elseif ($default !== null) {
            $this->setValue($default);
        }

        $parent->addAttribute($this);
    }

    private function checkIsValidType($type) {
        return array_search($type, $this->validTypes) !== false;
    }

    public function getName() {
        return $this->getTagName();
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        if ($this->evaluate($value) /* gettype($value) === $this->type */) {
            $this->value = $value;
        } else {            
            throw new Exception('invalid value');
        }
    }

    private function evaluate($value) {
        if ($this->type === 'bool') {
            
            return $value === 'true' || $value === 'false';
        } else {
            $funcSuffix = 'val';
            $func = $this->type . $funcSuffix;
            $evaluation = $func($value);
            if ($evaluation === 0) {
                return $value === '0';
            }
        }
    }

    public function setDefaultValue($value) {
        if (gettype($value) === $this->type) {
            $this->default = $value;
            $this->setValue($value);
        } else {
            throw new Exception('invalid value');
        }
    }

    public function isRequired() {
        return $this->is_Required;
    }

    public function setRequired($isRequired) {
        $this->is_Required = $isRequired;
    }

    public function setType($type) {
        if ($this->checkIsValidType($type)) {
            $this->type = $type;
        } else {
            print_r($this->getTagName());
            throw new Exception('invalid type');
        }
    }

    public function getType() {
        return $this->type;
    }

    public function clonar() {
        return;
    }

    public function isEmpty() {
        return empty($this->value);
    }

}

?>
