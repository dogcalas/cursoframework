<?php
class NomorganoModel extends ZendExt_Model
{

    public function NomorganoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomOrgano();
    }

    public function insertarNomorgano($pDenorg, $pAbreorg, $pIdnestr, $pIdesp, $pOrden, $pFini, $pFfin, $idnomeav)
    {
        //$idorga								= $this->buscaridproximo();
        //$this->instance->idorgano			= $idorga;
        $this->instance->denorgano = $pDenorg;
        $this->instance->abrevorgano = $pAbreorg;
        $this->instance->idnivelestr = $pIdnestr;
        $this->instance->idespecialidad = $pIdesp;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;
        $this->instance->idnomeav = $idnomeav;

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

    function insertarNomOrganoExtendido($idorgano, $idnomeav)
    {

        $this->instance = new NomOrganoExt();

        $this->instance->idorgano = $idorgano;
        $this->instance->idnomeav = $idnomeav;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }

    }

    function insertarNomOrganoInt()
    {

    }

    public function insertarOrganoExt($idOrg, $tabla)
    {
        $this->instance->idorgano = $this->buscaridproximo();
        $this->instance->denorgano = $pDenorg;
        $this->instance->abrevorgano = $pAbreorg;
        $this->instance->idnivelestr = $pIdnestr;
        $this->instance->idespecialidad = $pIdesp;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;


        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function CantElementos($denom = false)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);
        if ($denom) {
            $denom = strtolower($denom);


            $result1 = $q->select('o.*,n.*,nom.*')
                ->from('NomOrgano o')
                //->innerJoin('o.NomEspecialidad e ')
                ->innerJoin('o.NomNivelestr n ')
                ->innerJoin('o.NomNomencladoreavestruc nom ')
                ->where("lower(o.denorgano) like '%$denom%'")
                ->execute()->count();


            return ($result1);

        } else {


            $result1 = $q->select('o.*,n.*,nom.*')
                ->from('NomOrgano o')
                //->innerJoin('o.NomEspecialidad e ')
                ->innerJoin('o.NomNivelestr n ')
                ->innerJoin('o.NomNomencladoreavestruc nom ')
                ->execute()->count();


            return ($result1);

        }
    }

    public function insertarOrganoInt($idOrg, $tabla)
    {
        $this->instance->idorgano = $this->buscaridproximo();
        $this->instance->denorgano = $pDenorg;
        $this->instance->abrevorgano = $pAbreorg;
        $this->instance->idnivelestr = $pIdnestr;
        $this->instance->idespecialidad = $pIdesp;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;


        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function buscarNomorgano($limit = 10, $start = 0, $idnomeav = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q
                ->from('NomOrgano o')
                ->where("o.idnomeav='$idnomeav' ")
                ->limit($limit)
                ->offset($start)
                ->orderby('o.orden')
                ->execute();

            $respuesta = array();
            foreach ($result as $r) {
                $respuesta[] = array($r['idorgano'], $r['abrevorgano']);
            }
            return $respuesta;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }


    public function buscarorganos($limit = 10, $start = 0, $denom = false)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);
                $result1 = $q->select('o.*,n.*,nom.*')
                    ->from('NomOrgano o')
                    //->innerJoin('o.NomEspecialidad e ')
                    ->innerJoin('o.NomNivelestr n ')
                    ->innerJoin('o.NomNomencladoreavestruc nom ')
                    ->where("LOWER(o.denorgano) like '%$denom%'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('o.orden')
                    ->execute();

            } else {
                $result1 = $q->select('o.*,n.*,nom.*')
                    ->from('NomOrgano o')
                    //->innerJoin('o.NomEspecialidad e ')
                    ->innerJoin('o.NomNivelestr n ')
                    ->innerJoin('o.NomNomencladoreavestruc nom ')

                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('o.orden')
                    ->execute();
            }

            return ($result1);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

        }
    }

    /**
     * Comprobar si existe un organo por denominacion o abreviatura
     * @author Rolando Ramos Morales
     * @param String $Denom Denominacion del organo
     * @param String $Abrev Abreviatura del organo
     * @return boolean
     */
    public function existeNomorganoDenomAbrev($Denom, $Abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idorgano')->from('NomOrgano')
                ->where("denorgano ='$Denom' or abrevorgano='$Abrev'")
                ->execute()
                ->count();
            return $consulta == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }


    public function existeNomorgano($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idorgano')->from('NomOrgano')
                ->where("idorgano ='$pId'")
                ->execute()
                ->count();
            return $consulta == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

    public function getID($id, $tabla)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $consulta = $q->select('o.idorgano as idb')
                ->from('NomOrgano o')
                ->where("o.denorgano ='$denominacion' AND o.idnomeav='$tabla'")
                ->execute()
                ->toArray();
            $respuesta = isset($consulta[0]['idb']) ? $consulta[0]['idb'] : false;

            return $respuesta;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

    public function eliminarNomorgano($pId)
    {
        try {
            $this->instance = new NomOrgano();
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idorgano')->from('NomOrgano')
                ->where("idorgano = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
        $result = $query->select('max(a.idorgano) as maximo')
            ->from('NomOrgano a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    public function modificarNomorgano($pId, $pDenorg, $pAbreorg, $pIdnestr, $pIdesp, $pOrden, $pIdeav, $pFini, $pFfin)
    {
        try {

            $this->instance = $this->conn->getTable('NomOrgano')->find($pId);
            $this->instance->denorgano = $pDenorg;
            $this->instance->abrevorgano = $pAbreorg;
            $this->instance->idnivelestr = $pIdnestr;
            $this->instance->idespecialidad = $pIdesp;
            $this->instance->orden = $pOrden;
            $this->instance->idnomeav = $pIdeav;
            $this->instance->fechaini = $pFini;
            $this->instance->fechafin = $pFfin;

            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    function verificarUsado($idorgano)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('*')
            ->from('DatEstructura a')
            ->where("idorgano='$idorgano'")
            ->execute()
            ->count();
        $result1 = $query->select('*')
            ->from('DatEstructuraop a')
            ->where("idorgano='$idorgano'")
            ->execute()
            ->count();
        return $result + $result1;
    }
}

?>
