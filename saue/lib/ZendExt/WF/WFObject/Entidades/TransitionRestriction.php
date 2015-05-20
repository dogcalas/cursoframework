<?php

class ZendExt_WF_WFObject_Entidades_TransitionRestriction extends ZendExt_WF_WFObject_Base_Complex {

    private $Split;
    private $Join;
    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TransitionRestriction';
    }

    public function getJoin() {
        /*
         * Idem a getSplit
         */
        if($this->Join === NULL)
            $this->Join = new ZendExt_WF_WFObject_Entidades_Join($this);
        //return $this->get('Join');
        return $this->Join;
    }
    
    public function isNullJoin(){
        /*
         * Idem a isNullSplit
         */
        return $this->Join === NULL;
    }    

    public function isNullSplit(){
        /*
         * Para saber si es null el split sin tener que
         * invocar a la funcion getSplit(), ya que se 
         * crearia un nuevo split al invocar la funcion.
         * 
         * Seria mucho mejor si hubiera una forma de saber
         * quien invoca a esta funcion. A lo mejor existe, pero
         * yo no la conozco, aun.
         */
        return $this->Split === NULL;
    }

    public function getSplit() {
        /*
         * Sospecho que si se invoca esta funcion desde
         * alguna parte, es porque en alguna parte se definio
         * que esta transicion tiene un Split.
         * Hay que tener cuidado que quien invoca no sea el
         * traductor a XPDL.
         */
        
        //Esto no estaba
        if($this->Split === NULL)
            $this->Split = new ZendExt_WF_WFObject_Entidades_Split($this);
        //return $this->get('Split');
        return $this->Split;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        /*$this->add(new ZendExt_WF_WFObject_Entidades_Split($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_Join($this));*/
        return;
    }

    public function toArray() {
        /*
         * Ver el comentario de la funcion getSplit
         * para corregir esto en proxima ocasion...
         * ahora no me apura.
         */
        $array = array(
            'Split' => $this->getSplit()->toArray(),
            'Join' => $this->getJoin()->toArray()
        );
        return $array;
    }

}

?>