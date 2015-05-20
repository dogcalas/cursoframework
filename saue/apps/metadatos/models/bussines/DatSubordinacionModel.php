<?php
class DatSubordinacionModel extends ZendExt_Model
{


    public function DatSubordinacionModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatSubordinacion();
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
    public function  insertarSubordinacion($idhijo, $idpadre, $idnomsubordinacion)
    {

        $this->instance->idhijo = $idhijo;
        $this->instance->idpadre = $idpadre;
        $this->instance->idnomsubordinacion = $idnomsubordinacion;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
     * eliminar un puesto
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarsubordinacion($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idhijo')->from('DatSubordinacion')->where("idhijo = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
    /*public function cambiarsubordinacion($idhija, $idpadre, ra, $phabilidades, $pcondiciones, $pacciones, $priesgos)
    {

        $this->instance 					= $this->conn->getTable('DatPuesto')->find($pidpuesto);
        $this->instance->denominacion		= $pdenominacion;
        $this->instance->abreviatura		= $pabreviatura;
        $this->instance->habilidades		= $phabilidades;
        $this->instance->condiciones		= $pcondiciones;
        $this->instance->acciones			= $pacciones;
        $this->instance->riesgos			= $priesgos;

        try
        {
            $this->instance->save();
            return true;
        }
        catch (Doctrine_Exception $ee)
        {
            if(DEBUG_ERP)
                die(__FILE__.' '.__LINE__.' '.$ee->getMessage());

            return false;
        }

    } */
}

?>