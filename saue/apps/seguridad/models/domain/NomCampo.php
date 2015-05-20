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
class NomCampo extends BaseNomCampo
{

    public function setUp()
    {
        parent::setUp();      
        $this->hasMany('NomValor',array('local'=>'idcampo','foreign'=>'idcampo'));
        $this->hasOne('NomExpresiones',array('local'=>'idexpresiones','foreign'=>'idexpresiones'));
    }
	
    static function obtenercantcampos()
	{
	        $query = Doctrine_Query::create();
	        $cantFndes = $query->select('count(a.idcampo) as cant')->from('NomCampo a')->execute();
	        return $cantFndes[0]->cant;
	}  
	
    static public function cargarcampo($limit,$start)
	{
	$query = Doctrine_Query::create();
	$fndes = $query->select('campo.idcampo,campo.nombre,campo.nombreamostrar,campo.tipo,campo.longitud,campo.visible,campo.tipocampo,campo.descripcion,campo.idexpresiones,expre.expresion ,expre.denominacion,v.idcampo')->from('NomCampo campo,campo.NomExpresiones expre,campo.NomValor v')->limit($limit)->offset($start)->execute();
	return $fndes;
	}

	static public function cargarstoregrid($limit,$start)
	{
	$query = Doctrine_Query::create();
	$fndes = $query->select('u.idusuario, u.nombreusuario, f.idfila,v.idvalor,v.valor')->from('SegUsuario u')->leftJoin('u.NomFila f')->leftJoin('f.NomValor v')->limit($limit)->offset($start)->execute()->toArray(true);
	return $fndes;
	}
    static public function usuarioBuscado($nombreusuario,$limit,$start)
	{
	$query = Doctrine_Query::create();
	$fndes = $query->select('idusuario, nombreusuario')->from('SegUsuario ')->where("nombreusuario like '%$nombreusuario%'")->limit($limit)->offset($start)->execute()->toArray(true);
	return $fndes;
	}
	static public function comprobardatosusuario($nombre,$nombreamostar)
    {
	    $query = Doctrine_Query::create();
	    $cantidaddatusuarios = $query->from('NomCampo')->where("nombre = ? OR nombreamostrar = ?",array($nombre,$nombreamostar))->count();                      
	    return $cantidaddatusuarios;
    }

	static public function usuarioDominioBuscado($nombreusuario, $arrayresult)	{
		$query = Doctrine_Query::create();
		$fndes = $query->select('u.idusuario, u.nombreusuario, f.idfila,v.idvalor,v.valor')->from('SegUsuario u')->leftJoin('u.NomFila f')->leftJoin('f.NomValor v')->where("u.nombreusuario like '%$nombreusuario%'")->whereIn('u.idusuario',$arrayresult)->execute()->toArray(true);
		return $fndes;
	}

	static public function gridUsuarioDominio($arrayresult)	{
		$query = Doctrine_Query::create();
		$fndes = $query->select('u.idusuario, u.nombreusuario, f.idfila,v.idvalor,v.valor')->from('SegUsuario u')->leftJoin('u.NomFila f')->leftJoin('f.NomValor v')->whereIn('u.idusuario',$arrayresult)->execute()->toArray(true);
		return $fndes;
	}
        
        //------------------------
        static public function CantCamposXIdExpresion($arrayExp){
            $query = Doctrine_Query::create();
	    $cantidaddatusuarios = $query->from('NomCampo')->whereIn("idexpresiones",$arrayExp)->count();                      
	    return $cantidaddatusuarios;
        }
}
