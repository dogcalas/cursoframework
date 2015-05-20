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
class SegClaveacceso extends BaseSegClaveacceso
{

  public function setUp()
  {
    parent::setUp();    
    $this->hasOne('SegUsuario',array('local'=>'idusuario','foreign'=>'idusuario'));   
  }
  
  
  static public function cargarclaves($idusuario)
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.pkidclaveacceso,a.valor,a.clave,a.idusuario,a.fechainicio,a.fechafin')
								->from('SegClaveacceso a')
								->where('a.idusuario = ?',array($idusuario))
								->orderBy('a.fechainicio')
								->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
								->execute();
	            return $fndes;
		}
		
	static public function claveActiva($idusuario)
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.pkidclaveacceso,a.valor,a.clave,a.idusuario,a.fechainicio,a.fechafin')
								->from('SegClaveacceso a')
								->where('a.idusuario = ? AND a.valor = ?',array($idusuario,true))
								->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
								->execute();
	            return $fndes;
		}

}