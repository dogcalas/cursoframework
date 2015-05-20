<?php
class NomagrupacionesModel extends ZendExt_Model
{
    public function __construct()
    {
        parent::ZendExt_Model();
        $this->instance = new NomAgrupacion();
    }

    /**
     * Verificar si existe una apgrupacion por denominacion a abreviatura.
     * @author Rolando Ramos Morales
     * @param String $denom
     * @param String $abrev
     * @return boolean
     */
    public function existeNomAgrupacionDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $consulta = $q->select('idagrupacion')
                ->from('NomAgrupacion')
                ->where("denagrupacion ='$denom'or abrevagrupacion='$abrev'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function existeNomAgrupacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $consulta = $q->select('idagrupacion')
                ->from('NomAgrupacion')
                ->where("idagrupacion ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantNomAgrup($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomAgrupacion')
                    ->where("LOWER(denagrupacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->from('NomAgrupacion')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscarNomAgrupacion($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomAgrupacion')
                    ->where("LOWER(denagrupacion) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomAgrupacion')
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


    public function insertarNomAgrupacion($denagrupacion, $abrevagrupacion, $orden, $fechaini, $fechafin)
    {

        $this->instance->idagrupacion = $this->buscaridproximo();
        $this->instance->denagrupacion = $denagrupacion;
        $this->instance->abrevagrupacion = $abrevagrupacion;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function modificarNomAgrupacion($idagrupacion, $denagrupacion, $abrevagrupacion, $orden, $fechaini, $fechafin)
    {
        $this->instance = $this->conn->getTable('NomAgrupacion')->find($idagrupacion);
        $this->instance->denagrupacion = $denagrupacion;
        $this->instance->abrevagrupacion = $abrevagrupacion;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function usandoNomagrupaciones($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatAgrupacionest d')
                ->where("d.idagrupacion ='$pId'")
                ->execute()
                ->count();

            return (($consulta) != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idagrupacion) as maximo')
            ->from('NomAgrupacion a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function eliminarNomAgrupacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idagrupacion')->from('NomAgrupacion')->where("idagrupacion = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

}

?>
