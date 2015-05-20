<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Janier Treto Portal
 * @author Hector David Peguero Alvarez

 * @version 1.0-0
 */
class SegRolAsignacion extends BaseSegRolAsignacion
{
	public function setUp(){
        parent::setUp();
        $this->hasOne ('SegRol', array ('local' => 'idrol', 'foreign' => 'idrol'));
		        }



       /**
        * Cuando este método se ejecute el rol será eliminado, aún si después
        * ocurriera un error en la BD
        */
	static public function eliminarRoles($idrol)
    {
        $dm = Doctrine_Manager::getInstance();
        $conn = $dm->getCurrentConnection();
        $query = Doctrine_Query::create();
        $query->delete()->from('SegRolAsignacion')->where("idrol =?",$idrol)->execute();
         
        return true;
    }

//static public function eliminarAsignacion($idrol)
//    {
//        $query = Doctrine_Query::create();
//        $query->delete()->from('SegRolAsignacion')->where("idrolasig =?",$idrol)->execute();
//        return true;
//    }
//
//    static public function obtenerRolesAsig($idrol){
//	 $query = Doctrine_Query::create();
//        $data=   $query->select('idrolasig')->from('SegRolAsignacion')->where("idrol =?",$idrol)->execute()->toArray();
//   
//        return $data;
//	}
//	
//	
//	static public function validarAsignacionroles($idrol,$idrolasig){
//	 $query = Doctrine_Query::create();
//        $data=   $query->select('idrolasig')->from('SegRolAsignacion')->where("idrol =?",$idrol)->addWhere("idrolasig = ?",$idrolasig)->execute()->toArray();
//   
//        return $data;
//	}
//	static public function obtenerFuncionalidades2($idrol) { 
//    $query = Doctrine_Query::create();         
//    $datos = $query->select('f.idfuncionalidad id, f.denominacion text, f.referencia,f.descripcion,f.icono, f.index')->from('DatFuncionalidad f')->innerjoin('f.DatSistemaSegRolDatFuncionalidad srf') ->innerjoin ('srf.SegRolAsignacion s')->where(" s.idrol = ? and  srf.idrol = s.idrolasig",array($idrol))->execute()->toArray();  
//	print_r($datos);die;
//   // return $datos;
//    }
//	
//	
//	
//         static public function obtenerPadresAsig($idrol){
//	 $query = Doctrine_Query::create();
//        $data = $query->select(idrol)->from('SegRolAsignacion')->where("idrolasig =?",$idrol)->execute()->toArray();
//   // print_r($data[0]['idrol']);die;
//        return $data;
//	}
//
//static public function desasignarRol($idrol,$idrolasig)
//    {
//        $query = Doctrine_Query::create();
//        $query->delete()->from('SegRolAsignacion')->where("idrol =?",$idrol)->addWhere("idrolasig =?",$idrolasig)->execute();
//        return true;
//    }


}
