<?php
/**
 * ZendExt_Listener_TraceData 
 *
 * @author Omar Antonio Díaz Peña
 * @version 2.0
 * @package ZendExt
 * @subpackage Listener
 * @copyright Sauxe
 */
class ZendExt_Listener_TraceData implements Doctrine_Record_Listener_Interface {
	
	public function postDelete(Doctrine_Event $event){
        $trace = ZendExt_Listener_Trace_Factory::factory ($event->getInvoker ());
        $trace->setOperation (3);
        $this->saveTrace ($trace);
    }

    public function postUpdate(Doctrine_Event $event){
        $trace = ZendExt_Listener_Trace_Factory::factory ($event->getInvoker ());
        $trace->setOperation (2);
        $this->saveTrace ($trace);
    } 

    public function postInsert(Doctrine_Event $event){
        $trace = ZendExt_Listener_Trace_Factory::factory ($event->getInvoker ());
        $trace->setOperation (1);
        $this->saveTrace ($trace);
    }

    public function saveTrace ($pTrace)
    {
        ZendExt_Trace::getInstance ()
                     ->handle ($pTrace);
    }

	public function preSave(Doctrine_Event $event){}

	public function preSerialize(Doctrine_Event $event){}

    public function postSerialize(Doctrine_Event $event){}

    public function preUnserialize(Doctrine_Event $event){}

    public function postUnserialize(Doctrine_Event $event){}    

    public function postSave(Doctrine_Event $event){}

    public function preDelete(Doctrine_Event $event){}   

    public function preUpdate(Doctrine_Event $event){}
    
    public function preInsert(Doctrine_Event $event){}    
    
    public function preHydrate(Doctrine_Event $event){}
    
    public function postHydrate(Doctrine_Event $event){}
}
