<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SourceEvent
 *
 * @author dogy
 */
class ZendExt_Component_SourceEvent {

    private $Observers = array();
    private $class;
    private $id;

    public function getId() {
        return $this->id;
    }

    function __construct($id, $class = null) {
        $this->class = $class;
        $this->id = $id;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function NotifyChange($params = null) {
        foreach ($this->Observers as $obj) {
            require_once $obj->getImpls();
            $file = new Zend_Reflection_File($obj->getImpls());
            $observerObj = $file->getClass()->newInstance();
            if (isset($this->class)) {
                require_once $this->class;
                $fileEvent = new Zend_Reflection_File($this->class);
                $fileEventObj = $fileEvent->getClass()->newInstanceArgs($params);
                call_user_func_array(array($observerObj, "update"), array($fileEventObj));
            } else {
                call_user_func_array(array($observerObj, "update"));
            }
        }
    }

    public function AddObserver($Observer) {
        $this->Observers[] = $Observer;
    }

    public function RemoveObserver($Observer) {
        $key = array_search($Observer, $this->Observers, true);
        unset($this->Observers[$key]);
        $this->Observers = array_values($this->Observers);
    }

}

?>
