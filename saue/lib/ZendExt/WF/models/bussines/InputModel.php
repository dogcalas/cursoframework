<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class InputModel extends ZendExt_Model 
{ 
	public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
	public function Guardar($Input) 
    { 
            $Input->save();
    } 
 
	public function Eliminar($Input) 
    { 
            $Input->delete();
    }
    public function getVariableName($node_id)
    {
		$input = new Input();
		$result = $input->getVariableName($node_id);
		return $result[0]['name'];
	}
	public function getVariableCondition($node_id)
    {
		$input = new Input();
		$result = $input->getVariableCondition($node_id);
		return $result[0]['condition'];
	}
}

