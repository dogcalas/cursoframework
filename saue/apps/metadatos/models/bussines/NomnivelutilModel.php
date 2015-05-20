<?php
class NomnivelutilModel extends ZendExt_Model
{

    function NomnivelutilModel()
    {
        parent::ZendExt_Model();
        $this->instance = new  NomNivelUtilizacion();
    }

    /**
     * Insertar un nivl de utilizacion.
     *
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */
    public function  insertarNomnivelutilizacion($pDenom, $pAbrev, $pOrden, $pFini, $pFfin)
    {

        //$this->instance->idnivelutilizacion		=	$this->buscaridproximo();
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;
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

    /**
     * buscar los niveles de utilizacion.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */

    public function buscarNomnivelutilizacion($limit, $start, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomNivelUtilizacion')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomNivelUtilizacion')
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

    /**
     * Contar la cantidaad de nivel de utilizacion.
     *
     * @return unknown
     */
    public function contNomnivelurtilizacion($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomNivelUtilizacion')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->from('NomNivelUtilizacion')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function usandoNomnivelutilizacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomCargocivil')
                ->where("idnivelutilizacion ='$pId'")
                ->execute()
                ->count();
            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     * Modificar un nivel de utilizacion.
     *
     * @param unknown_type $pIdnivelutilizacion
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */

    public function modificarNomnivelutilizacion($pIdnivelutilizacion, $pDenom, $pAbrev, $pOrden, $pFini, $pFfin)
    {
        $this->instance = $this->conn->getTable('NomNivelUtilizacion')->find($pIdnivelutilizacion);
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;

        try {
            $this->instance->save();
            return true;

        } catch (Exception $e) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$e->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }

    }

    /**
     * Eliminar un nivel de utilizacion.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function eliminarNomnivelutilizacion($pId)
    {
        try {
            $this->instance = new NomNivelUtilizacion();
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);
            $result = $query->delete('idnivelutilizacion')
                ->from('NomNivelUtilizacion')

                ->where("idnivelutilizacion = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Verificar si existe un nivel por denominacion o abreviatura.
     * @author Rolando Ramos Morales
     * @param String $denom Denominacion del nivel
     * @param String $abrev Abreviatura del nivel
     * @return boolean
     */
    public function  existeNomnivelDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idnivelutilizacion')
                ->from('NomNivelUtilizacion')
                ->where("denominacion ='$denom'or abreviatura='$abrev'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }


    public function existeNomnivelutilizacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idnivelutilizacion')
                ->from('NomNivelUtilizacion')
                ->where("idnivelutilizacion ='$pId'")
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
     * Tomar el proximo id para insertar;
     *
     * @return unknown
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(n.idnivelutilizacion) as maximo')
            ->from('NomNivelUtilizacion n')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }
}

?>
