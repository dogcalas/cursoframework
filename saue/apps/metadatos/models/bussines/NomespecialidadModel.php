<?php
class NomespecialidadModel extends ZendExt_Model
{

    public function NomespecialidadModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomEspecialidad();
    }

    function insertarNomespecialidad($denesp, $abrev, $codesp, $orden, $fini, $ffin)
    {

        $this->instance->idespecialidad = $this->buscaridproximo();
        $this->instance->denespecialidad = $denesp;
        $this->instance->abrevespecialidad = $abrev;
        $this->instance->codespecialidad = $codesp;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fini;
        $this->instance->fechafin = $ffin;
        try {
            $this->instance->save();
            return true;

        } catch (Exception $ee) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());
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
    public function buscarNomespecialidad($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomEspecialidad ')
                ->limit($limit)
                ->offset($start)
                ->orderby('orden')
                ->execute()
                ->toArray();
            return $result;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function listarNomespecialidad($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomEspecialidad ')
                ->limit($limit)
                ->offset($start)
                ->orderby('orden')
                ->setHydrationMode(Doctrine::HYDRATE_NONE)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function cantNomespecialidad()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomEspecialidad ')
                ->execute()->count();

            return $result;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function existeNomespecialidadId($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idespecialidad')->from('NomEspecialidad')->where("idespecialidad ='$pId'")->execute()->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    public function usadoNomespecialidadId($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatEstructura')
                ->where("idespecialidad ='$pId'")
                ->execute()
                ->count();
            $consulta1 = $q->from('DatEstructuraop')
                ->where("idespecialidad ='$pId'")
                ->execute()
                ->count();
            $consulta2 = $q->from('DatCargo')
                ->where("idespecialidad ='$pId'")
                ->execute()
                ->count();
            return (($consulta + $consulta1 + $consulta2) != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    public function eliminarNomespecialidad($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idespecialidad')->from('NomEspecialidad')->where("idespecialidad = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
        $result = $query->select('max(a.idespecialidad) as maximo')
            ->from('NomEspecialidad a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    function modificarNomespecialidad($pidesp, $pdenesp, $pabrev, $pcodesp, $porden, $pfini, $pffin)
    {

        $this->instance = $this->conn->getTable('NomEspecialidad')->find($pidesp);
        $this->instance->denespecialidad = $pdenesp;
        $this->instance->abrevespecialidad = $pabrev;
        $this->instance->codespecialidad = $pcodesp;
        $this->instance->orden = $porden;
        $this->instance->fechaini = $pfini;
        $this->instance->fechafin = $pffin;
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
