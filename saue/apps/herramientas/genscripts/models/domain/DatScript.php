<?php

class DatScript extends BaseDatScript {

    public function setUp() {
        parent :: setUp();
        $this->hasOne('NomTiposscript', array('local' => 'id_tiposcript', 'foreign' => 'id_tiposcript'));
    }

    static function ExistScript($nombre, $cx) {
        $query = Doctrine_Query::create($cx);
        $datos = $query->select('sc.id_script')
                        ->from('DatScript sc')
                        ->where('sc.nombre_script = ?', array($nombre))
                        ->execute()->toArray();
        
        return $datos;
    }

    static function AllScript($cx) {
        $query = Doctrine_Query::create($cx);
        $datos = $query->select('sc.id_script, sc.nombre_paquete, sc.nombre_sistema, sc.version_sistema, sc.version_script, sc.nombre_script, sc.id_tiposcript, sc.usuario, sc.fecha, sc.ip_host')
                        ->from('DatScript sc')
                        ->execute()->toArray();
       
        return $datos;
    }

}

