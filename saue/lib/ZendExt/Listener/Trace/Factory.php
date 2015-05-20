<?php
/**
 * ZendExt_Listener_Trace_Factory
 *
 * @author Omar Antonio Díaz Peña
 * @version 2.0
 * @package ZendExt
 * @subpackage Listener
 * @copyright Sauxe
 */
class ZendExt_Listener_Trace_Factory {
	public function getTable ($pRecord)
	{
		return $pRecord->getTable ()->tableName();
	}

	public function getSchema ($pRecord)
	{
		$sql = sprintf(
			"SELECT table_schema 
			 FROM information_schema.tables 
			 WHERE table_name = '%s'", 
		    $this->getTable ($pRecord)
	    );
	  	
	    $result = ZendExt_Aspect_TransactionManager::getInstance()
	    										   ->openConections()
	    										   ->execute ($sql)
	    										   ->fetchAll ();
        
        $result = $conn->execute ($sql)->fetchAll (); 
        return $result[0][0];  
	}

	public function getId ($pRecord)
	{
		$column = $pRecord->getTable ()->getIdentifier ();	
		return $pRecord->get ($column);
	}

	public function getAction ()
	{
		$request = Zend_Controller_Front::getInstance()
										->getRequest ();

		if ($request)
			return $request->getActionName ();
		else
			return 'index';
	}

	public function getTraceCategory ()
	{
		return Zend_Registry::getInstance  ()
							->config
							->module_reference;
	}

	public function getUser ()
	{
		return Zend_Registry::getInstance  ()
							->session
							->usuario;	
	}

	public function getCommonStructure ()
	{
		return ZendExt_GlobalConcept::getInstance ()
									->Estructura
									->idestructura;
	}

	public function getIdProcess () 
	{
		return ZendExt_Process::getInstance ()
							  ->getId ();
	}

	public static function factory($pRecord)
	{
		$result = new ZendExt_Trace_Container_Data ($this->getSchema ($pRecord),
													$this->getTable (),
													$this->getId (),
													null,													
													$this->getAction (),
													$this->getTraceCategory (), 
													$this->getUser (),
													$this->getCommonStructure (),
													$this->getIdProcess ());
		return $result;
	}
}