<?php
class NomcategoriaocupacionalModel extends ZendExt_Model
{

    public function NomcategoriaocupacionalModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCategocup();
    }


    /**
     * Insertar
     *
     * @param string $denesp
     * @param int $orden
     * @param int $fini
     * @param int $ffin
     * @return bool
     */
    function insertarNomCategoriaOcup($denesp, $orden, $fini, $ffin, $Abrev)
    {

        //$this->instance->idcategocup	= $this->buscaridproximo();
        $this->instance->dencategocup = $denesp;
        $this->instance->orden = $orden;
        $this->instance->abreviatura = $Abrev;
        $this->instance->fechaini = $fini;
        $this->instance->fechafin = $ffin;
        try {
            $this->instance->save();
            return true;

        } catch (Exception $e) {
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }


    /**
     * Devuelve el arreglo con las especialidades de la bas e dedatos
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomCategoriaOcup($limit = 100, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomCategocup ')
                    ->where(" lower(dencategocup) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();

                return $result;
            } else {
                $result = $q->from('NomCategocup ')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();

                return $result;
            }

        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    public function cantNomCategoriaOcup($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('*')->from('NomCategocup ')
                    ->where(" lower(dencategocup) like '%$denom%'")
                    ->execute()->count();
                return $result;
            } else {
                $result = $q->from('NomCategocup ')
                    ->execute()->count();
                return $result;
            }
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Comprobar si existe una categoria osupacional por denominacion o abreviatura.
     * @author Rolando Ramos Morales
     * @param String $Denom Denominacion de la categoria ocupacional
     * @param String $Abrev Abreviatura de la categoria ocupacional
     * @return boolean
     */
    public function existeNomCategDenomAbrev($Denom, $Abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcategocup')
                ->from('NomCategocup')
                ->where("dencategocup ='$Denom' or  abreviatura='$Abrev'")
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
     * Existe
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomCategoriaOcupId($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcategocup')
                ->from('NomCategocup')
                ->where("idcategocup ='$pId'")
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
     * Existe
     *
     * @param int $pId
     * @return bool
     */
    public function usadoNomCategoriaOcupId($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomCargocivil')
                ->where("idcategocup ='$pId'")
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
     * Eliminar
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarNomCategoriaOcup($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcategocup')
                ->from('NomCategocup')
                ->where("idcategocup = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

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
        $result = $query->select('max(a.idcategocup) as maximo')
            ->from('NomCategocup a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    /**
     * Modificar una sub categoria del grado militar
     *
     * @param int $pidesp
     * @param int $denesp
     * @param int $orden
     * @param date $fini
     * @param date $ffin
     * @return bool
     */
    function modificarNomCategoriaOcup($pidesp, $denesp, $orden, $fini, $ffin, $Abrev)
    {

        $this->instance = $this->conn->getTable('NomCategocup')->find($pidesp);
        $this->instance->dencategocup = $denesp;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fini;
        $this->instance->fechafin = $ffin;
        $this->instance->abreviatura = $Abrev;
        try {
            $this->instance->save();
            return true;

        } catch (Exception $e) {
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }
}

?>
