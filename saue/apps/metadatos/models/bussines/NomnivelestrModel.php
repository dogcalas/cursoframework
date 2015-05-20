<?php
class NomnivelestrModel extends ZendExt_Model
{

    function NomnivelestrModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomNivelestr();

    }

    public function insertarNomNivelestr($pDen, $pAbrev, $pOrden, $pFechaini, $pFechafin)
    {
        //$this->instance->idnivelestr	= $this->buscaridproximo();
        $this->instance->abrevnivelestr = $pAbrev;
        $this->instance->dennivelestr = $pDen;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function buscarNomNivelestr($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomNivelestr ')
                    ->limit($limit)
                    ->offset($start)
                    ->where("lower(dennivelestr) like '%$denom%'")
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            } else {
                $result = $q->from('NomNivelestr ')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    public function listarNomNivelestr($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomNivelestr ')
                ->limit($limit)
                ->offset($start)
                ->orderby('orden')
                ->setHydrationMode(Doctrine::HYDRATE_NONE)
                ->execute();

            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    public function usandoNivelsestr($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomOrgano')
                ->where("idnivelestr ='$pId'")
                ->execute()
                ->count();
            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantNomNivelestr($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $result = $q->from('NomNivelestr ')
                    ->where("lower(dennivelestr) like '%$denom%'")
                    ->execute()
                    ->count();

                return $result;
            } else {
                $result = $q->from('NomNivelestr ')
                    ->execute()
                    ->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    public function existeNomNivelEstrPorDenomAbrv($denom, $abrev)
    {

        $q = Doctrine_Query::create();
        $consulta = $q->select('idnivelestr')
            ->from('NomNivelestr')
            ->where("dennivelestr ='$denom' or abrevnivelestr='$abrev'")
            ->execute()->count();
        return ($consulta != 0);
    }

    public function existeNomNivelEstr($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idnivelestr')
                ->from('NomNivelestr')
                ->where("idnivelestr ='$pId'")
                ->execute()->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarNomNivelEstr($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idnivelestr')
                ->from('NomNivelestr')
                ->where("idnivelestr = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** --------------------------------------------
     * Devuelve el proximo id de la tabla
     *
     * @return int
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idnivelestr) as maximo')
            ->from('NomNivelestr a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function modificarNomNivelEstr($pId, $pDen, $pAbrev, $pOrden, $pFechaini, $pFechafin)
    {
        try {

            $this->instance = $this->conn->getTable('NomNivelestr')->find($pId);
            $this->instance->abrevnivelestr = $pAbrev;
            $this->instance->dennivelestr = $pDen;
            $this->instance->orden = $pOrden;
            $this->instance->fechaini = $pFechaini;
            $this->instance->fechafin = $pFechafin;
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

}

?>
