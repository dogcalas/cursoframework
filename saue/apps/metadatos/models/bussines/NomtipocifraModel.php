<?php
class NomtipocifraModel extends ZendExt_Model
{

    function NomtipocifraModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomTipocifra();

    }

    public function insertarNomtipocifra($pDentipoc, $pEscif, $pEdescarg, $pOrden, $pFechaini, $pFechafin)
    {
        //$this->instance->idtipocifra	= $this->buscaridproximo();
        $this->instance->dentipocifra = $pDentipoc;
        $this->instance->escifracargo = $pEscif;
        $this->instance->esdescargable = $pEdescarg;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$e->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function modificarNomtipocifra($idcifra, $pDentipoc, $pEscif, $pEdescarg, $pOrden, $pFechaini, $pFechafin)
    {
        $this->instance = $this->conn->getTable('NomTipocifra')->find($idcifra);
        $this->instance->dentipocifra = $pDentipoc;
        $this->instance->escifracargo = $pEscif;
        $this->instance->esdescargable = $pEdescarg;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$e->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function buscarNomtipocifra($limit = 10, $start = 0, $denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomTipocifra ')
                    ->where("lower(dentipocifra) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            } else {
                $result = $q->from('NomTipocifra ')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    public function cantNomtipocifra($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomTipocifra ')
                    ->where("lower(dentipocifra) like '%$denom%'")
                    ->execute()
                    ->count();

                return $result;
            } else {
                $result = $q->from('NomTipocifra ')
                    ->execute()
                    ->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    public function usandoNomtipocifra($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatCargo')
                ->where("idtipocifra ='$pId'")
                ->execute()
                ->count();
            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     *Comprobaj si existe un Tipo de cifra por la Denominacion
     * @param <type> $denom
     * @return <boolean>
     */
    public function existeNomtipocifraDenom($denom)
    {
        $q = Doctrine_Query::create($conn);

        $consulta = $q->select('idtipocifra')
            ->from('NomTipocifra')
            ->where("dentipocifra ='$denom'")
            ->execute()->count();
        return ($consulta != 0);
    }


    public function existeNomtipocifra($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtipocifra')->from('NomTipocifra')->where("idtipocifra ='$pId'")->execute()->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarNomtipocifra($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idtipocifra')->from('NomTipocifra')->where("idtipocifra = '$pId'")->execute();
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
        $result = $query->select('max(a.idtipocifra) as maximo')
            ->from('NomTipocifra a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }
}

?>
