<?php

class DatAccionDatObjetobd extends BaseDatAccionDatObjetobd
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasMany ('DatAccion', array ('local' => 'idaccion', 'foreign' => 'idaccion'));
        $this->hasMany ('DatObjetobd', array ('local' => 'idobjetobd', 'foreign' => 'idobjetobd'));
    }

    static public function getById($idobjetobd,$idaccion){
        return Doctrine::getTable('DatAccionDatObjetobd')->find(array($idobjetobd,$idaccion));
    }
    static public function getBy_idobjetobd($idobjetobd){
        return Doctrine::getTable('DatAccionDatObjetobd')->findByDql('idobjetobd=?',array($idobjetobd));
    }



}

