<?php

class DatServicioObjetobd extends BaseDatServicioObjetobd
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasMany('DatServicioioc', array('local' => 'idservicio', 'foreign' => 'idservicio'));
        $this->hasMany('DatObjetobd', array('local' => 'idobjetobd', 'foreign' => 'idobjetobd'));
    }
    static public function getById($idservicio,$idobjetobd){
        return Doctrine::getTable('DatServicioObjetobd')->find(array($idservicio,$idobjetobd));
    }
    static public function getBy_idobjetobd($idobjetobd){
        return Doctrine::getTable('DatServicioObjetobd')->findByDql('idobjetobd=?',array($idobjetobd));
    }
    static public function getBy_idservicio($idservicio){
        return Doctrine::getTable('DatServicioObjetobd')->findByDql('idservicio=?',array($idservicio));
    }
}

