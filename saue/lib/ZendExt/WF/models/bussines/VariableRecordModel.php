<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
 class VariableRecordModel extends ZendExt_Model 
 { 
    public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
    public function Guardar($VariableRecord) 
    { 
            $VariableRecord->save();
    } 
 
    public function Eliminar($VariableRecord) 
    { 
            $VariableRecord->delete();
    } 
    public function Buscar_Varible_Record_Id($record_id)////Devuelve un arreglo
    {
		$record = VariableRecord::Buscar($record_id);
		return $record;
	}
	public function Buscar_Varible_Record($record_id)////Devuelve un objeto doctrine
    {
		$record = VariableRecord::Buscar_Variable_Record($record_id);
		return $record;
	}
	public function Buscar_Varible_Record_Node($node_id, $execution_id)
    {
		$record = VariableRecord::Buscar_Variable_Record_Node($node_id, $execution_id);
		return $record;
	}
	
	
	
	
 }

