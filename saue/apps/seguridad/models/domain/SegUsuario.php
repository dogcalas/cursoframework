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
class SegUsuario extends BaseSegUsuario
{

    public function setUp()
    {
    parent::setUp();    
    $this->hasMany('DatEntidadSegUsuarioSegRol',array('local'=>'idusuario','foreign'=>'idusuario'));    
    $this->hasMany('SegClaveacceso',array('local'=>'idusuario','foreign'=>'idusuario'));
    $this->hasMany('SegCertificado',array('local'=>'idusuario','foreign'=>'idusuario'));
    $this->hasOne('NomFila',array('local'=>'idusuario','foreign'=>'idusuario'));
    $this->hasOne('NomTema',array('local'=>'idtema','foreign'=>'idtema'));
    $this->hasOne('NomIdioma',array('local'=>'ididioma','foreign'=>'ididioma'));
    $this->hasOne('NomDesktop',array('local'=>'iddesktop','foreign'=>'iddesktop'));  
    $this->hasOne('SegDominio',array('local'=>'iddominio','foreign'=>'iddominio')); 
	$this->hasMany('SegUsuarioNomDominio', array ('local'=>'idusuario','foreign'=>'idusuario'));
	$this->hasMany('SegCompartimentacionusuario', array ('local'=>'idusuario','foreign'=>'idusuario'));
	$this->hasMany('DatServidor', array ('local'=>'idusuario','foreign'=>'idservidor','refClass' => 'SegUsuarioDatSerautenticacion'));
    }
  
  	static function cargarusuario($filtroDominio, $nombreusuario, $limit, $start) {
  		$where = "userD.iddominio = $filtroDominio";
  		if($nombreusuario)
  			$where.=" and u.nombreusuario like '%$nombreusuario%'";
	    $query = Doctrine_Query::create();
	    $datos = $query ->select('u.idusuario,u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
	    				->from('SegUsuario u,u.NomDesktop e, u.NomTema t, u.NomIdioma i')
	    				->innerjoin('u.SegUsuarioNomDominio userD')
	    				->where($where)
	    				->limit($limit)
	    				->offset($start)
	    				->execute();
	    return $datos;
    }
	
	static function cargarGridUsuario($filtroUsuarios,$limit, $start) {
	    $query = Doctrine_Query::create();
	    $datos = $query ->select('u.idusuario,u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
	    				->from('SegUsuario u,u.NomDesktop e, u.NomTema t, u.NomIdioma i')
	    				->whereIn("u.idusuario",$filtroUsuarios)
	    				->limit($limit)
	    				->offset($start)
	    				->execute();
                                   
	    return $datos;
    }

    /*static function obtenercantusuarios()
    {
    $query = Doctrine_Query::create();
    $cantFndes = $query->select('count(u.idusuario) as cant')->from('SegUsuario u')->execute();
    return $cantFndes[0]->cant;
    }*/
    
	static function cantidadFilas($arrayresult) {
	    $query = Doctrine_Query::create();
	    $datos = $query ->select('u.idusuario')
	    				->from('SegUsuario u')
	    				->whereIn("u.idusuario",$arrayresult)
	    				->count();
	    return $datos;
    }
    
	static function cargarUsuariosconpermisosaDominios($filtroDominio) {
		
	    $query = Doctrine_Query::create();
	    $datos = $query ->select('u.idusuario')
	    				->from('SegUsuario u')
	    				->whereIn("u.iddominio",$filtroDominio)
	    				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
	    				->execute();
	    return $datos;
    }
    
    static function obtenercantusuarios($filtroDominio, $nombreusuario) {
    	$where = "userD.iddominio = $filtroDominio";
  		if($nombreusuario)
  			$where.=" and u.nombreusuario like '%$nombreusuario%'";
	    $query = Doctrine_Query::create();
	    $cantFndes = $query	->select('count(u.idusuario) as cant')
	    					->from('SegUsuario u')
	    					->innerjoin('u.SegUsuarioNomDominio userD')
	    					->where($where)
	    					->execute();
	    return $cantFndes[0]->cant;
    }
    
	static function obtenercantGridUsuario($filtroDominio) {
	    $query = Doctrine_Query::create();
	    $cantFndes = $query	->select('count(u.idusuario) as cant')
	    					->from('SegUsuario u')
	    					->whereIn("u.iddominio",$filtroDominio)
	    					->execute();
	    return $cantFndes[0]->cant;
    }
    
 	static function cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start) {
		
		
		$query = Doctrine_Query::create();
		$cero=0;
		$uno=1;
		if($nombreusuario)
		$where = "u.nombreusuario like '%$nombreusuario%'";
		if ($dominiobuscar && $nombreusuario)
			$where .= " and u.iddominio = $dominiobuscar";
		elseif($dominiobuscar && !$nombreusuario)
            $where .= "u.iddominio = $dominiobuscar";		
		if($activar == 'No' && ($nombreusuario || $dominiobuscar))
	    	$where .= " and u.activo = $cero";
		elseif($activar == 'No' && !$nombreusuario && !$dominiobuscar)
			 $where .= "u.activo = $cero";
	    if($activar == 'Si' && ($nombreusuario || $dominiobuscar))
	    	$where .= " and u.activo = $uno";
		elseif($activar == 'Si' && !$nombreusuario && !$dominiobuscar)	
		    $where .= "u.activo = $uno";
        if($where)
		{
		$datos = $query ->select('u.idusuario, u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
						->from('SegUsuario u, u.NomDesktop e, u.NomTema t, u.NomIdioma i')
						->where($where)
    					->whereIn("u.idusuario",$arrayresult)
    					->limit($limit)
    					->offset($start)
    					->execute();
		}				
		else
        {		
	    $datos = $query ->select('u.idusuario, u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
						->from('SegUsuario u, u.NomDesktop e, u.NomTema t, u.NomIdioma i')
    					->whereIn("u.idusuario",$arrayresult)
    					->limit($limit)
    					->offset($start)
    					->execute();
		}				
		return $datos;
    }
    
	static function cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar) {
		$where = "u.nombreusuario like '%$nombreusuario%'";
		if ($dominiobuscar)
			$where .= " and u.iddominio = $dominiobuscar";
		if($activar == 'Desactivar')
	    	$where .= " and u.activo = true";
	    if($activar == 'Activar')
	    	$where .= " and u.activo = false";
   		$query = Doctrine_Query::create();
   		$cantFndes = $query ->select('count(u.idusuario) as cant')
   							->from('SegUsuario u')
   							->where("$where")
   							->whereIn("u.idusuario",$arrayresult)
   							->execute();
		return $cantFndes[0]->cant;
    	}
    
	

	/*static function obtenercantusuariosBuscados($nombreusuario, $filtroDominio, $dominiobuscar, $activar) {
		$where = "u.nombreusuario like '%$nombreusuario%'";
		if ($dominiobuscar)
			$where .= " and u.iddominio = $dominiobuscar";
		if($activar == 'Desactivar')
	    	$where .= " and u.activo = true";
	    if($activar == 'Activar')
	    	$where .= " and u.activo = false";
   		$query = Doctrine_Query::create();
   		$cantFndes = $query ->select('count(u.idusuario) as cant')
   							->from('SegUsuario u')
   							->whereIn("u.iddominio",$filtroDominio)
   							->where("$where")
   							->execute();
		return $cantFndes[0]->cant;
    }*/

    static public function cantusuariodadodominio($iddominio){
        $query = Doctrine_Query::create();
        $cantFndes = $query->select('COUNT(u.iddominio) iddominio')->from('SegUsuario u')->where("u.iddominio = ?",$iddominio )->execute();
        return $cantFndes[0]->iddominio != 0; 
        }
        
    public function usuariodadodominio($iddominio){
        $query = Doctrine_Query::create();
        $result = $query->select('u.idusuario')->from('SegUsuario u')->where("u.iddominio = ?",$iddominio )->execute()->toArray();
         return $result;
      }  
        
		
    static public function cargarservidor($idusuario){
    $query=Doctrine_Query::create();
    $datos=$query->select('u.idusuario,s.idservidor,a.idservidor,o.denominacion')->from('SegUsuario u')->innerJoin('u.SegUsuarioDatSistemaDatSerautenticacion s')->innerJoin('s.DatSerautenticacion a')->innerJoin( 'a.DatServidor o')->where("u.idusuario =?",$idusuario)->execute();
    return $datos;
    }	
			
    static public function obtenercanservldap($idusuario){
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->from('SegUsuario u')->innerJoin('u.SegUsuarioDatSistemaDatSerautenticacion s')->innerJoin('s.DatSerautenticacion a')->innerJoin( 'a.DatServidor o')->where("u.idusuario =?",$idusuario)->count();
	    return $cantFndes;
	    } 
		
    static function eliminarusuario($idusuario){
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->delete()->from('SegUsuario m')->where("m.idusuario = ?", array($idusuario))->execute();      			      
	    return true;
	    } 
	
    public function comprobarusuario($user, $pass){
	    $query = Doctrine_Query::create();
	    $usuario = $query->select('u.idusuario, u.nombreusuario')->from('SegUsuario u')->where("u.nombreusuario = ? AND u.contrasenna = ?", array($user, $pass))->execute()->toArray(true);     			      
	    return $usuario;            
	    }
	
    static public function cargarperfilusuario($idusuario){
	    $query = Doctrine_Query::create();
	    $usuario = $query->select('u.idusuario, u.nombreusuario, u.identidad, u.idarea, u.idcargo,u.idcargo,u.iddominio, d.abreviatura desktop, d.iddesktop, d.denominacion, t.abreviatura tema, t.idtema, t.denominacion, i.ididioma, i.abreviatura idioma, i.denominacion,f.idfila,v.valor,v.idvalor, u.activo')->from('SegUsuario u')->leftJoin("u.NomTema t")->leftJoin("u.NomIdioma i")->leftJoin("u.NomDesktop d")->leftJoin("u.NomFila f")->leftJoin("f.NomValor v")->where("u.idusuario = ?", $idusuario)->execute()->toArray(true);     			      
	    return $usuario;	            
	    }	

    static  public  function contarusuario($user){
	    $query = Doctrine_Query::create();
	    $usuario = $query->select('count(idusuario) as cant')->from('SegUsuario')->where("nombreusuario = ?",$user)->execute();    			      
	    return $usuario[0]->cant;
	}

	static  public  function comprobardesktop($iddesktop)
    {
    $query = Doctrine_Query::create();
    $usuario = $query->from('SegUsuario')->where("iddesktop = ?",$iddesktop)->count();    			      
    return $usuario;
    }
    	static  public  function comprobartema($idtema)
    {
    $query = Doctrine_Query::create();
    $usuario = $query->from('SegUsuario')->where("idtema = ?",$idtema)->count();    
    return $usuario;
    }
	
    public function nombusuario($id){
	    $query = Doctrine_Query::create();
	    $usuario = $query->select('nombreusuario')->from('SegUsuario')->where("idusuario = ?",$id)->execute();    			      
	    return $usuario;
	    }
	
    public function nombusuariocont($user){
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->from('SegUsuario')->where("nombreusuario = ?",$user)->count();	            
	    return $cantFndes;
	    }
	
    static public function cargardominiodeusuario($idusuario){
	    $query = Doctrine_Query::create();
	    $dominio = $query->select('u.iddominio')->from('SegUsuario u')->where("u.idusuario = ?", $idusuario)->execute();
	    return $dominio;	            
	    }
    
    static public function cargarentidadesareascargos($idusuario) {
     $query = Doctrine_Query::create();
     $datos = $query->select('u.identidad, u.idarea, u.idcargo')->from('SegUsuario u')->where("u.idusuario = ?", $idusuario)->execute()->toArray();                       
     return $datos;                
     }
    
   	static public function cargarservidoraut($idusuario){
        $q = Doctrine_Query::create();  
        $datos = $q->select('s.idservidor , s.denominacion ')->from('DatServidor s, s.DatSerautenticacion a, a.SegUsuario u')->where("u.idusuario = ? ",$idusuario)->execute();           
        return  $datos;
      }
      
    static public function comprovarestado($idusuario){
        $q = Doctrine_Query::create();  
        $datos = $q->select('u.estado ')->from('SegUsuario u')->where("u.idusuario = ? ",$idusuario)->execute();           
        return  $datos;
      }
      
    static public function verificarpass($usuario){
	    $q = Doctrine_Query::create();
        $datos = $q->select('idusuario, contrasenna, ip, activo')->from('SegUsuario')->where("nombreusuario = ? ",$usuario)->execute();
        return  $datos;
      }
     
    public function cargarusuarios($identidad,$idrol){
        $q = Doctrine_Query::create();  
        $datos = $q->select('u.idusuario')->from('SegUsuario u, u.DatEntidadSegUsuarioSegRol ur')->where("ur.identidad = ? and ur.idrol = ? ",array($identidad,$idrol))->execute()->toArray();           
        return  $datos;
      }
      
    static public function existealgunusuario(){
        $q = Doctrine_Query::create();  
        $cant = $q->from('SegUsuario')->count();           
        return  $cant;
      }
      
    static public function usuariosdelcargo($idcargo){
        $q = Doctrine_Query::create(); 
        $datos = $q->select('idusuario, nombreusuario')->from('SegUsuario')->where("idcargo = ?",$idcargo)->setHydrationMode(Doctrine::HYDRATE_ARRAY)->execute(); 
        return  $datos;
      }

	static public function validarasignacionroles($arrayUsuario) {
		$query = Doctrine_Query::create();
        $datos = $query->select('d.idrol, d.identidad,u.idusuario,d.idusuario')->from('SegUsuario u')->leftjoin('u.DatEntidadSegUsuarioSegRol d')->whereIn("u.idusuario",$arrayUsuario)->orderby('u.idusuario,d.idrol,d.identidad')->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
        return $datos;
	}
	
	static public function obtenerIdDominioDadoIdUsuario($idusuario) {
		$query = Doctrine_Query::create();
		$datos = $query ->select('iddominio')
				->from('SegUsuario')
				->where("idusuario = ?",$idusuario)
				->execute();
		return $datos[0]->iddominio;
	}

	static public function obtenerIdUsuariosDadoIdDominio($iddominio) {
		$query = Doctrine_Query::create();
		$datos = $query ->select('idusuario, nombreusuario')
				->from('SegUsuario')
				->where("iddominio = ?",$iddominio)
				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
				->execute();
		return $datos;
	}

	static public function obtenerIdUsuario($nombreusuario) {
		$query = Doctrine_Query::create();
		$datos = $query ->select('idusuario')
				->from('SegUsuario')
				->where("nombreusuario = ?",$nombreusuario)
				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
				->execute();
				
		return $datos[0]['idusuario'];
	}
	
	
        public function DesactivarUsuariosExt($cadena){
		$mg = ZendExt_Aspect_TransactionManager::getInstance();
		$conn = $mg->getConnection('postgres');
		$query = Doctrine_Query::create($conn);
		$query->update()->from('SegUsuario')->set("activo",0)->whereIn("nombreusuario",$cadena)->execute();
		$conn->commit();
		return true;
	}
	
	static public function activarUsuarios($cadena){
		$query=Doctrine_Query::create();
	    $query->update()->from('SegUsuario')->set("activo",1)->whereIn("idusuario",$cadena)->execute();
	    return true;
		}
		
		static public function DesactivarUsuarios($cadena){
		$query=Doctrine_Query::create();
	    $query->update()->from('SegUsuario')->set("activo",0)->whereIn("idusuario",$cadena)->execute();
	    return true;
	}
	
	static public function obtenerIdDominios($iddominio){
	    $query=Doctrine_Query::create();
	    $datos=$query->select('u.iddominio,ud.idusuario')
	    			->from('SegUsuario u')
	    			->innerJoin('u.SegUsuarioNomDominio ud')
	    			->where("ud.iddominio =?",$iddominio)
	    			->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
	    			->execute();
	    return $datos;            
		}
		
            static public function obtenerIdDominiosCompartimentados($iddominio){
	    $query=Doctrine_Query::create();
	    $datos=$query->select('u.iddominio,ud.idusuario')
	    			->from('SegUsuario u')
	    			->innerJoin('u.SegCompartimentacionusuario ud')
	    			->where("ud.iddominio =?",$iddominio)
	    			->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
	    			->execute();
	    return $datos;            
		}
	static public function usuariosSinDominio() {
		$query=Doctrine_Query::create();
	    $datos=$query->select('u.idusuario')
	    			->from('SegUsuario u')
	    			->where("u.iddominio = 0")
	    			->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
	    			->execute();
	    return $datos;
		}

    static function cantUsuarioAsociadoRol()
    {
	$idrol = '10000000001';
	$activo = 1;
        $query = Doctrine_Query::create();
        $cant = $query->select('u.idusuario,u.activo, d.idrol')
			->from('SegUsuario u , u.DatEntidadSegUsuarioSegRol d')
			->where("d.idrol =?",$idrol)
			->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
			->execute();
        return $cant;
    }
       
  static function cantidadFilasUsuariosConNombre($nombreusuario, $arrayresult, $dominiobuscar) {
		$where = "u.nombreusuario like '%$nombreusuario%'";
		if ($dominiobuscar)
			$where .= " and u.iddominio = $dominiobuscar";		
	   
   		$query = Doctrine_Query::create();
   		$cantFndes = $query ->select('count(u.idusuario) as cant')
   							->from('SegUsuario u')
   							->where("$where")
   							->whereIn("u.idusuario",$arrayresult)
   							->execute();
		return $cantFndes[0]->cant;
    	} 


  static function buscarUsuarioPorNombre($nombreusuario, $arrayresult, $dominiobuscar,  $limit, $start) {


		$query = Doctrine_Query::create();		
		if($nombreusuario)
		$where = "u.nombreusuario like '%$nombreusuario%'";
		if ($dominiobuscar && $nombreusuario)
			$where .= " and u.iddominio = $dominiobuscar";
		elseif($dominiobuscar && !$nombreusuario)
                $where .= "u.iddominio = $dominiobuscar";
		
                if($where)
		  {
		  $datos = $query ->select('u.idusuario, u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
						->from('SegUsuario u, u.NomDesktop e, u.NomTema t, u.NomIdioma i')
						->where($where)
    					->whereIn("u.idusuario",$arrayresult)
    					->limit($limit)
    					->offset($start)
    					->execute();
		}
		else
        {
	    $datos = $query ->select('u.idusuario, u.activo, u.nombreusuario,u.ip,u.iddominio, u.iddesktop, u.identidad, u.idcargo, e.denominacion, u.idtema, t.denominacion, u.ididioma, i.denominacion')
						->from('SegUsuario u, u.NomDesktop e, u.NomTema t, u.NomIdioma i')
    					->whereIn("u.idusuario",$arrayresult)
    					->limit($limit)
    					->offset($start)
    					->execute();
		}
		return $datos;
    }
     static public function cantusuariodadotema($idtema){
       
        $query = Doctrine_Query::create();
        $cantFndes = $query->select('COUNT(u.idtema) idtema')->from('SegUsuario u')->where("u.idtema = ?",$idtema )->execute();
        return $cantFndes[0]->idtema != 0; 
        }
        
         ///------------------------------
    static public function nombusuariocontrasennabd($id){
	    $query = Doctrine_Query::create();
	    $usuario = $query->select('idusuario,nombreusuario,contrasenabd,contrasenna')->
                    from('SegUsuario')->
                    where("idusuario = ?",$id)->
                    execute()->toArray();
	    return $usuario;
	    }
    static public function updatepassword($pass,$id){
	    $query = Doctrine_Query::create();
	    $query->update()
                    ->from('SegUsuario')
                    ->set("contrasenna","'$pass'")
                    ->set("contrasenabd","'$pass'")
                    ->where("idusuario=?",array($id))
                    ->execute();
        return true;
	    }
}
