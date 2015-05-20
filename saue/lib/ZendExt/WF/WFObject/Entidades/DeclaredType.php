
<?php
class ZendExt_WF_WFObject_Entidades_DeclaredType extends ZendExt_WF_WFObject_Base_Complex
{
    public function __construct($parent) {
        //echo 'package';
        parent::__construct($parent);
        $this->tagName = 'DeclaredType';        
    }
    
    public function fillStructure()
    { 
        return;
    }

    public function clonar() {
        return;
    }
    
    
    public function toArray() {
        $array = array(
            'Id' => $this->getId(),
            'Name' => $this->getName()
        );
        return $array;
    }
   
}

?>

