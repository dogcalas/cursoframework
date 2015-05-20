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
class DatEntidadSegUsuarioSegRol extends BaseDatEntidadSegUsuarioSegRol
{

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('SegUsuario', array('local' => 'idusuario', 'foreign' => 'idusuario'));
        $this->hasOne('SegRol', array('local' => 'idrol', 'foreign' => 'idrol'));
    }

    static public function CargarUsuarioXIdRol($idRol)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (m.idusuario) idususario, m.idusuario,u.nombreusuario,u.contrasenabd')
            ->from('DatEntidadSegUsuarioSegRol m, m.SegUsuario u')
            ->where('u.idusuario=m.idusuario and m.idrol =?', array($idRol))
            ->execute();
        return $result;
    }

    static public function getEntitiesUser($idusuario)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (m.identidad) identidad')
            ->from('DatEntidadSegUsuarioSegRol m')
            ->where('m.idusuario=?', $idusuario)
            ->execute();
        return $result->toArray();
    }

    static public function CargarUsuarioXIdRolV2($idRol)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (m.idusuario) idususario, m.idusuario,u.nombreusuario,u.contrasenabd')
            ->from('DatEntidadSegUsuarioSegRol m, m.SegUsuario u')
            ->where('u.idusuario=m.idusuario and m.idrol =?', array($idRol))
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    static public function CargarRolXIdUsuario($idusuario)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('DISTINCT (m.idrol) idrol, m.idrol')
            ->from('DatEntidadSegUsuarioSegRol m')
            ->where('m.idusuario =?', array($idusuario))
            ->execute();
        return $result;
    }

    static public function eliminarrol($valorsistema, $rol, $identidad)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatEntidadSegUsuarioSegRol')->where('idsistema =? and idrol =? and identidad =?', array($valorsistema, $rol, $identidad))->execute();
    }

    static public function cargarroles($idrol, $idusuario, $identidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('idrol')->from('DatEntidadSegUsuarioSegRol')->where("idrol =? and  idusuario =? and identidad =?", array($idrol, $idusuario, $identidad))->execute();
        return $datos;
    }

    static function eliminarusuariorolentidad($idusuario)
    {
        $query = Doctrine_Query::create();
        $cantFndes = $query->delete()->from('DatEntidadSegUsuarioSegRol m')->where("m.idusuario = ?", array($idusuario))->execute();
        return true;
    }

    static public function obtenerrol($idusuario, $identidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('DISTINCT (m.idrol) idrol')->from('DatEntidadSegUsuarioSegRol m')->where("m.idusuario = ? and m.identidad =?", array($idusuario, $identidad))->execute();
        return $datos;
    }

    static public function obtenerrolusuarioentidad($idusuario, $identidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('DISTINCT (m.idrol) idrol')->from('DatEntidadSegUsuarioSegRol m')->where("m.idusuario = ? and m.identidad =?", array($idusuario, $identidad))->setHydrationMode(Doctrine:: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function rolesperfil($idusuario, $identidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('DISTINCT (r.idrol) idrol, r.denominacion')->from('SegRol r, r.DatEntidadSegUsuarioSegRol m')->where("m.idusuario = ? and m.identidad =?", array($idusuario, $identidad))->execute();
        return $datos;
    }

    static public function cargarentidadesusuario($idusuario)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('e.identidad, e.identidad as idestructura, e.idrol,e.idusuario')->from('DatEntidadSegUsuarioSegRol e ')->where("e.idusuario = ?", $idusuario)->setHydrationMode(Doctrine:: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function eliminarusuariorol($idusuario, $idrol)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatEntidadSegUsuarioSegRol')->where('idusuario =? and idrol =? ', array($idusuario, $idrol))->execute();
    }

    static public function cargarentidadesusuariorol($idusuario, $idrol)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select(' e.identidad, e.identidad as idestructura, e.idrol')->from('DatEntidadSegUsuarioSegRol e ')->where("e.idusuario = ? and e.idrol = ?", array($idusuario, $idrol))->setHydrationMode(Doctrine:: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function cargarentidadesusuarionorol($idusuario, $idrol)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select(' e.identidad, e.identidad as idestructura, e.idrol')->from('DatEntidadSegUsuarioSegRol e ')->where("e.idusuario = ? and e.idrol <> ?", array($idusuario, $idrol))->setHydrationMode(Doctrine:: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function cantidadEntidadesNoAsignadasDadoUsuario($idusuario, $arrayIdEntidades)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('count(e.identidad) as cant')
            ->from('DatEntidadSegUsuarioSegRol e ')
            ->where("e.idusuario = ?", $idusuario)
            ->whereNotIn("e.identidad", $arrayIdEntidades)
            ->execute();
        return $datos[0]->cant;
    }

    static public function obtenerEntidadesNoAsignadasDadoUsuario($idusuario, $arrayIdEntidades)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('e.identidad, e.identidad as idestructura')
            ->from('DatEntidadSegUsuarioSegRol e ')
            ->where("e.idusuario = ?", $idusuario)
            ->whereNotIn("e.identidad", $arrayIdEntidades)
            ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
            ->execute();
        return $datos;
    }

    static public function cantidadEntidadesNoAsignadasDadoArrayUsuarios($arrayIdUsuarios, $arrayIdEntidades)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('count(e.identidad) as cant')
            ->from('DatEntidadSegUsuarioSegRol e ')
            ->whereIn("e.idusuario", $arrayIdUsuarios)
            ->whereIn("e.identidad", $arrayIdEntidades)
            ->execute();
        return $datos[0]->cant;
    }

    static public function eliminarentidadusuariorol($idusuario, $idrol, $identidad)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatEntidadSegUsuarioSegRol')->where('idusuario =? and idrol =? and identidad = ?', array($idusuario, $idrol, $identidad))->execute();
        return;
    }

    static public function eliminarUsuarioDadoIdEntidades($idusuario, $arrayIdEntidades)
    {
        $query = Doctrine_Query::create();
        $query->delete()
            ->from('DatEntidadSegUsuarioSegRol')
            ->where('idusuario =?', $idusuario)
            ->whereIn('identidad', $arrayIdEntidades)
            ->execute();
        return;
    }

    static public function existeusuariorol($identity)
    {
        $q = Doctrine_Query::create();
        $result = $q->select('COUNT(eur.identidad) identidad')->from('DatEntidadSegUsuarioSegRol eur')->where("eur.identidad = ?", $identity)->execute();
        return $result[0]->identidad != 0;
    }

    static function tieneRelacionUsuario($usuarioelim, $entidadelim)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('COUNT(r.identidad) identidad')->from('DatEntidadSegUsuarioSegRol r')->where('r.idusuario = ? and r.identidad = ?', array($usuarioelim, $entidadelim))->execute();
        return $result[0]->identidad != 0;
    }


    static function comprobarExisteRol($idusuario, $idrol)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('d.identidad')->from('DatEntidadSegUsuarioSegRol d')->where("d.idusuario =? and d.idrol =?", array($idusuario, $idrol))->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        return $datos;
    }

    static public function obtenerEntidadesDadoArrayEnt($iddominio, $arrayEnt)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('u.idusuario, d.identidad')
            ->from('SegUsuario u')
            ->innerJoin('u.DatEntidadSegUsuarioSegRol d')
            ->where('u.iddominio = ?', $iddominio)
            ->whereIn("d.identidad", $arrayEnt)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $datos;
    }

    /**
     *<b> OcurrenciasUsuario($idusuario)</b>
     * retorna la cantidad de veces que aparece
     * el idusuario pasado como parámetro en la
     * tabla
     */
    static public function OcurrenciasUsuario($idusuario)
    {
        $query = Doctrine_Query::create();
        return $count = $query->from('DatEntidadSegUsuarioSegRol')
            ->where('idusuario =?', array($idusuario))
            ->count();
    }

    static public function obtenerrol1($idusuario, $arrayidentidad)
    {
        $query = Doctrine_Query::create();
        $datos = $query->select('u.idrolasig ')
            ->from('SegRolAsignacion u')
            ->innerJoin('u.SegRol d ON d.idrol = u.idrol ')
            ->innerJoin('d.DatEntidadSegUsuarioSegRol  e ON d.idrol = e.idrol')
            ->where("e.idusuario =? ", array($idusuario))
            ->whereIn('e.identidad', $arrayidentidad)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $datos;
    }
}
