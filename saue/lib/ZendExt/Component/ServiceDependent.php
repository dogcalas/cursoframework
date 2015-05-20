<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceDependent
 *
 * @author dalfonso
 */
class ZendExt_Component_ServiceDependent extends ZendExt_Component_Service {

    /**
     *
     * @var ZendExt_Component_Bundle
     */
    protected $bundleResolver;
    protected $optional;
    protected $use;

    public function __construct($id, $impl, $interface, $optional = false, $use = null) {
        parent::__construct($id, $impl, $interface);
        $this->optional = $optional;
        if ($use)
            $this->use = $use;
    }

    public function getUseComponent() {
        if ($this->use) {
            $arr = split("-", $this->use);
            return $arr[0];
        }
        return false;
    }

    public function getUseVersion() {
        if ($this->use) {
            $arr = split("-", $this->use);
            return $arr[1];
        }
        return false;
    }

    public function setBundleResolver($bundleResolver) {
        $this->bundleResolver = $bundleResolver;
    }

    public function setOptional($optional) {
        $this->optional = $optional;
    }

    public function isOptional() {
        return $this->optional;
    }

    public function getBundleResolver() {
        return $this->bundleResolver;
    }

}

?>
