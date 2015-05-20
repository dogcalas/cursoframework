<?php
class  NomcargomilitarModel extends ZendExt_Model
{
    public function NomcargomilitarModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCargomilitar();
    }

    public function insertarNomcargomilitar($pDonocmilitar, $pAbrecargomilitar, $pIdespecialidad, $pOrden, $pIdprepmilitar, $pFechaini, $pFechafin)
    {
        //$this->instance->idcargomilitar			= $this->buscaridproximo();
        $this->instance->dencargomilitar = $pDonocmilitar;
        $this->instance->abrevcargomilitar = $pAbrecargomilitar;
        $this->instance->idespecialidad = $pIdespecialidad;
        $this->instance->orden = $pOrden;
        $this->instance->idprepmilitar = $pIdprepmilitar;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }

    }

    public function usandoNomcargoMtar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatCargomtar')
                ->where("idcargomilitar ='$pId'")
                ->execute()
                ->count();

            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function modificarNomcargomilitar($idcargomilitar, $pDonocmilitar, $pAbrecargomilitar, $pIdespecialidad, $pOrden, $pIdprepmilitar, $pFechaini, $pFechafin)
    {
        $this->instance = $this->conn->getTable('NomCargomilitar')->find($idcargomilitar);
        $this->instance->dencargomilitar = $pDonocmilitar;
        $this->instance->abrevcargomilitar = $pAbrecargomilitar;
        $this->instance->idespecialidad = $pIdespecialidad;
        $this->instance->orden = $pOrden;
        $this->instance->idprepmilitar = $pIdprepmilitar;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }

    }


    /**
     * Buscar cargos militares que existen
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomcargomtar($limit = 10000, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('ncm.*,np.idprepmilitar,np.denprepmilitar')
                    ->from('NomCargomilitar ncm')
                    ->innerJoin('ncm.NomPrepmilitar np ')
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->where("lower(ncm.dencargomilitar) like '%$denom%'")
                    ->orderby('orden')
                    ->execute();
            } else {
                $result = $q->select('ncm.*,np.idprepmilitar,np.denprepmilitar')
                    ->from('NomCargomilitar ncm')
                    ->innerJoin('ncm.NomPrepmilitar np ')
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantNomcargMilitar($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('ncm.*,np.idprepmilitar,np.denprepmilitar')
                    ->from('NomCargomilitar ncm')
                    ->innerJoin('ncm.NomPrepmilitar np ')
                    ->where("lower(ncm.dencargomilitar) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->select('ncm.*,np.idprepmilitar,np.denprepmilitar')
                    ->from('NomCargomilitar ncm')
                    ->innerJoin('ncm.NomPrepmilitar np ')
                    ->execute()->count();

                return $result;
            }

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Comprobar si eciste un cargo por la denominacion o la abreviatura
     * @author Rolando Ramos Morales
     * @param String $Denom Denominacion del nomenclador del cargo militar
     * @param String $Abrev Abreviatura del nomenclador del cargo militar
     * @return boolean
     */
    public function existeNomcargomtarDenomAbrev($Denom, $Abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargomilitar')
                ->from('NomCargomilitar')
                ->where("dencargomilitar ='$Denom'or abrevcargomilitar='$Abrev'")
                ->execute()
                ->count();

            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     * Verificar si existe en la base de datos
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomcargo($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargomilitar')
                ->from('NomCargomilitar')
                ->where("idcargomilitar ='$pId'")
                ->execute()
                ->count();

            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarNomcargomtar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcargomilitar')->from('NomCargomilitar')->where("idcargomilitar = '$pId'")->execute();
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
        $result = $query->select('max(a.idcargomilitar) as maximo')
            ->from('NomCargomilitar a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }
}

?>
