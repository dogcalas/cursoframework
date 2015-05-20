<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author yriverog
 */
class ezcWorkflowUserTask extends ezcWorkflowNodeInput {

    /* public $variableName = null;
      public $variableCondition = null; */

    public function __construct($configuration = '') {
        parent::__construct($configuration);
    }
    
    
    public function getIdRol() {
        $config = $this->getConfiguration();
        if (array_key_exists('idrol', $config)) {
            return $config['idrol'];
        } else {
            throw new Exception('IdRol no es un atributo valido de ' . get_class($this));
        }
    }    

}

?>
