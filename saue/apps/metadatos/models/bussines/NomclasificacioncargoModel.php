<?php
class NomclasificacioncargoModel extends ZendExt_Model
{

    public function NomclasificacioncargoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomClasificacionCargo();
    }

    /**
     * Insertar una clasificacion
     * @return bool;
     *
     */
    public function insertarNomclasificacion($pDenom, $pOrden, $pFechaini, $pFechafin)
    {
        //$this->instance->idclasificacion		= $this->buscarproximoid();
        $this->instance->denominacion = $pDenom;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;

        try {

            $this->instance->save();
            return true;

        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    /**
     * Verifica si existe una clasificacion del cargo.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function exitNomclasificacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idclasificacion')
                ->from('NomClasificacionCargo')
                ->where("idclasificacion ='$pId'")
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
     * Modificar Nomenclador de clasificacion de cargo.
     *
     * @param unknown_type $pIdclasificacion
     * @param unknown_type $pDenom
     * @param unknown_type $pOrden
     * @param unknown_type $pFechaini
     * @param unknown_type $pFechafin
     * @return unknown
     */
    public function modificarNomclasificacion($pIdclasificacion, $pDenom, $pOrden, $pFechaini, $pFechafin)
    {
        $this->instance = $this->conn->getTable('NomClasificacionCargo')->find($pIdclasificacion);

        $this->instance->denominacion = $pDenom;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;

        try {

            $this->instance->save();
            return true;

        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }

    }

    /**
     * Eliminar una clasificacion de cargo.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function eliminarNomclasificacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idclasificacion')
                ->from('NomClasificacionCargo')
                ->where("idclasificacion = '$pId'")
                ->execute();
            return ($result != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     * Buscar las clasificaciones del cargo.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return array
     */
    public function buscarNomclasificacion($limit, $start, $denom = false)
    {
        try {

            if ($denom) {
                $denom = strtolower($denom);
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('*')
                    ->from('NomClasificacionCargo')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;

            } else {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('*')
                    ->from('NomClasificacionCargo')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            }


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     *
     * Contar las clasificaciones de cargos existentes.
     *
     * @return unknown
     */
    public function conNomclasificacion($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);

                $result = $q->select('*')
                    ->from('NomClasificacionCargo')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->select('*')
                    ->from('NomClasificacionCargo')
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
     * busca el proximo id.
     *
     * @return unknown
     */
    public function buscarproximoid()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idclasificacion) as maximo')
            ->from('NomClasificacionCargo a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function usandoClasificacion($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $consulta = $q->from('DatCargocivil')
                ->where("idclasificacion ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }
}

?>
