<?php
class NomtipocalificadorModel extends ZendExt_Model
{

    public function NomtipocalificadorModel()
    {
        parent::ZendExt_Model();
        $this->instanse = new NomTipoCalificador();

    }

    /**
     * insertar un tipo de calificador.
     *
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFechaini
     * @param unknown_type $pFechafin
     */
    public function insertarNomtipocalificador($pDenom, $pAbrev, $pOrden, $pFechaini, $pFechafin)
    {
        //$this->instanse->idtipocalificador		= $this->buscaridproximo();
        $this->instanse->denominacion = $pDenom;
        $this->instanse->abreviatura = $pAbrev;
        $this->instanse->orden = $pOrden;
        $this->instanse->fechaini = $pFechaini;
        $this->instanse->fechafin = $pFechafin;
        try {
            $this->instanse->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC24', $ee);

            return false;
        }
    }

    /**
     * modificar un valor del tipo de calificador.
     *
     * @param unknown_type $pIdtipocalificador
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFechaini
     * @param unknown_type $pFechafin
     * @return unknown
     */
    public function modificarNomtipocalificador($pIdtipocalificador, $pDenom, $pAbrev, $pOrden, $pFechaini, $pFechafin)
    {
        $this->instanse = $this->conn->getTable('NomTipoCalificador')->find($pIdtipocalificador);
        $this->instanse->denominacion = $pDenom;
        $this->instanse->abreviatura = $pAbrev;
        $this->instanse->orden = $pOrden;
        $this->instanse->fechaini = $pFechaini;
        $this->instanse->fechafin = $pFechafin;
        try {
            $this->instanse->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC24', $ee);

            return false;
        }
    }

    /**
     * Busacr los datos del tipo de calificador.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */
    public function buscarNomtipocalificador($limit, $start, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomTipoCalificador')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomTipoCalificador')
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
     * contar la cantidad de tipo de calificador.
     *
     * @return unknown
     */
    public function contNomtipocalificador($denom = false)
    {
        try {
            if ($denom) {
                $denom = strtolower($denom);
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->from('NomTipoCalificador')
                    ->where("LOWER(denominacion) like '%$denom%'")
                    ->execute()
                    ->count();
                return $result;
            } else {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->from('NomTipoCalificador')
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

    public function usandoNomTipocalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtipocalificador')
                ->from('NomCalificadorCargo c')
                ->where("c.idtipocalificador ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function existeNomtipocalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtipocalificador')
                ->from('NomTipoCalificador')
                ->where("idtipocalificador ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarNomtipocalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idtipocalificador')
                ->from('NomTipoCalificador')
                ->where("idtipocalificador = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * buscar el proximo id a insertar.
     *
     * @return unknown
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idtipocalificador) as maximo')
            ->from('NomTipoCalificador a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }
}

?>
