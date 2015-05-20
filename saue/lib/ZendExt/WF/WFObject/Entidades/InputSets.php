<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImputSets
 *
 * @author allenk
 */
class ZendExt_WF_WFObject_Entidades_InputSets extends ZendExt_WF_WFObject_Base_Collections {

    //put your code here
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'InputSets';
    }

    public function clonar() {
        
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_InputSet($this);
    }
    
    
    public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('InputSets' => $result);
        return $array;
    }

}

?>
