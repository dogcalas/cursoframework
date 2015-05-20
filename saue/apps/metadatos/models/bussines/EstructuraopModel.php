<?php
class EstructuraopModel extends ZendExt_Model
{
    public function EstructuraopModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatEstructuraop();
    }

    public function insertarEstructuraop($idfila, $idpadre, $idprefijo, $idestructura, $fechaini, $fechafin, $denominacion, $abreviatura, $idnomeav, $idorgano, $idespecialidad, $idnivelestr, $iddpa, $codigo)
    {
        $this->Instancia();

        $this->instance->idestructuraop = $idfila;
        $this->instance->idpadre = ($idpadre == $idestructura) ? $idfila : $idpadre;
        $this->instance->idprefijo = $idprefijo;
        $this->instance->idestructura = $idestructura;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        $this->instance->denominacion = $denominacion;
        $this->instance->abreviatura = $abreviatura;
        $this->instance->idnomeav = $idnomeav;
        $this->instance->idorgano = $idorgano;
        $this->instance->iddpa = $iddpa;
        $this->instance->codigo = $codigo;
        if ($idespecialidad)
            $this->instance->idespecialidad = $idespecialidad;
        if ($idnivelestr)
            $this->instance->idnivelestr = $idnivelestr;

        try {
            $this->instance->save();
            return $idfila;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }

    }

    public function eliminarEstructura($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructuraop')
                ->from('DatEstructuraop')
                ->where("idestructuraop = '$pId'")
                ->execute();
            return ($result != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    /** ---------------------------------------------------------------
     * Verifica si existe la estructura
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function exiteDatestructuraop($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idestructuraop')->from('DatEstructuraop')->where("idestructuraop ='$pId'")->execute()->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /** ---------------------------------------------------------------
     * Busca los datos de la estructura
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */
    public function buscarDatestructuraop($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            $resultTotal = $this->conn->getTable('DatEstructuraop')->findAll();
            $result = $q->from('DatEstructuraop')->limit($limit)->offset($start)->execute();
            $resultado = $result->toArray();
            $solve = array('cant' => $resultTotal->count(), 'datos' => $resultado);
            return $solve;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /** ---------------------------------------------------------------
     * Elimina una estructura
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function eliminarDatestructuraop($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructuraop')->from('DatEstructuraop')->where("idestructuraop = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    /** ---------------------------------------------------------------
     * Obtiene el id de la estructura que se debe adicionar
     *
     * @return int
     */
    function idUltimaEstructura()
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        ///$resultTotal 	= $this->conn->getTable ('DatEstructura')->findAll ();
        $result = $q->select("max(idestructuraop)")->from('DatEstructuraop ')->execute();
        $arrelo = $result->toArray();
        return $arrelo[0]['max'];
        //$solve 			= array ('cant' => $resultTotal->count (), 'datos' => $resultado);

    }

    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new DatEstructuraop();
    }

    /** ----------------------------------------
     * Buscar todos los hijos de una estructura
     *
     * @param int $idPadre
     * @return array
     */
    public function getHijos($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructuraop<>x.idpadre" : "x.idestructuraop=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop as id,x.abreviatura as text,( 2 = 1) as leaf, x.codigo,
										CONCAT('geticon?icon=',x.idnomeav)  as icon,'externa' as tipo, x.lft, x.rgt")->from('DatEstructuraop x ')
            ->where($SQLwhere)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }


    /** ----------------------------------------
     * Buscar todos los hijos de una estructura
     *
     * @param int $idPadre
     * @return array
     */
    public function getHijosOrg($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructuraop<>x.idpadre" : "x.idestructuraop=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop,x.idestructuraop as id,CONCAT( x.abreviatura,CONCAT('-',no.abrevorgano)) as text,( (x.rgt - x.lft) = 1) as leaf,'folder' as cls,'externa' as tipo,
										 (1 = 0 ) as checked,
										x.idpadre as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon,
										( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")->from('DatEstructuraop x ')
            ->innerJoin('x.NomOrgano no')
            ->where($SQLwhere)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();

        return $result;
    }

    /**
     * Obtener lod datos de un Area segun su id
     */
    public function buscarEstructurabyId($pid)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select(" x.denominacion,x.abreviatura, o.denorgano as tipo_de_area,x.codigo")
            ->from('DatEstructuraop x ')
            ->innerJoin('x.NomOrgano o ')
            ->where("x.idestructuraop='$pid' ")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;


    }

    /** ----------------------------------------------------------------
     * Otener las estructuras que pertenecen a una tabla determinada
     *
     * @param int $idTabla
     * @return array
     */
    public function getEstructurasTablas($idTabla, $idestructura)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("f.idfila,f.idnomeav,f.dominio,x.idestructuraop,x.idpadre,x.denominacion,x.abreviatura,x.idnomeav,x.idestructura,x.idorgano,x.idespecialidad,x.idnivelestr,x.iddpa,x.codigo,o.idorgano,o.denorgano,o.abrevorgano")
            ->from('NomFilaestruc   f ')
            ->innerJoin('f.DatEstructuraop x ')
            ->innerJoin('x.NomOrgano o ')
            ->where("x.idnomeav='$idTabla' AND x.idestructura='$idestructura' ")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    /**
     *Otener las estructuras hijas
     * @param <type> $idTabla
     * @param <type> $idestructura
     * @return <type>
     */

    public function getEstructurasTablashijas($idTabla, $idestructura)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("f.idfila,f.idnomeav,f.dominio,x.idestructuraop,x.idpadre,x.denominacion,x.abreviatura,x.idnomeav,x.idestructura,x.idorgano,x.idespecialidad,x.idnivelestr,x.iddpa,x.codigo,o.idorgano,o.denorgano,o.abrevorgano,n.idnomeav,n.nombre")
            ->from('NomFilaestruc   f ')
            ->innerJoin('f.DatEstructuraop x ')
            ->innerJoin('x.NomOrgano o ')
            ->innerJoin('x.NomNomencladoreavestruc n')
            ->where("x.idpadre='$idTabla' AND x.idestructura='$idestructura' AND x.idestructuraop !='$idTabla'")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    public function getEstructuraId($pId, $idestructura = false)
    {

        if (!$pId)
            return false;
        $sql = ($pId == 'Composicion') ? (($idestructura && $idestructura != 'Estructuras') ? " e.idestructuraop = e.idpadre AND e.idestructura='$idestructura' " : ' e.idestructuraop = e.idpadre ') : " e.idestructuraop ='$pId' ";

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('e.idestructuraop,e.idpadre,e.abreviatura,e.denominacion,e.idnomeav,e.idestructura,e.idorgano,e.idespecialidad,e.idnivelestr,e.iddpa,e.codigo,e.version,o.idorgano,o.denorgano,o.abrevorgano,o.idnomeav,n.idnivelestr,n.abrevnivelestr,n.dennivelestr')
                ->from('DatEstructuraop e')
                ->innerJoin('e.NomOrgano o ')
                ->innerJoin('e.NomNivelestr n ')
                ->where($sql)
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();

            return $consulta;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;

        }
    }

    public function buscaridproximo()
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idestructuraop) as maximo')
            ->from('DatEstructuraop a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    public function  modificarEstructura($pidestructura, $pfechaini, $pfechafin, $pdenominacion, $pabreviatura, $idorgano, $codigo, $iddpa, $idnivelestr, $idespecialidad)
    {
        try {

            $this->instance = $this->conn->getTable('DatEstructuraop')->find($pidestructura);
            $this->instance->fechaini = $pfechaini;
            $this->instance->fechafin = $pfechafin;
            $this->instance->denominacion = $pdenominacion;
            $this->instance->abreviatura = $pabreviatura;
            $this->instance->idorgano = $idorgano;
            $this->instance->codigo = $codigo;
            $this->instance->idnivelestr = $idnivelestr;
            $this->instance->idespecialidad = $idespecialidad;
            $this->instance->iddpa = $iddpa;
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $e) {

            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());


            return false;
        }
    }

    function eliminarEstructuraPorTabla($idnomeav)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructura')->from('DatEstructuraop')->where("idnomeav = '$idnomeav'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    public function existeCodigoEstinterna($pCod)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->select('idestructura')->from('DatEstructuraop')->where("codigo = '$pCod'")->execute()->count();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function tienehijosop($pId)
    {
        try {
            $q = Doctrine_Query::create();
            $result = $q->select('count(e.idestructuraop) cont')
                ->from('DatEstructuraop e')
                ->where("e.idpadre='$pId' and e.idestructuraop!='$pId'")
                ->execute()
                ->toArray();
            $resul = $q->select('count(e.idcargo) cont')
                ->from('DatCargo e')
                ->where("e.idestructuraop='$pId'")
                ->execute()
                ->toArray();
            $p = $result[0]['cont'] + $resul[0]['cont'];
            return $p == 0 ? false : true;
            //	return 	$result[0]['cont']==0 ? false : true;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /**
     * Obtener lod datos de un Area segun su id
     */
    public function EstructurasInternasDadoIDSeguridad($idarea)
    {
        $q = Doctrine_Query::create();
        $result = $q->select("x.denominacion,x.abreviatura, o.denorgano as tipo_de_area")
            ->from('DatEstructuraop x ')
            ->innerJoin('x.NomOrgano o ')
            ->where("x.idestructuraop='$idarea' ")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;


    }

    /** ----------------------------------------
     * Buscar todos los hijos de una estructura
     *
     * @param int $idPadre
     * @return array
     */
    public function DameHijosInternaSeguridad($idPadre)
    {

        $SQLwhere = ($idPadre) ? 'x.idpadre=' . $idPadre . ' AND x.idestructuraop <>x.idpadre' : 'x.idestructuraop =x.idpadre';
        $q = Doctrine_Query::create();

        $result = $q->select("( x.rgt - x.lft = 1) as leaf, x.idestructuraop as id,CONCAT(x.codigo,CONCAT(' ',x.abreviatura)) as text, 'interna' as tipo,x.denominacion, false  checked")
            ->from('DatEstructuraop x ')
            ->where($SQLwhere)
            ->orderBy('x.idnomeav')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    /** ----------------------------------------
     * Buscar todos los hijos de una estructura sin cheked
     *
     * @param int $idPadre
     * @return array
     */
    public function DameHijosInternaSeguridadSinCheked($idPadre)
    {
        $SQLwhere = ($idPadre) ? 'x.idpadre=' . $idPadre . ' AND x.idestructuraop <>x.idpadre' : 'x.idestructuraop =x.idpadre';
        $q = Doctrine_Query::create();
        $result = $q->select("( x.rgt - x.lft = 1) as leaf, x.idestructuraop as id,CONCAT(x.codigo,CONCAT(' ',x.abreviatura)) as text, 'interna' as tipo,x.denominacion")
            ->from('DatEstructuraop x ')
            ->where($SQLwhere)
            ->orderBy('x.idnomeav')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }


}

?>
