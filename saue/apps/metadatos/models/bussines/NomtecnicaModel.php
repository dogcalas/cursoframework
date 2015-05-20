<?php
class NomtecnicaModel extends ZendExt_Model
{
    public function NomtecnicaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomTecnica();
    }

    public function existeNomtecnicaDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtecnica')
                ->from('NomTecnica')
                ->where("dentecnica ='$denom' or abrevtecnica='$abrev'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function existeNomtecnica($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtecnica')
                ->from('NomTecnica')
                ->where("idtecnica ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function usandoTecnica($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $consulta = $q->from('DatTecnica')
                ->where("idtecnica ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    public function buscarNomtecnica($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomTecnica')
                    ->where("LOWER(dentecnica) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;
            } else {
                $result = $q->from('NomTecnica')
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

    public function cantNomtecnica($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomTecnica')
                    ->where("LOWER(dentecnica) like '%$denom%'")
                    ->execute()
                    ->count();
                return $result;
            } else {
                $result = $q->from('NomTecnica')
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

    public function insertarNomtecnica($pcodtecnica, $dentecnica, $pabrevtecnica, $pvaplantilla, $pOrden, $pFechaini, $pFechafin)
    {

        //$this->instance->idtecnica			= $this->buscaridproximo();
        $this->instance->codtecnica = $pcodtecnica;
        $this->instance->abrevtecnica = $pabrevtecnica;
        $this->instance->dentecnica = $dentecnica;
        $this->instance->vaplantilla = $pvaplantilla;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
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
        $result = $query->select('max(a.idtecnica) as maximo')
            ->from('NomTecnica a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function modificarNomtecnica($idpref, $pcodtecnica, $dentecnica, $pabrevtecnica, $pvaplantilla, $pOrden, $pFechaini, $pFechafin)
    {

        $this->instance = $this->conn->getTable('NomTecnica')->find($idpref);
        $this->instance->codtecnica = $pcodtecnica;
        $this->instance->abrevtecnica = $pabrevtecnica;
        $this->instance->dentecnica = $dentecnica;
        $this->instance->vaplantilla = $pvaplantilla;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
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

    public function eliminarNomtecnica($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idtecnica')->from('NomTecnica')->where("idtecnica = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

}

?>
