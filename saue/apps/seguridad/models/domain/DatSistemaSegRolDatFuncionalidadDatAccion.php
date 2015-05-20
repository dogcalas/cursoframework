<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class DatSistemaSegRolDatFuncionalidadDatAccion extends BaseDatSistemaSegRolDatFuncionalidadDatAccion
{

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DatAccion', array('local' => 'idaccion', 'foreign' => 'idaccion'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idsistema', 'foreign' => 'idsistema'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idfuncionalidad', 'foreign' => 'idfuncionalidad'));
        $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local' => 'idrol', 'foreign' => 'idrol'));
        $this->hasMany('SegRol', array('local' => 'idrol', 'foreign' => 'idrol'));
    }

    static public function cargaraccionesquetiene($idsistema, $idrol, $idfuncionalidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('a.idaccion, a.denominacion,a.idfuncionalidad')->from('DatAccion a,a.DatSistemaSegRolDatFuncionalidadDatAccion s')
            ->where("s.idsistema = ? and s.idrol=? and s.idfuncionalidad=?", array($idsistema, $idrol, $idfuncionalidad))->execute();

        return $datos;
    }

    static public function cargaraccionesFUN($idsistema, $idrol, $idfuncionalidad)
    {
        $query = Doctrine_Query::create();
        $stmt = $query->getConnection()->prepare("SELECT m.idaccion AS m__idaccion, m.denominacion AS m__denominacion,
                                                         m.idfuncionalidad AS m__idfuncionalidad, m2.idrol
                                                    FROM mod_seguridad.dat_accion m
                                                    LEFT JOIN mod_seguridad.dat_sistema_seg_rol_dat_funcionalidad_dat_accion m2
                                                    ON m.idaccion = m2.idaccion
                                                    WHERE (m2.idsistema = $idsistema AND m2.idrol = $idrol
                                                    AND m2.idfuncionalidad = $idfuncionalidad)
                                                    ORDER BY m2.idaccion;");
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }


    static public function todaslasaccionesquetiene($idsistema, $idrol, $idfuncionalidad)
    {
        $query = Doctrine_Query::create();
        $cantidad = $query->select('count(s.idaccion) cant')
            ->from('DatSistemaSegRolDatFuncionalidadDatAccion s')
            ->where("s.idsistema = ?  and s.idrol = ? and s.idfuncionalidad = ?", array($idsistema, $idrol, $idfuncionalidad))->execute();
        return $cantidad[0]->cant;
    }

    static public function cargaraccionesquenotiene($idsistema, $idrol, $idfuncionalidad)
    {
        $query = Doctrine_Query::create();
        $acciones = $query->select('a.idaccion, a.idfuncionalidad')->from('DatAccion a,a.DatSistemaSegRolDatFuncionalidadDatAccion s')
            ->where("s.idsistema = ? and s.idrol=? and s.idfuncionalidad=?", array($idsistema, $idrol, $idfuncionalidad))->execute();
        return $acciones;
    }

    static function eliminaraccion($idsistema, $idfuncionalidad, $idrol, $accionesEliminar)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion r')
            ->where("r.idsistema = ? and r.idfuncionalidad = ? and r.idrol = ?", array($idsistema, $idfuncionalidad, $idrol))
            ->whereIn('r.idaccion', $accionesEliminar)
            ->execute();
        return true;
    }

    static function eliminarAccionesAutorizadas($arrayAccEliminar, $rolesDominio)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion r')
            ->whereIn('r.idaccion', $arrayAccEliminar)
            ->whereIn('r.idrol', $rolesDominio)
            ->execute();
        return true;
    }

    static function obtenerAccionesAutorizadas($arrayAccEliminar, $rolesDominio)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('r.idaccion')->from('DatSistemaSegRolDatFuncionalidadDatAccion r')
            ->whereIn('r.idaccion', $arrayAccEliminar)
            ->whereIn('r.idrol', $rolesDominio)
            ->execute();
        return $datos->toArray();
    }

    ////-------------------------------------------
    static public function eliminarXrol($idrol)
    {

        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion')->where("idrol = ?", array($idrol))->execute();
        return true;
    }

    static public function eliminarXSQL($where, $datos)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion')->where($where, $datos)->execute();
    }

    static public function AccionesDeRolesBD()
    {
        $query = Doctrine_Query::create();
        $idacciones = $query->select('srfa.idaccion,r.denominacion,rsg.idservidor,rsg.idgestor')
            ->from('DatSistemaSegRolDatFuncionalidadDatAccion srfa')
            ->innerJoin('srfa.SegRol r')
            ->innerJoin('r.SegRolDatServidorDatGestor rsg')
            ->orderby('srfa.idaccion')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $idacciones;
    }

    static public function IdAccionesFromRol($idrol)
    {
        $query = Doctrine_Query::create();
        $idacciones = $query->select('idaccion')
            ->from('DatSistemaSegRolDatFuncionalidadDatAccion')
            ->where('idrol=?', $idrol)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $idacciones;
    }

    static public function IdAccionesFromRolSistemasFunctionalidades($idrol, $idfuncionalidades)
    {
        $query = Doctrine_Query::create();
        $idacciones = $query->select('idaccion')
            ->from('DatSistemaSegRolDatFuncionalidadDatAccion')
            ->where('idrol=?', $idrol)
            ->whereIn('idfuncionalidad', $idfuncionalidades)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $idacciones;
    }

}