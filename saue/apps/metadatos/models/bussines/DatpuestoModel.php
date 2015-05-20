<?php
class DatpuestoModel extends ZendExt_Model
{


    public function DatpuestoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatPuesto();
    }

    /**
     * Insertar un puesto de trabajo
     *
     * @param int $idcargo
     * @param string $denominacion
     * @param string $abreviatura
     * @param string $habilidades
     * @param string $condiciones
     * @param string $acciones
     * @param string $riesgos
     * @return bool
     */
    public function insertarPuesto($idcargo, $denominacion, $abreviatura, $habilidades, $condiciones, $acciones, $riesgos)
    {

        $this->instance->idpuesto = $this->buscaridproximo();
        $this->instance->idcargo = $idcargo;
        $this->instance->denominacion = $denominacion;
        $this->instance->abreviatura = $abreviatura;
        $this->instance->habilidades = $habilidades;
        $this->instance->condiciones = $condiciones;
        $this->instance->acciones = $acciones;
        $this->instance->riesgos = $riesgos;

        try {
            $this->instance->save();
            return true;
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
        $result = $query->select('max(a.idpuesto) as maximo')
            ->from('DatPuesto a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /**
     * buscar todos los puestos que esten en la tabla de cargos
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarPuestos($limit = 10, $start = 0, $idcargo)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('DatPuesto ')
                ->where("idcargo = '$idcargo'")
                ->limit($limit)
                ->offset($start)
                ->execute()
                ->toArray();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     * eliminar un puesto
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarPuesto($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idpuesto')->from('DatPuesto')->where("idpuesto = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     *Modificar un puesto de trabajo determinado
     *
     * @param unknown_type $pidpuesto
     * @param unknown_type $pidcargo
     * @param unknown_type $pdenominacion
     * @param unknown_type $pabreviatura
     * @param unknown_type $phabilidades
     * @param unknown_type $pcondiciones
     * @param unknown_type $pacciones
     * @param unknown_type $priesgos
     * @return unknown
     */
    public function modificarPuesto($pidpuesto, $pdenominacion, $pabreviatura, $phabilidades, $pcondiciones, $pacciones, $priesgos)
    {

        $this->instance = $this->conn->getTable('DatPuesto')->find($pidpuesto);
        $this->instance->denominacion = $pdenominacion;
        $this->instance->abreviatura = $pabreviatura;
        $this->instance->habilidades = $phabilidades;
        $this->instance->condiciones = $pcondiciones;
        $this->instance->acciones = $pacciones;
        $this->instance->riesgos = $priesgos;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }
}

?>