<?php
class DatagrupacionestModel extends ZendExt_Model
{
    public function DatagrupacionestModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatAgrupacionest();
    }


    public function insertarAgrupaciones($pIdestuctura, $pIdagrupacion)
    {
        $this->instance->idestructura = $pIdestuctura;
        $this->instance->idagrupacion = $pIdagrupacion;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscarAgrupacionest($idagrupacion, $limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $result = $q->select('a.idestructura,a.idagrupacion,e.idestructura,e.denominacion,e.abreviatura,e.idnomeav')
                ->from('DatAgrupacionest a')
                ->innerJoin('a.DatEstructura e')
                //->innerJoin('a.NomAgrupacion n')
                ->where("a.idagrupacion= '$idagrupacion'")
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->limit($limit)
                ->offset($start)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Muestra el valor del nomenclador de agrupaciones
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarAgrupacionestNom($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('a.idagrupacion,a.denagrupacion,a.abrevagrupacion,a.orden,a.fechaini,a.fechafin')
                ->from('NomAgrupacion a')
                ->limit($limit)
                ->offset($start)
                ->execute()
                ->toArray();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function existeAgrupacionest($pId, $idestructutra)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idagrupacion')
                ->from('DatAgrupacionest')
                ->where("idagrupacion ='$pId' AND idestructura='$idestructutra'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function eliminarAgrupacionet($pId, $pIdEstructura)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idagrupacion')
                ->from('DatAgrupacionest')
                ->where("idagrupacion = '$pId' AND idestructura = '$pIdEstructura'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }
}

?>