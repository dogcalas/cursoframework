<?php
class NomgradomilitarModel extends ZendExt_Model
{
    public function NomgradomilitarModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomGradomilit();
    }

    public function insertarNomGradomilitar($idgsubcateg, $dengradomilit, $abrevgradomilit, $esmarina, $homologoterr, $anterior, $sucesor, $orden, $fechaini, $fechafin)
    {

        //$this->instance->idgradomilit			= $this->buscaridproximo();
        $this->instance->idgsubcateg = $idgsubcateg;
        $this->instance->dengradomilit = $dengradomilit;
        $this->instance->abrevgradomilit = $abrevgradomilit;
        $this->instance->esmarina = $esmarina;
        $this->instance->homologoterr = $homologoterr;
        $this->instance->anterior = $anterior;
        $this->instance->sucesor = $sucesor;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            //echo ($ee->getMessage());
            throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    public function usandoGradoMtar($pId)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $consulta = $q->select('count(idgradomilit) as cont')
                ->from('DatCargomtar')
                ->where("idgradomilit ='$pId'")
                ->execute();
            $consulta1 = $q->select('count(idgradomilit) as cont')
                ->from('NomGradomilit')
                ->where("anterior ='$pId'   or  sucesor='$pId'")
                ->execute();
            /*echo'<pre>';
            print_r($consulta);
            print_r($consulta1);	die();*/
            $sum = $consulta[0]['cont'] + $consulta1[0]['cont'];
            return ($sum != 0) ? true : false;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    public function modificarNomGradomilitar($idgradomilitar, $idgsubcateg, $dengradomilit, $abrevgradomilit, $esmarina, $homologoterr, $anterior, $sucesor, $orden, $pFechaini, $pFechafin)
    {
        $this->instance = $this->conn->getTable('NomGradomilit')->find($idgradomilitar);
        $this->instance->idgsubcateg = $idgsubcateg;
        $this->instance->dengradomilit = $dengradomilit;
        $this->instance->abrevgradomilit = $abrevgradomilit;
        $this->instance->esmarina = $esmarina;
        $this->instance->homologoterr = $homologoterr;
        $this->instance->anterior = $anterior;
        $this->instance->sucesor = $sucesor;
        $this->instance->orden = $orden;
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
     * Buscar cargos militares que existen
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarGradomtar($limit = 1000, $start = 0, $denom = false)
    {


        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('nm.*,a.*,s.*,ct.*')
                    ->from('NomGradomilit nm')
                    ->leftJoin('nm.NomGradomilit a ')
                    ->leftJoin('nm.Sucesor s ')
                    ->innerJoin('nm.NomGsubcateg ct ')
                    ->where("LOWER(nm.dengradomilit) like '%$denom%'")
                    //->where(" nm.anterior = a.idgradomilit and  nm.sucesor = s.idgradomilit ")
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute();
                return $result;
            } else {
                $result = $q->select('nm.*,a.*,s.*,ct.*')
                    ->from('NomGradomilit nm')
                    ->leftJoin('nm.NomGradomilit a ')
                    ->leftJoin('nm.Sucesor s ')
                    ->innerJoin('nm.NomGsubcateg ct ')
                    //->where(" nm.anterior = a.idgradomilit and  nm.sucesor = s.idgradomilit ")
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->execute();
                return $result;
            }

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantGradomtar($denom = false)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('count(nm.idgradomilit) as count ')
                    ->from('NomGradomilit nm')
                    ->where("LOWER(nm.dengradomilit) like '%$denom%'")
                    //->leftJoin('nm.NomGradomilit a ')
                    //->leftJoin('nm.Sucesor s ')
                    //->innerJoin('nm.NomGsubcateg ct ')
                    //->where("nm.anterior=a.idgradomilit  and  nm.sucesor=s.idgradomilit ")
                    ->execute()
                    ->toArray();
                $result1 = $result[0]['count'];
                return $result1;
            } else {
                $result = $q->select('count(nm.idgradomilit) as count ')
                    ->from('NomGradomilit nm')
                    //->leftJoin('nm.NomGradomilit a ')
                    //->leftJoin('nm.Sucesor s ')
                    //->innerJoin('nm.NomGsubcateg ct ')
                    //->where("nm.anterior=a.idgradomilit  and  nm.sucesor=s.idgradomilit ")
                    ->execute()
                    ->toArray();
                $result1 = $result[0]['count'];
                return $result1;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Verificar si existe un grado militar por denominacion o abreviatura
     * @author Rolando Ramos Morales
     * @param String $denom
     * @param String $abrev
     * @return boolean
     */
    public function existeGradoDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idgradomilit')
                ->from('NomGradomilit')
                ->where("dengradomilit ='$denom'or abrevgradomilit='$abrev'")
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
     * Verificar si existe en la base de datos
     *
     * @param int $pId
     * @return bool
     */
    public function existeGrado($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idgradomilit')
                ->from('NomGradomilit')
                ->where("idgradomilit ='$pId'")
                ->execute()
                ->count();

            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarGradomtar($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idgradomilit')
                ->from('NomGradomilit')
                ->where("idgradomilit = '$pId'")
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
        $result = $query->select('max(a.idgradomilit) as maximo')
            ->from('NomGradomilit a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }


}

?>
