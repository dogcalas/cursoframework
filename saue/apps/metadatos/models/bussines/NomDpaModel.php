<?php
class NomDpaModel extends ZendExt_Model
{

    public function NomDpaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomDpa();
    }

    public function insertarNomDpa($idpadre, $denominacion, $abreviatura, $idtipodpa)
    {

        $this->instance->iddpa = $this->buscaridproximo();
        $this->instance->idpadredpa = $idpadre;
        $this->instance->denominacion = $denominacion;
        $this->instance->abreviatura = $abreviatura; //$pEssueldo;
        $this->instance->idtipodpa = $idtipodpa; //$pEssueldo;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    public function modificarNomDpa($iddpa, $denominacion, $abreviatura, $idtipodpa)
    {

        $this->instance = $this->conn->getTable('NomDpa')->find($iddpa);

        $this->instance->idpadredpa = $idpadre;
        $this->instance->denominacion = $denominacion;
        $this->instance->abreviatura = $abreviatura;
        $this->instance->idtipodpa = $idtipodpa;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    /**
     * Buscar los nomencladores de cargo civil
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomDpa($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('*')
                ->from('NomDpa ')
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

    public function cantNomDpa()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('*')
                ->from('NomDpa ')
                ->execute()->count();

            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Verificar si existe un nomenclador
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomDpa($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('iddpa')
                ->from('NomDpa')
                ->where("iddpa ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Eliminar un elemento del nomenclador
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarNomDpa($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('iddpa')
                ->from('NomDpa')
                ->where("iddpa = '$pId'")
                ->execute();
            return ($result != 0);
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
        $result = $query->select('max(a.iddpa) as maximo')
            ->from('NomDpa a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    //-- trabajo especifico con el Arbol de DPA


    public function obtenerHijos($idpadre)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        $sqlPadre = ($idpadre) ? " a.idpadredpa=$idpadre AND a.iddpa<>a.idpadredpa" : "a.iddpa=a.idpadredpa";;
        $result = $query->select("a.iddpa as id,CONCAT( a.denominacion, ' ' )   as text")
            ->from('NomDpa a')
            // ->innerJoin('a.NomTipodpa nt')
            ->where($sqlPadre)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();


        return $result;

    }


    //-- TRABAJO CON LOS TIPOS DE DPA

    public function insertarNomTipoDpa($denominacion, $orden)
    {

        $this->instance = new NomTipodpa();
        $this->instance->idtipodpa = $this->buscaridproximoToipoDpa();
        $this->instance->orden = $orden;
        $this->instance->denominacion = $denominacion;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {

            echo($ee->getMessage());
            return false;
        }
    }

    public function modificarNomTipoDpa($iddpa, $denominacion, $orden)
    {

        $this->instance = $this->conn->getTable('NomTipodpa')->find($iddpa);


        $this->instance->denominacion = $denominacion;
        $this->instance->orden = $orden; //$pEssueldo;\
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            echo($ee->getMessage());
            return false;
        }
    }

    /**
     * Buscar los nomencladores de cargo civil
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomTipoDpa($limit = 10, $start = 0, $predecesor = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($predecesor) {
                $result = $q->select('*')
                    ->from('NomTipodpa ')
                    ->where("idtipodpa='$predecesor'")

                    ->execute()
                    ->toArray();
                $orden = $result[0]['orden'];

                $result = $q->select('*')
                    ->from('NomTipodpa ')
                    ->where("( orden - 1 )='$orden'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderBy('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {


                $result = $q->select('*')
                    ->from('NomTipodpa ')
                    ->limit($limit)
                    ->offset($start)
                    ->orderBy('orden')
                    ->execute()
                    ->toArray();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantNomTipoDpa()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('*')
                ->from('NomTipodpa ')
                ->execute()->count();

            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Verificar si existe un nomenclador
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomTipoDpa($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtipodpa')
                ->from('NomTipodpa')
                ->where("idtipodpa ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Eliminar un elemento del nomenclador
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarNomTipoDpa($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idtipodpa')
                ->from('NomTipodpa')
                ->where("idtipodpa = '$pId'")
                ->execute();
            return ($result != 0);
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
    public function buscaridproximoToipoDpa()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idtipodpa) as maximo')
            ->from('NomTipodpa a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function usadoNomTipoDpa($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomDpa')
                ->where("idtipodpa ='$pId'")
                ->execute()
                ->count();


            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

}

?>
