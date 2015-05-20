<?php
class NomcalificadorModel extends ZendExt_Model
{

    public function NomcalificadorModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCalificadorCargo();
    }

    /**
     * Insertar un calificador de cargo.
     *
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */

    public function insertarNomcalificador($pIdcat, $pIdtipocal, $pDenom, $pAbrev, $pOrden, $pCodigo, $pFini, $pFfin)
    {

        //$this->instance->idcalificador		= $this->buscaridproximo();
        $this->instance->idtipocalificador = $pIdtipocal;
        $this->instance->idcategocup = $pIdcat;
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        $this->instance->orden = $pOrden;
        $this->instance->codigo = $pCodigo;
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
     * funcion para buscar los calificador de cargo.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */

    public function buscarNomcalificador($limit = 10, $start = 0, $denom = false)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($denom) {
                $denom = strtolower($denom);

                $result = $q->select("c.idcalificador,c.denominacion,c.abreviatura,c.fechaini,c.fechafin,c.orden,c.idtipocalificador,c.codigo,c.idcategocup,
								t.idtipocalificador,t.denominacion,t.abreviatura,o.idcategocup,o.dencategocup,o.abreviatura")
                    ->from('NomCalificadorCargo c')
                    ->innerJoin('c.NomTipoCalificador t')
                    ->innerJoin('c.NomCategocup o')
                    ->where("lower(c.denominacion) like '$denom%'")
                    //->leftjoin ('c.NomCategocup o')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();

            } else {

                $result = $q->select("c.idcalificador,c.denominacion,c.abreviatura,c.fechaini,c.fechafin,c.orden,c.idtipocalificador,c.codigo,c.idcategocup,
								t.idtipocalificador,t.denominacion,t.abreviatura,o.idcategocup,o.dencategocup,o.abreviatura")
                    ->from('NomCalificadorCargo c')
                    ->innerJoin('c.NomTipoCalificador t')
                    ->innerJoin('c.NomCategocup o')
                    //->leftjoin ('c.NomCategocup o')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /**
     * Contar la cantidad de calificadores de cargo
     *
     * @return unknown
     */

    public function contNomcalificador($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $resul = $q->select('count(c.idcalificador) cont')
                    ->from('NomCalificadorCargo c')
                    ->innerJoin('c.NomTipoCalificador t')
                    ->innerJoin('c.NomCategocup o')
                    ->where("lower(c.denominacion) like '$denom%'")
                    ->execute()
                    ->toArray();

                $result = $resul[0]['cont'];


                return $result;
            } else {

                $resul = $q->select('count(c.idcalificador) cont')
                    ->from('NomCalificadorCargo c')
                    ->innerJoin('c.NomTipoCalificador t')
                    ->innerJoin('c.NomCategocup o')
                    ->execute()
                    ->toArray();

                $result = $resul[0]['cont'];


                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Para comprobar si tiene relacion con otra tabla.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function usandoNomcalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('NomCargocivil')
                ->where("idcalificador ='$pId'")
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
     * buscar el proximo id para insertar.
     *
     * @return unknown
     */

    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idagrupacion) as maximo')
            ->from('NomAgrupacion a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }

    /**
     * Modificar un calificador
     *
     * @param unknown_type $pIdcalificador
     * @param unknown_type $pDenom
     * @param unknown_type $pAbrev
     * @param unknown_type $pOrden
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */
    public function modificarNomcalificador($pIdcalificador, $pIdcat, $pIdtipocal, $pDenom, $pAbrev, $pOrden, $pCodigo, $pFini, $pFfin)
    {

        $this->instance = $this->conn->getTable('NomCalificadorCargo')->find($pIdcalificador);
        $this->instance->idcategocup = $pIdcat;
        $this->instance->idtipocalificador = $pIdtipocal;
        $this->instance->denominacion = $pDenom;
        $this->instance->abreviatura = $pAbrev;
        $this->instance->orden = $pOrden;
        $this->instance->codigo = $pCodigo;
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

    public function eliminarNomcalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idagrupacion')
                ->from('NomCalificadorCargo')
                ->where("idcalificador = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     * Comprobar si existe un claificador de cargo por denomincanion o abreviatura
     * @author Rolando Ramos Morales
     * @param String $Denom Denominacion del calificador
     * @param String $abrev Abreviatura del  calificador
     * @return boolean
     */
    public function exiteNomcalificadorDenomAbrev($Denom, $Abrev)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $consulta = $q->select('idtipocalificador')
                ->from('NomCalificadorCargo')
                ->where("denominacion ='$Denom' or  abreviatura='$Abrev' ")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }


    public function existeNomcalificador($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $consulta = $q->select('idtipocalificador')
                ->from('NomCalificadorCargo')
                ->where("idagrupacion ='$pId'")
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
