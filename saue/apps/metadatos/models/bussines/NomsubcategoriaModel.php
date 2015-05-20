<?php
class NomsubcategoriaModel extends ZendExt_Model
{

    public function NomsubcategoriaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomGsubcateg();
    }


    /**
     * Insertar
     *
     * @param string $denesp
     * @param int $orden
     * @param int $fini
     * @param int $ffin
     * @return bool
     */
    function insertarNomsubcategoria($denesp, $orden, $fini, $ffin)
    {

        //$this->instance->idgsubcateg	= $this->buscaridproximo();
        $this->instance->densubcateg = $denesp;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fini;
        $this->instance->fechafin = $ffin;

        try {
            $this->instance->save();
            return true;

        } catch (Exception $e) {
            //echo($e->getMessage());
            if (DEBUG_ERP)
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
    public function buscarNomsubcategoria($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomGsubcateg ')
                    ->limit($limit)
                    ->offset($start)
                    ->where("LOWER(densubcateg) like '%$denom%'")
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            } else {
                $result = $q->from('NomGsubcateg ')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            }

            return $result;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function cantNomsubcategoria($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomGsubcateg ')
                    ->where("LOWER(densubcateg) like '%$denom%'")
                    ->execute()
                    ->count();
                return $result;
            } else {
                $result = $q->from('NomGsubcateg ')
                    ->execute()
                    ->count();
                return $result;

            }
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /**
     * Comprobar si existe alguna subcategoria por denom o abreviatura
     * @author Rolando Ramos Morales
     * @param $denom denominacion
     *
     * @return boolean
     *
     */
    public function existeNomsubcategoriaDenomAbrev($denom)
    {
        $q = Doctrine_Query::create();
        $consulta = $q->select('idgsubcateg')
            ->from('NomGsubcateg')
            ->where("densubcateg ='$denom'")
            ->execute()
            ->count();
        return ($consulta != 0);
    }

    /**
     * Existe
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomsubcategoriaId($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idgsubcateg')
                ->from('NomGsubcateg')
                ->where("idgsubcateg ='$pId'")
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
     * Eliminar
     *
     * @param int $pId
     * @return bool
     */
    public function eliminarNomSubcategoria($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idgsubcateg')
                ->from('NomGsubcateg')
                ->where("idgsubcateg = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            //if(DEBUG_ERP)
            //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
            throw new ZendExt_Exception('EC05', $ee);
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
        $result = $query->select('max(a.idgsubcateg) as maximo')
            ->from('NomGsubcateg a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    /**
     * Modificar una sub categoria del grado militar
     *
     * @param int $pidesp
     * @param int $denesp
     * @param int $orden
     * @param date $fini
     * @param date $ffin
     * @return bool
     */
    function modificarNomespecialidad($pidesp, $denesp, $orden, $fini, $ffin)
    {

        $this->instance = $this->conn->getTable('NomGsubcateg')->find($pidesp);
        $this->instance->densubcateg = $denesp;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fini;
        $this->instance->fechafin = $ffin;

        try {
            $this->instance->save();
            return true;
        } catch (Exception $e) {
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }
}

?>
