<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
class DatSerautenticacion extends BaseDatSerautenticacion
{

	public function setUp()
	  {
	    parent::setUp();    
	    $this->hasOne('DatServidor',array('local'=>'idservidor','foreign'=>'idservidor')); 
	    $this->hasMany('SegUsuario',array('local'=>'idservidor','foreign'=>'idusuario','refClass'=>'SegUsuarioDatSerautenticacion')); 
	    $this->hasMany('DatSistema',array('local'=>'idservidor','foreign'=>'idservidor')); 
	  }

    static public function obtenercantidad($idservidor)
    {
		$query = Doctrine_Query::create();
		$cant = $query->from('SegUsuarioDatSerautenticacion us')
			  ->where("us.idservidor = ?", $idservidor)->count();                       
		return $cant;
    }
	static public function cargarservidoresauth($limit,$start)
	{
	            $q = Doctrine_Query::create();
	            if(($limit!=0))
	            $result = $q->select('s.idservidor id,s.idservidor, s.denominacion text,s.tiposervidor, s.descripcion, s.ip')->from('DatServidor s ')->orderby('idservidor')->limit($limit)->offset($start)->execute();
	            else 
	            $result = $q->select('s.idservidor id,s.idservidor, s.denominacion text')->from('DatServidor s')->innerJoin('s.DatServidorbd b')->orderby('s.idservidor')->execute();	
			    return $result;
		}
		
	static public function tipoServidorAuth($idservidor)
    {
     $q = Doctrine_Query::create();
	 $result = $q->select('s.tservidor')
	 ->from('DatSerautenticacion s ')
	 ->where('s.idservidor = ?',$idservidor)
	 ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
	 ->execute();
	 
	 return $result;
    }	
      
}
