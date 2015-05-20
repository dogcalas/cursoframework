<?php

class NomTipoconex extends BaseNomTipoconex
{

    public function setUp()
    {
        parent :: setUp ();
    }
    
    static public function CargarConexiones(){
        $query = Doctrine_Query::create();
        $result=$query->from('NomTipoconex')
                      ->orderby('denominacion')
                      ->execute();
        return $result;
    }


}

