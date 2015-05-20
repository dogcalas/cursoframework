<?php

class DatProcess extends BaseDatProcess
{

    public function setUp()
    {
        parent :: setUp();
    }

    //creado para el paginado
    static public function obtenercantprocesos()
    {
        $query = Doctrine_Query::create();
        $cantFndes = $query->select('count(s.idproceso) cant')->from('DatProcess s')->execute();
        return $cantFndes[0]->cant;
    }

    static public function getproceso($offset, $limit)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idproceso,nombre,descripcion,fuentedatos,idconexion,instancia,validado,activado,version')
            ->from('DatProcess')
            ->offset($offset)
            ->limit($limit)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getprocesoactivo($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('activado')
            ->from('DatProcess')
            ->where("idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getsomeatributtproceso($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idproceso,nombre,version')
            ->from('DatProcess')
            ->where("idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function setproceso($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatProcess')
            ->where("idproceso = ?", $idproceso)
            ->execute();

        return $result;
    }

    static function eliminarproceso($idproceso)
    {
        $query = Doctrine_Query::create();
        $cantFndes = $query->delete()->from('DatProcess m')->where("m.idproceso = ?", $idproceso)->execute();
        return true;
    }

    static public function getidconexion($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select(idconexion)
            ->from('DatProcess m')
            ->where("m.idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getschemas($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select(esquemas)
            ->from('DatProcess m')
            ->where("m.idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function gettables($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select(tablas)
            ->from('DatProcess m')
            ->where("m.idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getvalidado($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select(validado)
            ->from('DatProcess m')
            ->where("m.idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getactivado($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select(activado)
            ->from('DatProcess m')
            ->where("m.idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function activarProceso($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idproceso,nombre,descripcion,fuentedatos,idconexion,tablas,esquemas,instancia,eventinicio,eventfin,activado,validado,version,modificarversion')
            ->from('DatProcess')
            ->where("idproceso = ?", $idproceso)
            ->execute();

        return $result[0];
    }

}
