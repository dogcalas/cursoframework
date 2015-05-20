<?php
class NomcategcivilModel extends ZendExt_Model
{

    public function NomcategcivilModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCategcivil();
    }

    public function insertarNomcategoriacivil($pDenocatcivil, $pAbrecatcivil, $pEssueldo, $pOrden, $pFechaini, $pFechafin)
    {

        //$this->instance->idcategcivil		= $this->buscaridproximo();
        $this->instance->dencategcivil = $pDenocatcivil;
        $this->instance->abrevcategcivil = $pAbrecatcivil;
        $this->instance->essueldo = $pEssueldo;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    public function modificarNomcategoriacivil($idcat, $pDenocatcivil, $pAbrecatcivil, $pEssueldo, $pOrden, $pFechaini, $pFechafin)
    {

        $this->instance = $this->conn->getTable('NomCategcivil')->find($idcat);

        $this->instance->dencategcivil = $pDenocatcivil;
        $this->instance->abrevcategcivil = $pAbrecatcivil;
        $this->instance->essueldo = $pEssueldo;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
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
    public function buscarNomcategcivil($limit, $start, $denom = false)
    {
        try {

            if ($denom) {
                $denom = strtolower($denom);
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('*')
                    ->from('Nomcategcivil ')
                    ->where("lower(dencategcivil) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute()
                    ->toArray();
                return $result;


            } else {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('*')
                    ->from('Nomcategcivil ')
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

    public function cantNomcategcivil($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('*')
                    ->from('Nomcategcivil ')
                    ->where("lower(dencategcivil) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->select('*')
                    ->from('Nomcategcivil ')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Para comprovar si existe una categoria civil por denominacion o abreviatura
     * @author Rolando Ramos Morales
     * @param String $denom denominacion de la categoria civil
     * @param String $abrev abreviatura  de la categoria civil
     * @return boolean
     */

    public function  existeNomcategcivilDenomAbrev($denom, $abrev)
    {
        $q = Doctrine_Query::create();

        $consulta = $q->select('idcategcivil')
            ->from('Nomcategcivil')
            ->where("dencategcivil ='$denom'or abrevcategcivil='$abrev' ")
            ->execute()
            ->count();
        return ($consulta != 0);
    }

    /**
     * Verificar si existe un nomenclador
     *
     * @param int $pId
     * @return bool
     */
    public function existeNomcategcivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcategcivil')
                ->from('Nomcategcivil')
                ->where("idcategcivil ='$pId'")
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
    public function eliminarNomcategcivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcategcivil')->from('Nomcategcivil')->where("idcategcivil = '$pId'")->execute();
            return ($result != 0);
        } catch (Doctrine_Exception $ee) {
            throw new ZendExt_Exception('EC05', $ee);
            //if(DEBUG_ERP)
            //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());

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
        $result = $query->select('max(a.idcategcivil) as maximo')
            ->from('Nomcategcivil a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function usandoCategoriaCvil($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomCargocivil')
                ->where("idcategcivil ='$pId'")
                ->execute()
                ->count();
            $consulta1 = $q->from('DatCargocivil')
                ->where("idcategcivil ='$pId'")
                ->execute()
                ->count();
            return (($consulta + $consulta1) != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }


}

?>
