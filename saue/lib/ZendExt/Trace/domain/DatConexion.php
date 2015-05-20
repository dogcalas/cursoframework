<?php

class DatConexion extends BaseDatConexion
{

    public function setUp()
    {
        parent :: setUp();
    }

    static public function getconexions($offset, $limit)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatConexion')
            ->offset($offset)
            ->limit($limit)
            ->execute()->toArray(true);

        return $result;
    }

    static public function setconexions($id)
    {
        $query = Doctrine_Query::create();
        $result = $query->select('*')
            ->from('DatConexion')
            ->where("id = ?", $id)
            ->execute()->toArray(true);

        return $result;
    }


    static function eliminarconexion($idconexion)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('DatConexion m')->where("m.id = ?", $idconexion)->execute();
        return true;
    }

}

