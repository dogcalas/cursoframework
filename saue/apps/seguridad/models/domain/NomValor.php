<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class NomValor extends BaseNomValor {

    public function setUp() {
        parent::setUp();
        $this->hasOne('NomFila', array('local' => 'idfila', 'foreign' => 'idfila'));
        $this->hasOne('NomCampo', array('local' => 'idcampo', 'foreign' => 'idcampo'));
    }

    static public function genId() {
        $query = new Doctrine_Query ();
        $result = $query->select('max(f.idvalor) as maximo')
                ->from('NomValor f')
                ->execute()
                ->toArray();
        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    static public function cantvaloresdadofila($idfila) {
        $query = Doctrine_Query::create();
        $cantvalores = $query->from('NomValor')
                ->where("idfila = ?", $idfila)
                ->count();
        return $cantvalores;
    }

    static public function cargarvalores($idfila) {
        $query = Doctrine_Query::create();
        $valores = $query->select('idfila,idcampo,valor')->from('NomValor')
                        ->where("idfila = ?", $idfila)
                        ->execute()->toArray();
        return $valores;
    }

    static public function cargaridvalor($idfila, $idcampo) {
        $query = Doctrine_Query::create();
        $idvalores = $query->select('idvalor')->from('NomValor')
                        ->where("idfila = ? and idcampo = ?", array($idfila, $idcampo))
                        ->execute()->toArray(true);
        return $idvalores;
    }

    static function cargarcamposdadovalores($idvalor) {
        $query = Doctrine_Query::create();
        $afuera = $query->select('v.idvalor,c.nombre, c.nombreamostrar')->from('NomValor v,v.NomCampo c')->where('idvalor=?', $idvalor)->orderby('v.idvalor')->execute()->toArray(true);
        return $afuera;
    }

}
