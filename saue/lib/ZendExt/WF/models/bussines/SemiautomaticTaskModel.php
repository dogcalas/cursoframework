<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class SemiautomaticTaskModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($SemiautomaticTask) 
    { 
            $SemiautomaticTask->save();
    } 
 
   public function Eliminar($SemiautomaticTask) 
    { 
            $SemiautomaticTask->delete();
    } 
    public function BuscarPorId($id)
    {
		return SemiautomaticTask::BuscarPorId($id);
	}
	public function buscarToDelete($node_id)
	{
		$noderesult = SemiautomaticTask::buscarId($node_id);
		if (!empty($noderesult))
		{
			//echo ' .. Era semiautomatica... ';print_r($noderesult[0]['node_id']);die;
			$noderesult->active = false;
			//echo ' despues nodoSemi se desactive';
			$noderesult->save();
		}
	}
	
	public function Buscar_Obj($node_id)
	{
		return SemiautomaticTask::buscarId($node_id);
	}
 }

