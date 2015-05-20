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
class SegCertificado extends BaseSegCertificado
{

	public function setUp()
  	{
    	parent::setUp();    
    	$this->hasOne('SegUsuario',array('local'=>'idusuario','foreign'=>'idusuario'));   
  	}
  	
  	public function existecertificado($idusuario)
  	{
  	$query = Doctrine_Query::create();
	$certificado = $query->select('c.idcertificado, c.idusuario')->from('SegCertificado c')->where("c.idusuario = ?",$idusuario)->execute()->toArray(true);     			      
    return $certificado;
  	}
 		
	public function verificarcertificado($certificado)
	{
			$query = Doctrine_Query::create();
            $certificado = $query->select('idusuario,idcertificado')->from('SegCertificado')->where("valor = ?", $certificado)->execute();

        return $certificado;
	}
    
    static public function existcertificado($certificado) 
    {
            $query = Doctrine_Query::create();
            $certificado = $query->from('SegCertificado')->where("valor = ?", $certificado)->count();
            return $certificado;
    }
    public static function obtenerUsuariosConectados($limit,$start){
        $query = Doctrine_Query::create();
        $datos = $query->select('u.idusuario,c.idcertificado, u.nombreusuario,  c.fecha , c.hora , c.idsession, c.rol,c.entidad')
                        ->from('SegCertificado c')
                        ->innerjoin('c.SegUsuario u')
                        ->limit($limit)->offset($start)  
                        ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                        ->execute();
        return $datos;        
    }
        public static function obtenerUserConectados(){
        $query = Doctrine_Query::create();
        $datos = $query->select('c.idusuario')
                        ->from('SegCertificado c')                                                
                        ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                        ->execute();
        return $datos;        
    }
    static function obtenerCantidadUsuariosConectados()	{
	            $query = Doctrine_Query::create();
	            $cantFndes = $query->select('count(a.idcertificado) as cant')
	            			->from('SegCertificado a')		   
	            			->execute();
	            return $cantFndes[0]->cant;
    }
    static function cerrarSesionAbierta($idcertificado){            
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->delete()->from('SegCertificado m')->where("m.idcertificado = ?", $idcertificado)->execute();
	    return true;
    }

}
