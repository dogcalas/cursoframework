<?php

class SegDatosReconocimiento extends BaseSegDatosReconocimiento
{

    public function setUp()
    {
        parent :: setUp ();
    }
static	public function cargardatoreconocimiento($limit,$start)
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.iddatosreconocimiento,a.metododistancia,a.metodoknn,a.ndescomposicion,a.metodorec')->from('SegDatosReconocimiento a')->orderby('iddatosreconocimiento')->limit($limit)->offset($start)->execute();
	            return $fndes;
		}
                static function obtenerdatos()
		{
			  $query = Doctrine_Query::create();
	            $cantFndes = $query->select('COUNT(a.iddatosreconocimiento) cant')->from('SegDatosReconocimiento a')
	            				   ->execute();
	            return $cantFndes[0]['cant'];
		}  
                  static function obtenernivel()
		{
		    $query = Doctrine_Query::create();
	            $Fndes = $query->select('a.ndescomposicion')->from('SegDatosReconocimiento a')
	            				   ->execute();
	            return $Fndes[0]['ndescomposicion'];
		}  
                
                

}

