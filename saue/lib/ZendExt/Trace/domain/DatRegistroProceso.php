<?php

class DatRegistroProceso extends BaseDatRegistroProceso
{

    public function setUp()
    {
        parent :: setUp();
    }

    static public function getconexions()
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatConexion')
            ->execute()->toArray(true);

        return $result;
    }

    static public function activarProceso($id)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatRegistroProceso')
            ->where("id_registro = ?", $id)
            ->execute();

        return $result;
    }

    static public function getRegistros($id)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatRegistroProceso')
            ->where("id_proceso = ?", $id)
            ->execute()->toArray(true);

        return $result;
    }


}

