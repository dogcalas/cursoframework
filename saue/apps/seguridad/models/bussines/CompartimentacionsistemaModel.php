<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
class CompartimentacionsistemaModel extends ZendExt_Model
	{
    public function CompartimentacionsistemaModel() {
          parent::ZendExt_Model();
        }
        
        public function cargarsistema($id){
                $sistemas = DatSistema::cargarsistema($id);
                return $sistemas; 
	}
        
        public function cargarFuncionalidadesCompartimentacion($idsistema, $iddominio){ 
        $funcionalidad = DatFuncionalidad::cargarFuncionalidadesCompartimentacion($idsistema, $iddominio);
        return $funcionalidad; 
        }
        
        public function cargarAccionesCompartimentacion($idfuncionalidad, $iddominio){ 
        $funcionalidad = DatAccion::cargarAccionesCompartimentacion($idfuncionalidad, $iddominio);
        return $funcionalidad;
        }
        
        public function obtenerSistemasByIddominio($iddominio){  
        $sistemasIns = DatSistemaCompartimentacion::obtenerSistemasByIddominio($iddominio);
        return $sistemasIns;
        }
        
        public function obtenerfuncionalidadesByIddominio($iddominio){ 
        $funcionalidadesIns = DatFuncionalidadCompartimentacion::obtenerfuncionalidadesByIddominio($iddominio);
        return $funcionalidadesIns;
        }
        
        public function cargarAccionByArrayIdFunc($arrayPadres){ 
        $arrayAccionesHijas = DatAccion::cargarAccionByArrayIdFunc($arrayPadres);
        return $arrayAccionesHijas;
        }
        
        public function obteneraccionesByIddominio($iddominio){ 
                $accionesIns = DatAccionCompartimentacion::obteneraccionesByIddominio($iddominio);
                return $accionesIns;
        }
        
	public function gestionarSistemasFuncAcc($arraySistemas, $arrayFuncionalidades, $arrayAcciones, $arraySistEliminar, $arrayFuncEliminar, $arrayAccEliminar, $iddominio){
			if(count($arraySistemas))
				$this->insertarCompartimentacionObj('DatSistemaCompartimentacion', 'idsistema', $iddominio, $arraySistemas);
			if($arrayFuncionalidades)
				$this->insertarCompartimentacionObj('DatFuncionalidadCompartimentacion', 'idfuncionalidad', $iddominio, $arrayFuncionalidades);
			if($arrayAcciones)
				$this->insertarCompartimentacionObj('DatAccionCompartimentacion', 'idaccion', $iddominio, $arrayAcciones);
			if(count($arraySistEliminar))
				DatSistemaCompartimentacion::eliminarSistemas($arraySistEliminar,$iddominio);
			if(count($arrayFuncEliminar))
				DatFuncionalidadCompartimentacion::eliminarFuncionalidades($arrayFuncEliminar,$iddominio);
			if(count($arrayAccEliminar)) {
				$rolesDominio = SegRolNomDominio::RolesdelDominio($iddominio);
				$rolesDominio = $this->arrayBidimencionalToUnidimencional($rolesDominio);
				DatAccionCompartimentacion::eliminarAcciones($arrayAccEliminar,$iddominio);
                                $acciones = DatSistemaSegRolDatFuncionalidadDatAccion::obtenerAccionesAutorizadas($arrayAccEliminar, $rolesDominio);
                                
                                $arrayacc = array();
                            foreach ($acciones as $value) {
                                 $arrayacc[] = $value['idaccion'];   

                            }
                           if(count($arrayacc) )
				DatSistemaSegRolDatFuncionalidadDatAccion::eliminarAccionesAutorizadas($arrayacc,$rolesDominio);
			}
		}
		
	private function insertarCompartimentacionObj($nameObj, $nameId, $iddominio, $arrayIdObj) {
        $Obj= array();
		foreach ($arrayIdObj as $key => $id){
			$Obj[$key] = new $nameObj();
		    $Obj[$key]->$nameId = $id;
		    $Obj[$key]->iddominio = $iddominio;
		    $Obj[$key]->save();
			}
        }
        
     private function arrayBidimencionalToUnidimencional($arrayRoles){
     	$array = array();
     	foreach($arrayRoles as $rol){
     		$array[] = $rol['idrol'];
     	}
     	return $array;
     } 
       
	}













?>