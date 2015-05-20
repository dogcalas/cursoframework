<?php
class DatcargomtarModel extends ZendExt_Model
{


    public function DatcargomtarModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatCargomtar();
    }

    /**
     * Inerta un cargo militar
     *
     * @param int $pIdcargo
     * @param int $pIdcargmtar
     * @param int $pIdgradomtar
     * @param float $pSalario
     * @param bool $pEscadmando
     * @return bool
     */
    public function insertarDatcargomtar($idcargo, $idcargmtar, $idgradomtar, $salario, $escadmando)
    {
        $this->instance->idcargo = $idcargo;
        $this->instance->idcargomilitar = $idcargmtar;
        $this->instance->idgradomilit = $idgradomtar;
        $this->instance->salario = $salario;
        $this->instance->escadmando = $escadmando;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage() . '-' . $idcargo . '-' . $idcargmtar . '-' . $idgradomtar . '-' . $salario . '-' . $escadmando);

            return false;
        }

    }

    /**
     * buscar todos los cargos que esten en la tabla de cargos
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarDatcargomtar($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $resultTotal = $this->conn->getTable('DatCargomtar')->findAll();
            $result = $q->from('DatCargomtar ')->limit($limit)->offset($start)->execute();
            $resultado = $result->toArray();
            $solve = array('cant' => $resultTotal->count(), 'datos' => $resultado);
            return $solve;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * verificar si existe un cargo en la base de datos
     *
     * @param int $pId
     * @return bool
     */
    public function existeDatcargomtar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargomilitar')->from('DatCargomtar')->where("idcargomilitar ='$pId'")->execute()->count();
            return $consulta == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * eliminar un cargo militar
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarDatcargomtar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcargomilitar')->from('DatCargomtar')->where("idcargomilitar = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Mostrar los cargos militares asicioados a la informacion de los cargos generales
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getDatosMilitares($limit = 10, $start = 0)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $resultTotal = $this->conn->getTable('DatCargomtar')->findAll();
        $result = $q->select("d.idcargomilitar,d.idgradomilit,d.salario,d.escadmando,d.idcargo,d.idnomcargomilitar,n.*")->from('DatCargomtar d')->innerJoin('d.DatCargo n ')->limit($limit)->offset($start)->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute();
        $solve = array('cant' => $resultTotal->count(), 'datos' => $result);
        return $solve;
    }

    /**
     * MOdificar cargo militar
     *
     * @param int $pidcargmtar
     * @param int $pidgradomtar
     * @param int $psalario
     * @param bool $pescadmando
     * @return unknown
     */
    public function modificarDatcargomtar($pidcargo, $pidcargmtar, $pidgradomtar, $psalario, $pescadmando)
    {
        $this->instance = $this->conn->getTable('DatCargomtar')->find($pidcargo);
        $this->instance->idcargomilitar = $pidcargmtar;
        $this->instance->idgradomilit = $pidgradomtar;
        $this->instance->salario = $psalario;
        $this->instance->escadmando = $pescadmando;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }

    }
}

?>