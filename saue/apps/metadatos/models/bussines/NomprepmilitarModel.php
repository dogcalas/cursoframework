<?php
class NomprepmilitarModel extends ZendExt_Model
{
    public function NomprepmilitarModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomPrepmilitar();
    }

    /**
     * Verificar si existe una preparacion militar por denominacion y abreviatura
     * @author Rolando Ramos Morales
     * @param String $denom Denominacion del la preparacion militar
     * @param String $abrev Abreviatura del la preparacion militar
     * @return boolean
     */
    public function existeNompmilitarDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idprepmilitar')
                ->from('NomPrepmilitar')
                ->where("denprepmilitar ='$denom'or abrevprepmilitar='$abrev'")
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
     * Verifica si existe el elemento
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomPrepmilitar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idprepmilitar')
                ->from('NomPrepmilitar')
                ->where("idprepmilitar ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function usandoPrepraMtar($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $consulta = $q->from('NomCargomilitar')
                ->where("idprepmilitar ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }


    /**
     * Buscar el listado de elementos de la preparacion militar
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomPrepmilitar($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomPrepmilitar')
                    ->where("LOWER(denprepmilitar) like '%$denom%'")

                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomPrepmilitar')
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

    public function cantNomPrepmilitar($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomPrepmilitar')
                    ->where("LOWER(denprepmilitar) like '%$denom%'")
                    ->execute()
                    ->count();
                return $result;
            } else {
                $result = $q->from('NomPrepmilitar')
                    ->execute()
                    ->count();
                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Inserta un elemento en el nomenclador de preparacion militar
     *
     * @param string $denominacion
     * @param string $abreviatura
     * @param int $orden
     * @param date $fechaini
     * @param date $fechafin
     * @return bool
     */
    public function insertarNomPrepmilitar($denominacion, $abreviatura, $orden, $fechaini, $fechafin)
    {
        //$this->instance->idprepmilitar			= $this->buscaridproximo();
        $this->instance->denprepmilitar = $denominacion;
        $this->instance->abrevprepmilitar = $abreviatura;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function modificarNomPrepmilitar($idprep, $denominacion, $abreviatura, $orden, $fechaini, $fechafin)
    {

        $this->instance = $this->conn->getTable('NomPrepmilitar')->find($idprep);
        $this->instance->denprepmilitar = $denominacion;
        $this->instance->abrevprepmilitar = $abreviatura;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    /**
     * Busca el proximo id autogenerado
     *
     * @return unknown
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idprepmilitar) as maximo')
            ->from('NomPrepmilitar a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function eliminarNomPrepmilitar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idprepmilitar')->from('NomPrepmilitar')->where("idprepmilitar = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }
}

?>
