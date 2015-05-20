<?php
class NomprefijoModel extends ZendExt_Model
{
    public function NomprefijoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomPrefijo();
    }

    public function existeNomprefijo($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idprefijo')
                ->from('NomPrefijo')
                ->where("idprefijo ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function usadoNomprefijo($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatEstructura')
                ->where("idprefijo ='$pId'")
                ->execute()
                ->count();
            $consulta1 = $q->from('DatEstructuraop')
                ->where("idprefijo ='$pId'")
                ->execute()
                ->count();
            $consulta2 = $q->from('DatCargo')
                ->where("idprefijo ='$pId'")
                ->execute()
                ->count();

            return (($consulta + $consulta1 + $consulta2) != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscarNomprefijo($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomPrefijo')
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

    public function cantNomprefijo()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->from('NomPrefijo')
                ->execute()
                ->count();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function insertarNomprefijo($prefijo, $desclugar, $pOrden, $fechaini, $fechafin)
    {

        //$this->instance->idprefijo			= $this->buscaridproximo();
        $this->instance->prefijo = $prefijo;
        $this->instance->desclugar = $desclugar;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idprefijo) as maximo')
            ->from('NomPrefijo a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function eliminarNomprefijo($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idprefijo')
                ->from('NomPrefijo')
                ->where("idprefijo = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function modificarNomprefijo($idprefijo, $prefijo, $desclugar, $pOrden, $fechaini, $fechafin)
    {
        $this->instance = $this->conn->getTable('NomPrefijo')->find($idprefijo);
        $this->instance->prefijo = $prefijo;
        $this->instance->desclugar = $desclugar;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }
}

?>
