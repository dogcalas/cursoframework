<?php

class temporal extends ZendExt_Model
{

    public function  __construct()
    {

        parent::ZendExt_Model();

    }

    public function temp($idclasif, $id)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"rep_gruposXclasificacion\"('$idclasif','$id') AS (clasificacion varchar,idorg numeric,cantgrup numeric,cantEnt numeric,cantUnid  numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }
}

?>