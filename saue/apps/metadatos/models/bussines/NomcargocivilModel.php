<?php
class NomcargocivilModel extends ZendExt_Model
{
    public function NomcargocivilModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCargocivil();
    }

    public function insertarNomcargocivil($pDenocargcivil, $pAbrecargcivil, $pIdespecialidad, $pIdcatgocup, $pIdcatgcivil, $pOrden, $pFechaini, $pFechafin, $pCodigo, $pDesc, $pReq, $pIdcal, $pIdcomp, $pIdnivel)
    {

        //$this->instance->idcargociv			= $this->buscaridproximo();
        $this->instance->dencargociv = $pDenocargcivil;
        $this->instance->abrevcargociv = $pAbrecargcivil;
        $this->instance->idespecialidad = $pIdespecialidad;
        $this->instance->idcategocup = $pIdcatgocup;
        //$this->instance->idcategcivil		= $pIdcatgcivil;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        //$this->instance->version			= $pVersion;
        $this->instance->codigo = $pCodigo;
        $this->instance->descripcion = $pDesc;
        $this->instance->requisitos = $pReq;
        $this->instance->idcalificador = $pIdcal;
        $this->instance->idgrupocomplejidad = $pIdcomp;
        $this->instance->idnivelutilizacion = $pIdnivel;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo (__FILE__.' '.__LINE__.' '.$e->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    public function modificarNomcargocivil($pIdcargcivil, $pDenocargcivil, $pAbrecargcivil, $pIdespecialidad, $pIdcatgocup, $pIdcatgcivil, $pOrden, $pFechaini, $pFechafin, $pCodigo, $pDesc, $pReq, $pIdcal, $pIdcomp, $pIdnivel)
    {
        $this->instance = $this->conn->getTable('NomCargocivil')->find($pIdcargcivil);
        $this->instance->dencargociv = $pDenocargcivil;
        $this->instance->abrevcargociv = $pAbrecargcivil;
        $this->instance->idespecialidad = $pIdespecialidad;
        $this->instance->idcategocup = $pIdcatgocup;
        //$this->instance->idcategcivil		= $pIdcatgcivil;
        //	$this->instance->orden				= $pOrden;
        $this->instance->fechaini = $pFechaini;
        $this->instance->fechafin = $pFechafin;
        //$this->instance->version			= $pVersion;
        $this->instance->codigo = $pCodigo;
        $this->instance->descripcion = $pDesc;
        $this->instance->requisitos = $pReq;
        $this->instance->idcalificador = $pIdcal;
        $this->instance->idgrupocomplejidad = $pIdcomp;
        $this->instance->idnivelutilizacion = $pIdnivel;
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
     * Buscar los nomencladores de cargo civil
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarNomcargocivil($limit = 10, $start = 0, $denom = false)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select('cc.*,
		            					ct.idcategocup,ct.dencategocup,g.idgrupocomplejidad,g.denominacion,u.idnivelutilizacion,u.denominacion,cal.idcalificador,cal.denominacion,cc.idcalificador')
                    ->from('NomCargocivil cc')
                    //->innerJoin('cc.NomCategcivil np ')
                    ->innerJoin('cc.NomCategocup ct ')
                    ->innerJoin('cc.NomGrupocomple g')
                    ->innerJoin('cc.NomNivelUtilizacion u')
                    ->innerJoin('cc.NomCalificadorCargo cal')
                    ->where("lower(cc.dencargociv) like '%$denom%'")
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('cal.denominacion,cc.dencargociv')
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->execute();
            } else {
                $result = $q->select('cc.*,
		            					ct.idcategocup,ct.dencategocup,g.idgrupocomplejidad,g.denominacion,u.idnivelutilizacion,u.denominacion,cal.idcalificador,cal.denominacion,cc.idcalificador')
                    ->from('NomCargocivil cc')
                    //->innerJoin('cc.NomCategcivil np ')
                    ->innerJoin('cc.NomCategocup ct ')
                    ->innerJoin('cc.NomGrupocomple g')
                    ->innerJoin('cc.NomNivelUtilizacion u')
                    ->innerJoin('cc.NomCalificadorCargo cal')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('cal.denominacion,cc.dencargociv')
                    ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                    ->execute();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function cantNomcargocivil($denom = false)
    {
        try {
            if ($denom) {
                $denom = strtolower($denom);
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('cc.*,
	            		ct.idcategocup,ct.dencategocup,g.idgrupocomplejidad,g.denominacion,u.idnivelutilizacion,
	            		u.denominacion,cal.idclasificacion,cal.denominacion')
                    ->from('NomCargocivil cc')
                    //->innerJoin('cc.NomCategcivil np ')
                    //->innerJoin('cc.NomEspecialidad p ')
                    ->innerJoin('cc.NomCategocup ct ')
                    ->where("lower(cc.dencargociv) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {

                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('cc.*,
	            		ct.idcategocup,ct.dencategocup,g.idgrupocomplejidad,g.denominacion,u.idnivelutilizacion,
	            		u.denominacion,cal.idclasificacion,cal.denominacion')
                    ->from('NomCargocivil cc')
                    //->innerJoin('cc.NomCategcivil np ')
                    //->innerJoin('cc.NomEspecialidad p ')
                    ->innerJoin('cc.NomCategocup ct ')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function existeNomcargocivilDenomAbrev($denom, $abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargociv')
                ->from('NomCargocivil')
                ->where("dencargociv ='$denom'or abrevcargociv='$abrev'")
                ->execute()->count();
            return ($consulta != 0);
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
     * @return unknown
     */
    public function existeNomcargocivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargociv')->from('NomCargocivil')->where("idcargociv ='$pId'")->execute()->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     * Verifica si el cargo es usado anteriormente por otro
     *
     * @param int $pId
     * @return bool
     */
    public function usadoNomcargocivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatCargocivil')
                ->where("idcargociv ='$pId'")
                ->execute()
                ->count();
            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarNomcargocivil($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcargociv')->from('NomCargocivil')->where("idcargociv = '$pId'")->execute();
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
        $result = $query->select('max(a.idcargociv) as maximo')
            ->from('NomCargocivil a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }


}

?>
