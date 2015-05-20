<?php

class DatImagenesUsuario extends BaseDatImagenesUsuario
{

    public function setUp()
    {
        parent :: setUp ();
    }
static function cargarimagenesusuario($idusuario){

 $query = Doctrine_Query::create();

 $datos = $query->select('a.idusuario,a.fecha,a.nombreimage,a.imagen')->from('DatImagenesUsuario a')->where("a.idusuario =?",$idusuario)->execute()->toArray();
		            return $datos;
		          // print_r($datos);die;
}

static function estaimagen($idusuario,$nombreimage){

 $query = Doctrine_Query::create();

 $datos = $query->select('a.idusuario,a.nombreimage')->from('DatImagenesUsuario a')->where("a.idusuario =? and a.nombreimage =?",array($idusuario,$nombreimage))->execute()->toArray();
		            return $datos;
		          // print_r($datos);die;
}

static function cargarimagenes(){
	

 $query = Doctrine_Query::create();

 $datos = $query->select('a.idusuario,a.fecha,a.nombreimage,a.imagen')->from('DatImagenesUsuario a')->execute()->toArray();
		            return $datos;
		           //print_r($datos);die;
}
 static function eliminarimagen($nombreimg,$user){
	    $query = Doctrine_Query::create();
	    $cantFndes = $query->delete()->from('DatImagenesUsuario m')->where("m.nombreimage = ? AND m.idusuario = ?", array($nombreimg,$user))->execute();
	    return true;
	    }		

}

