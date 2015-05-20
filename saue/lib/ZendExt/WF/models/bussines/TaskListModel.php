<?php 
/* 
* Esta clase ha sido generada por Doctrine Generator 
*/ 
class TaskListModel extends ZendExt_Model 
 { 
   public function init() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Guardar($TaskList) 
    { 
            $TaskList->save();
    } 
 
   public function Eliminar($TaskList) 
    { 
            $TaskList->delete();
    } 
    
    public function Save($arreglo)
    {

		$task= new TaskList();
		$task->rol_id= $arreglo['rol'];
		$task->workflow_id= (int)$arreglo['idwork'];
		$task->action_name=$arreglo['idaction'];
		
		$task->node_id=$arreglo['nodo'];
		//print_r($task);die;
		$task->save();		
	}
	
	public function Buscar($arreglo)
	{	
		$respuesta=-1;
		$action= $arreglo['idaction'];
		$obj=SemiautomaticTask::Existencia($action);
		
		if(empty($obj))
		{
			
			//print_r('no esta asociada  ');die;
			$respuesta=0;
		}
		else
		{	
			
			
			$list= new TaskList();
			
		/*Para ver si la actividad esta en espera*/	
			try
			{	print_r('esta asosiada  ');
				$actividad=$list->Buscar($action,$arreglo['rol_id']);
				//print_r($actividad);die;
				$execution_id=ZendExt_WF_WFExecute_Execute::Select_execution($actividad);
				//print_r($execution_id);die;
				
				
			}
		
			catch(Doctrine_Exception $e)
			{
				echo "<pre> Doctrine error: ";print_r($e->getMessage());die;
			}
			
			/*se captura la sesion, para inicializar una variable, que funciona como bandera de 
			 * asociacion de las actividades con los flujos de trabajo, esta variable contendra
			 * informacion sobre el nodo al cual pertenece y de la variable de salida asociada
			 * a dicho nodo*/
			
			if(!empty($actividad))
				{
					print_r($actividad);die;
				print_r('SI le toca  ');
				$node= SemiautomaticTask::BuscarPorId($actividad[0]['node_id']);
				//print_r($node);die;
				$session = Zend_Registry::getInstance()->session;   //obtener sesion 
				$wf_data['node']=$node;
				$varRecord= new ZendExt_WF_WFExecute_Record();
				//print_r($execution_id);die;
				if($execution_id==0)
				{
					$variable= $varRecord->search_by_node($actividad[0]['node_id'],$actividad[0]['execution_id']);
				}
				else if ($execution_id==-1)
				{
					throw new ZendExt_Exception('E010');
					
				}
				else
				{
					$variable= $varRecord->search_by_node($actividad[0]['node_id'],$execution_id);
					//print_r($variable);die;
				}
				//print_r($variable);die;
				//$variable=$varRecord->search_record($actividad)
					if(!empty($variable))
					{	
						
						//print_r($_POST);
						$variable['state']=true;
						$variable['nodo']= $actividad[0]['node_id'];
						$wf_data['variable']=$variable;
						/*						
						$session = Zend_Registry::getInstance()->session;   //obtener sesion 
						$session->workflow_object=$variable;
*/
						//$session->workflow_object['var']= $variable[0];
						//$session->workflow_object= $variable[0]['field'];
						
						//print_r($session->workflow_object);
					}
					$session->workflow_object=$wf_data;
					//print_r($session->workflow_object);die;
					$respuesta=$actividad[0]['workflow_id'];					
				}			
		}
		return $respuesta;		
	}	
	/*funcion para actualizar las tareas que se realizan sobre el marco de trabajo, 
	 *son eliminadas de la lista en espera (falta el identificador de la ejecucion,
	 * para actualizar especificamente la actividad de esa ejecucion)*/
	public function ActualizarSemiauto($idnodo,$idexecution) 
	{
		$task= new TaskList();
		$obj= $task->BuscarObj($idnodo, $idexecution);
		
		$this->Eliminar($obj);
		//print_r($obj);die;
	}
	
	public function BuscarObjDoctrine($wfId,$node_id,$execution_id)
	{
		$task= new TaskList();
		$obj = $task->BuscarObjDoctrine($wfId,$node_id,$execution_id);
		return $obj;
	}
	
	public function BuscarArray($wfId,$node_id,$execution_id)
	{
		$task= new TaskList();
		$obj = $task->BuscarArray($wfId,$node_id,$execution_id);
		return $obj;
	}
    
    

 }

