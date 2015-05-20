<?php
class NomescalasalarialModel extends ZendExt_Model
{

    public function NomescalasalarialModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomEscalasalarial();
    }

    /**
     * Insertar Escala salarial
     *
     * @param unknown_type $pDenorg
     * @param unknown_type $pAbreorg
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */
    public function insertarNomescalasalarial($pDenominacion, $pAbrev, $pOrden, $pFini, $pFfin)
    {
        //$this->instance->idescalasalarial	= $this->buscaridproximo();
        $this->instance->denominacion = $pDenominacion;
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

    public function usandoNomescalasalarial($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomGrupocomple')
                ->where("idescalasalarial ='$pId'")
                ->execute()
                ->count();
            $consulta1 = $q->from('NomSalario')
                ->where("idescalasalarial ='$pId'")
                ->execute()
                ->count();
            return (($consulta + $consulta1) > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Busca las escalas salarial.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */

    public function buscarNomescalasalarial($limit, $start, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomEscalasalarial')
                    ->where("lower(denominacion) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomEscalasalarial')
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


    public function countNomescalasalarial($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomEscalasalarial')
                    ->where("lower(denominacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->from('NomEscalasalarial')
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
     * Verificar si existe una escala salrial por denominacion o abreviatura
     * @author Rolando Ramos Morales
     * @param String $Denom Denominacion de la escala salarial
     * @param String $Abrev Abreviaturia de la escala salarial
     * @return boolean
     */
    public function existeNomEscalaslaria($Denom, $Abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idescalasalarial')
                ->from('NomEscalasalarial')
                ->where("denominacion ='$Denom' or abreviatura='$Abrev'")
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
     * Verificar si existe una escala salarial
     *
     * @param unknown_type $pId
     * @return unknown
     */


    public function existeNomEscalasalarial($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idescalasalarial')
                ->from('NomEscalasalarial')
                ->where("idescalasalarial ='$pId'")
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
     * Modifica una escala salarial.
     *
     * @param unknown_type $pidescalasalarial
     * @param unknown_type $pDenominacion
     * @param unknown_type $pAbrev
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */

    public function modificarNomEscalasalarial($pidescalasalarial, $pDenominacion, $pAbrev, $pOrden, $pFini, $pFfin)
    {


        $this->instance = $this->conn->getTable('NomEscalasalarial')->find($pidescalasalarial);
        $this->instance->denominacion = $pDenominacion;
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
     * Eliminar una escala salarial;
     *
     * @return unknown
     */

    public function eliminarNomEscalasalarial($pId)
    {

        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idescalasalarial')
                ->from('NomEscalasalarial')
                ->where("idescalasalarial = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }


    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(e.idescalasalarial) as maximo')
            ->from('NomEscalasalarial e')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

}

?>
