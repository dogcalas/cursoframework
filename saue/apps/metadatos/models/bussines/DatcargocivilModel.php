<?php
class DatcargocivilModel extends ZendExt_Model
{
    /**
     * Enter description here...
     *
     * @return DatcargocivilModel
     */
    public function DatcargocivilModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatCargocivil();
    }

    public function insertarCargocivil($idcargo, $idcargocivil, $idcategcivil, $idsalario, $idgrupocomple, $idescalasalarial, $idclasificacion, $modificable)

    {

        $this->instance->idcargo = $idcargo;
        $this->instance->idcargociv = $idcargocivil;
        $this->instance->idcategcivil = $idcategcivil;
        $this->instance->idsalario = $idsalario;
        $this->instance->idgrupocomple = $idgrupocomple;
        $this->instance->idescalasalarial = $idescalasalarial;
        $this->instance->idclasificacion = $idclasificacion;
        $this->instance->modificable = $modificable;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscarCargocivil($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $resultTotal = $this->conn->getTable('DatCargocivil')->findAll();
            $result = $q->from('DatCargocivil ')->limit($limit)->offset($start)->execute();
            $resultado = $result->toArray();
            $solve = array('cant' => $resultTotal->count(), 'datos' => $resultado);
            return $solve;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /*public function buscarCargocivilId( $pId){
        try{
             $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $resultTotal 	= $this->conn->getTable ('DatCargocivil')->findAll ();
            $result 		= $q->select('idcargociv')->from('DatCargocivil ')->where("idcargociv = '$pId'")->execute();
            $resultado 		= $result->toArray ();
            $solve 			= array ('cant' => $resultTotal->count (), 'datos' => $resultado);
            return $solve;

        }catch(Doctrine_Exception $e)
        {
            return false;
        }
    }*/
    public function existeCargocivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargociv')->from('DatCargocivil')->where("idcargociv ='$pId'")->execute()->count();
            return $consulta == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarCargocivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcargociv')->from('DatCargocivil')->where("idcargociv = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Mostrar los cargos civiles asicioados a la informacion de los cargos generales
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getDatosCiviles($limit = 10, $start = 0)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $resultTotal = $this->conn->getTable('DatCargocivil')->findAll();
        $result = $q->select("d.idcargocivil,d.idcargo,d.idcargociv,d.idcategcivil,d.idgrupocomple,d.idescalasalarial,d.idsalario,d.idclasificacion,
							n.idcargo,n.idestructuraop,n.idespecialidad,n.ctp,n.ctg,n.idtipocifra,n.idprefijo,n.orden,n.estado,n.fechaini,n.fechafin,n.salario,n.idtecnica,n.idgrupocomplejidad,n.idmodulo")
            ->from('DatCargocivil d')
            ->innerJoin('d.DatCargo n ')
            ->limit($limit)
            ->offset($start)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        $solve = array('cant' => $resultTotal->count(), 'datos' => $result);
        return $solve;
    }

    /**
     * Modifica un cargo civil
     *
     * @param unknown_type $pidcargocivil
     * @param unknown_type $pidcategcivil
     * @param unknown_type $psalario
     * @return unknown
     */
    public function modificarCargocivil($idcargo, $pidcargocivil, $pidcategcivil, $psalario, $idgrupocomple, $idescalasalarial, $idclasificacion, $modificable)
    {
        $this->instance = $this->conn->getTable('DatCargocivil')->find($idcargo);
        $this->instance->idcargo = $idcargo;
        $this->instance->idcargociv = $pidcargocivil;
        $this->instance->idcategcivil = $pidcategcivil;
        $this->instance->idsalario = $psalario;
        $this->instance->idgrupocomple = $idgrupocomple;
        $this->instance->idescalasalarial = $idescalasalarial;
        $this->instance->idclasificacion = $idclasificacion;
        $this->instance->modificable = $modificable;
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