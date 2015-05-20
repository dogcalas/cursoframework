<?php

class SegDatosImagenes extends BaseSegDatosImagenes
{

    public function setUp()
    {
        parent :: setUp ();
    }
static	public function cargardatosimg($limit,$start)
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.iddatosimg,a.alto,a.ancho,a.brillo,a.contraste,a.cantimg,a.forma,a.formato')->from('SegDatosImagenes a')->orderby('iddatosimg')->limit($limit)->offset($start)->execute();
	            return $fndes;
		}
                 static function obtenerdatos()
		{
	            $query = Doctrine_Query::create();
	            $cantFndes = $query->select('COUNT(a.iddatosimg) cant')->from('SegDatosImagenes a')
	            				   ->execute();
	            return $cantFndes[0]['cant'];
		}  
                static function cargarcantidad()
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.cantimg')->from('SegDatosImagenes a')->execute();
	            return $fndes[0]['cantimg'];
		}

}

