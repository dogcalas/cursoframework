<?php

class DatAccionDatServicioioc extends BaseDatAccionDatServicioioc
{

    public function setUp()
    {
        parent :: setUp ();
         $this->hasOne('DatAccion', array('local' => 'idaccion', 'foreign' => 'idaccion'));
        $this->hasOne('DatServicioioc', array('local' => 'idservicio', 'foreign' => 'idservicio'));
    }
    static public function getById($idservicio,$idaccion){
       return Doctrine::getTable('DatAccionDatServicioioc')->find(array($idservicio,$idaccion));
    }
    static public function getBy_idServicio($idservicio){
       return Doctrine::getTable('DatAccionDatServicioioc')->findByDql('idservicio=?',array($idservicio));
    }

}

