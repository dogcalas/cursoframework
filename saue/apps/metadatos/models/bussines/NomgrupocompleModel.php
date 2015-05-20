<?php
class NomgrupocompleModel extends ZendExt_Model
{

    public function NomgrupocompleModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomGrupocomple();
    }

    /**
     * insertar grupo de complejidad.
     *
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pIdescalasalarial
     * @param unknown_type $pSalarioescala
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */
    public function insertarNomgrupocomple($pDenom, $pAbrev, $pIdescalasalarial, $pSalarioescala, $pOrden, $pFini, $pFfin)
    {

        //$this->instance->idgrupocomplejidad	= $this->buscaridproximo();
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        $this->instance->idescalasalarial = $pIdescalasalarial;
        $this->instance->salarioescala = $pSalarioescala;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;


        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    /**
     * Modificar un grupo escala.
     *
     * @param unknown_type $idgrupocomple
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pIdescalasalarial
     * @param unknown_type $pSalarioescala
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */
    public function modificarNomgrupocomple($idgrupocomple, $pDenom, $pAbrev, $pIdescalasalarial, $pSalarioescala, $pOrden, $pFini, $pFfin)
    {
        $this->instance = $this->conn->getTable('NomGrupocomple')->find($idgrupocomple);
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        //$this->instance->idescalasalarial	= $pIdescalasalarial;
        //$this->instance->salarioescala		= $pSalarioescala;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;

        try {
            $this->instance->save();
            return true;

        } catch (Exception $e) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$e->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function existeNomgrupocompleDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idgrupocomplejidad')
                ->from('NomGrupocomple')
                ->where("denominacion ='$denom' or abreviatura='$abrev' ")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * existe un grupo de complejidad.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function existeNomgrupocomple($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idgrupocomplejidad')
                ->from('NomGrupocomple')
                ->where("idgrupocomplejidad ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Enter description here...
     *
     * @param id nom cargo civil.
     * @return array
     */
    public function nomNomgrupoc($pId)
    {

        try {
            if ($pId == '') {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result1 = $q->select("g.*")
                    // ->from('NomCargocivil c')
                    ->from('NomGrupocomple g')
                    ->execute()
                    ->toArray();
                /* echo '<pre>';
                 print_r($result1);
                 die(); */


                return $result1;
            } else {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select("g.*")
                    ->from(' NomGrupocomple g')
                    ->innerJoin('g.NomCargocivil c')
                    ->where("c.idcargociv ='$pId'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function usandoNomgrupocomplejidad($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomCargocivil')
                ->where("idgrupocomplejidad ='$pId'")
                ->execute()
                ->count();
            $consulta1 = $q->from('NomSalario')
                ->where("idgrupocomplejidad ='$pId'")
                ->execute()
                ->count();
            return (($consulta1 + $consulta) > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     * Eliminar grupo complejidad.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function eliminarNomgrupocomple($pId)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idgrupocomplejidad')
                ->from('NomGrupocomple')
                ->where("idgrupocomplejidad = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Buscar los grupos de complejidad
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */
    public function buscarNomgrupocomple($limit = 100, $start = 0, $denom = false)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = //$q->select("g.*,e.*")
                    $q->select("g.*")
                        ->from('NomGrupocomple g')
                        ->where("lower(g.denominacion) like '%$denom%'")
                        //->innerJoin('g.NomEscalasalarial e')
                        ->limit($limit)
                        ->offset($start)
                        ->orderby('orden')
                        ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                        ->execute();
                return $result;
            } else {

                $result = //$q->select("g.*,e.*")
                    $q->select("g.*")
                        ->from('NomGrupocomple g')
                        //->innerJoin('g.NomEscalasalarial e')
                        ->limit($limit)
                        ->offset($start)
                        ->orderby('orden')
                        ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                        ->execute();
                return $result;
            }
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }


    }

    /**
     * contar la cantidad de grupo de complejidad
     *
     * @return unknown
     */

    public function contNomgrupocomple($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->from('NomGrupocomple g')
                    ->innerJoin('g.NomEscalasalarial e')
                    ->where("lower(g.denominacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->from('NomGrupocomple g')
                    ->innerJoin('g.NomEscalasalarial e')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(g.idgrupocomplejidad) as maximo')
            ->from('NomGrupocomple g')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }
}

?>
