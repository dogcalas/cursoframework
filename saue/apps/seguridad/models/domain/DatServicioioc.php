<?php

class DatServicioioc extends BaseDatServicioioc
{

    public function setUp()
    {
        parent :: setUp ();
         $this->hasMany('DatServicioObjetobd',array('local'=>'idservicio','foreign'=>'idservicio'));
         $this->hasMany('DatAccionDatServicioioc',array('local'=>'idservicio','foreign'=>'idservicio'));
    }

    static public function obtenerDatServicioIoC($arraIdServicios) {
        $query = Doctrine_Query::create();
        $result = $query->select('s.denominacion,s.subsistema,s.idservicio')
                        ->from('DatServicioioc s')->
                         whereIn("s.idservicio",$arraIdServicios)
                        ->execute();
        return $result;
    }
    static public function ServiviciosObjetosBD() {
        $query = Doctrine_Query::create();
        $result = $query->select('s.idservicio')
                        ->from('DatServicioioc s, s.DatServicioObjetobd so ')->
                         where("s.idservicio=so.idservicio")
                        ->execute();
        return $result;
    }
    
    static public function getById($idservicio){
         return Doctrine::getTable('DatServicioioc')->find($idservicio);
     }
    
}

