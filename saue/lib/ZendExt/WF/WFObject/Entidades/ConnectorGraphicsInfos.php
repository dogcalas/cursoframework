<?php

class ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfos extends ZendExt_WF_WFObject_Base_Collections {

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'ConnectorGraphicsInfos';
    }

    public function clonar() {
        
    }

    public function createObject() {
        return new ZendExt_WF_WFObject_Entidades_ConnectorGraphicsInfo($this);
    }

    public function getConnectorGraphicsInfo() {
        return $this->get('ConnectorGraphicsInfo');
    }
    
     public function toArray() {
        $result = array();
        for ($i = 0; $i < $this->count(); ++$i) {
            $auxArray = $this->get($i)->toArray();
            $result[] = $auxArray();
        }
        $array = array('ConnectorGraphicsInfos' => $result);
        return $array;
    }

}
?>

