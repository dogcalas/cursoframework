<?php

class DatEvento extends BaseDatEvento
{

    public function setUp()
    {
        parent :: setUp();
    }

    static public function geteventproceso($idproceso, $offset, $limit)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idevento,nombre,descripcion')
            ->from('DatEvento')
            ->where("idproceso = ?", $idproceso)
            ->offset($offset)
            ->limit($limit)
            ->execute()->toArray(true);

        return $result;
    }

    static public function geteventprocesoall($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatEvento')
            ->where("idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function setevent($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getpl($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('pl')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static function eliminarevent($idevent)
    {
        $query = Doctrine_Query::create();
        $cantFndes = $query->delete()->from('DatEvento m')->where("m.idevento = ?", $idevent)->execute();

        return true;
    }

    static function eliminareventdeproceso($idproceso)
    {
        $query = Doctrine_Query::create();
        $cantFndes = $query->delete()->from('DatEvento m')->where("m.idproceso = ?", $idproceso)->execute();

        return true;
    }

    static public function getcorrelacion($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('conceptname,conceptinstance,orgresource,timestamp,orgrole,orggroup,semanticmodelreference,
		accconceptname,accconceptinstance,accorgresource,acctimestamp,accorgrole,accorggroup')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getorderbygroupby($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('orderby,piid')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getcondiciones($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('condiciones')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getevent($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('piid,conceptinstance,accconceptinstance,conceptname,accconceptname,orggroup,accorggroup,orgresource,accorgresource,orgrole,accorgrole,timestamp,acctimestamp,semanticmodelreference,pl')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getidevent($idproceso)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idevento')
            ->from('DatEvento')
            ->where("idproceso = ?", $idproceso)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getinstanciaevento($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('piid')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }

    static public function getidproceso($idevento)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('idproceso')
            ->from('DatEvento')
            ->where("idevento = ?", $idevento)
            ->execute()->toArray(true);

        return $result;
    }


}
