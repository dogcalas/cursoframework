<?php
/**
 * ZendExt_Listener_Multientity 
 *
 * @author Omar Antonio Díaz Peña
 * @version 2.0
 * @package ZendExt
 * @subpackage Listener
 * @copyright Sauxe
 */
class ZendExt_Listener_Multientity implements Doctrine_Record_Listener_Interface {
	/**
	 * preSave
	 *
	 * @param Doctrine_Event $event 
	 */
	public function preSave(Doctrine_Event $event){
    	//Se obtiene el objeto que provocó el evento: una instancia de Doctrine_Record
    	$invoker = $event->getInvoker ();
    	
    	if ($invoker->contains ('idestructuracomun') &&  //Si el registro posee un campo "idestructuracomun" y
    		$invoker->get ('idestructuracomun') == null) // si el campo carece de valorr
		{
				//Se le asigna el valor de la entidad en la cual se encuentra el usuario
				$invoker->set ('idestructuracomun'. 
								ZendExt_GlobalConcept::getInstance ()
                                                     ->Estructura
													 ->idestructura
                              );
    	}
    }

	public function preSerialize(Doctrine_Event $event){}

    public function postSerialize(Doctrine_Event $event){}

    public function preUnserialize(Doctrine_Event $event){}

    public function postUnserialize(Doctrine_Event $event){}    

    public function postSave(Doctrine_Event $event){}

    public function preDelete(Doctrine_Event $event){}

    public function postDelete(Doctrine_Event $event){}

    public function preUpdate(Doctrine_Event $event){}

    public function postUpdate(Doctrine_Event $event){}

    public function preInsert(Doctrine_Event $event){}

    public function postInsert(Doctrine_Event $event){}
    
    public function preHydrate(Doctrine_Event $event){}
    
    public function postHydrate(Doctrine_Event $event){}
}