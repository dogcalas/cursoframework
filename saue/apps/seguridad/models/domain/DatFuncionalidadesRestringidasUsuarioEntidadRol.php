<?php

class DatFuncionalidadesRestringidasUsuarioEntidadRol extends BaseDatFuncionalidadesRestringidasUsuarioEntidadRol
{

    public function setUp()
    {
        parent :: setUp();
        $this->hasOne('DatEntidadSegUsuarioSegRol', array('local' => 'idrol', 'foreign' => 'idusuario'));
        $this->hasOne('DatEntidadSegUsuarioSegRol', array('local' => 'identidad', 'foreign' => 'idusuario'));
        $this->hasOne('DatEntidadSegUsuarioSegRol', array('local' => 'idusuario', 'foreign' => 'idusuario'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idrol', 'foreign' => 'idfuncionalidad'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idsistema', 'foreign' => 'idfuncionalidad'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idfuncionalidad', 'foreign' => 'idfuncionalidad'));
    }

    static public function obtenerFuncionalidadesRes($idsistema, $idestructura, $idusuario, $rol)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('idfuncionalidad, idsistema, idrol, identidad')->from('DatFuncionalidadesRestringidasUsuarioEntidadRol ')->where("idusuario = ? and idrol = ? and identidad = ? and idsistema =?", array($idusuario, $rol, $idestructura, $idsistema))->setHydrationMode(Doctrine:: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function obtenerFunResDadoArrayRoles($idsistema, $idestructura, $idusuario, $rol)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('res.idfuncionalidad, res.idsistema, res.idrol, res.identidad')
            ->from('DatFuncionalidadesRestringidasUsuarioEntidadRol res ')
            ->where("res.idusuario = ? and res.identidad = ? and res.idsistema =?", array($idusuario, $idestructura, $idsistema))
            ->whereIn("res.idrol", $rol)
            ->execute();
        return $datos;
    }

    static public function eliminarfuncionalidades($func, $idusuario, $idrol, $identidad)
    {
        $query = Doctrine_Query::create();
        foreach ($func as $val) {
            foreach ($val[1] as $idfunc) {
                $query->delete()->from('DatFuncionalidadesRestringidasUsuarioEntidadRol')
                    ->where('idusuario =? and idrol =? and identidad = ? and idsistema = ? and idfuncionalidad =?', array($idusuario, $idrol, $identidad, $val[0], $idfunc))
                    ->execute();
            }
        }
        return;
    }
}

