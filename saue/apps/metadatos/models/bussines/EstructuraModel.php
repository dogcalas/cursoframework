<?php

/**
 * EstModel
 *
 * @author zcool
 * @version
 */
class EstructuraModel extends ZendExt_Model
{
    public function EstructuraModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatEstructura();
    }

    public function getEstructuraId($pId)
    {

        if (!$pId)
            return false;
        $sql = $pId == 'Estructuras' ? 'e.idestructura =e.idpadre' : "e.idestructura ='$pId'";
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            /*$consulta	= $q->select('e.*,o.*,es.*,n.*,d.*')
                            ->from('DatEstructura e')
                            ->innerJoin('e.NomOrgano o ')
                            ->innerJoin('e.NomEspecialidad es ')
                            ->innerJoin('e.NomNivelestr n ')
                            ->innerJoin('e.NomDpa d')
                            ->where($sql)
                            ->setHydrationMode( Doctrine:: HYDRATE_ARRAY )
                            ->execute()
                            ;*/
            $consulta = $q->select('e.idestructura,e.idpadre,e.denominacion,e.abreviatura,e.idnomeav,e.idorgano,e.idnivelestr,e.idespecialidad,e.iddpa,e.codigo,e.version,o.denorgano,o.abrevorgano,n.abrevnivelestr,n.dennivelestr,v.*')
                ->from('DatEstructura e')
                ->innerJoin('e.NomOrgano o ')
                //->innerJoin('e.NomEspecialidad es ')
                ->innerJoin('e.NomNivelestr n ')
                ->innerJoin('e.NomNomencladoreavestruc v')
                //->innerJoin('e.NomDpa d')
                ->where($sql)
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();
            return $consulta;


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    function getEstructurasPorId($estructuras)
    {
        if (!$estructuras)
            return false;
        //$sql = "e.idestructura in $estructuras";
        //print_r($sql); echo ("<br>");
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('e.idestructura,e.idpadre,e.denominacion,e.abreviatura,e.idnomeav,e.idorgano,e.idnivelestr,e.idespecialidad,e.iddpa,e.codigo,e.version,o.denorgano,o.abrevorgano,n.abrevnivelestr,n.dennivelestr,v.*')
                ->from('DatEstructura e')
                ->innerJoin('e.NomOrgano o ')
                ->innerJoin('e.NomNivelestr n ')
                ->innerJoin('e.NomNomencladoreavestruc v')
                ->whereIn('e.idestructura', $estructuras)
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();

            return $consulta;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    //----Estructuras para crear los dominios de seguridad.----// mierda
    public function DameEstructuraSeguridad($pId)
    {
        if ($pId == 0)
            $sql = 'e.idestructura = e.idpadre';
        else
            $sql = "e.idpadre ='$pId' AND e.idestructura <> e.idpadre";
        try {
            $q = Doctrine_Query::create();
            $consulta = $q->select("e.idestructura id, e.idestructura, e.rgt ,e.lft, e.denominacion text, 'externa' as tipo, false checked")
                ->from('DatEstructura e')
                ->innerJoin('e.NomOrgano o ')
                ->innerJoin('e.NomNivelestr n ')
                ->where($sql)
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            return $consulta;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
        }
    }


    public function getArrayEstructuras($limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            //$resultTotal 	= Doctrine>getTable ('DatEstructura')->findAll ();
            $result = $q->from('DatEstructura')->limit($limit)->offset($start)->execute();
            $resultado = $result->toArray();
            //$solve 			= array ('cant' => $resultTotal->count (), 'datos' => $resultado);
            return $resultado;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function getArrayEstructurasTodas()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            //$resultTotal 	= Doctrine>getTable ('DatEstructura')->findAll ();
            $result = $q->from('DatEstructura')->execute();
            $resultado1 = $result->toArray();
            $result1 = $q->from('DatEstructuraop')->execute();
            $resultado2 = $result1->toArray();
            $resultado = array_merge_recursive($resultado2, $resultado1);
            //$solve 			= array ('cant' => $resultTotal->count (), 'datos' => $resultado);
            return $resultado;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /**
     * Funcion para mostra los valores y campos de una estructura dada.
     * Utilizada para la integracion con otros modulos.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function Mostrarcamposestruc($pId, $campo = false)
    {
        try {


            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);


            if ($campo) {
                $resul = $query->select('c.nombre,v.valor')
                    ->from('NomCampoestruc c ')
                    ->innerJoin('c.NomValorestruc v')
                    ->innerJoin('v.NomFilaestruc f ')
                    ->innerJoin('f.DatEstructura e')
                    ->where("e.idestructura = '$pId' and c.nombre='$campo' ")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
            } else {
                $resul = $query->select('c.nombre,v.valor')
                    ->from('NomCampoestruc c ')
                    ->innerJoin('c.NomValorestruc v')
                    ->innerJoin('v.NomFilaestruc f ')
                    ->innerJoin('f.DatEstructura e')
                    ->where("e.idestructura = '$pId' ")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
            }

            return $resul;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /**
     * Funcion para mostra los valores y campos de una estructura dada.
     * Utilizada para la integracion con otros modulos.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    //Inseratr Estructura
    public function  insertarEstructura($idfila, $idpadre, $idprefijo, $fechaini, $fechafin, $denominacion, $abreviatura, $idnomeav, $idorgano, $idespecialidad, $idnivelestr, $iddpa, $codigo)
    {
        $this->Instancia();
        $this->instance = new DatEstructura();

        $this->instance->idestructura = $idfila;
        $this->instance->idpadre = ($idpadre == 'Estructuras') ? $idfila : $idpadre;
        $this->instance->idprefijo = $idprefijo;
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
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function eliminarEstructura($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructura')->from('DatEstructura')->where("idestructura = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;

        }
    }


    public function eliminarEstructurasporTabla($idTabla)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructura')->from('DatEstructura')->where("idnomeav = '$idTabla'")->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function  eliminarEstructuralog($pidestructura, $pfecha)
    {
        try {
            $this->instance = $this->conn->getTable('DatEstructura')->find($pidestructura);


            $this->instance->fechafin = $pfecha;
            $this->instance->save();
            return true;

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
        $result = $query->select('max(a.idestructura) as maximo')
            ->from('DatEstructura a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /** ----------------------------------------
     * Buscar todos los hijos de una estructura
     *
     * @param int $idPadre >
     * @return array
     */
    public function getHijos($idPadre, $fecha = '2000-01-01')
    {

        $SQLwhere = ($idPadre) ? "x.idpadre='$idPadre' AND x.idestructura<>x.idpadre and x.fechafin>'$fecha'" : "x.idestructura=x.idpadre and x.fechafin>'$fecha'";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,CONCAT(x.codigo,CONCAT(' ',x.abreviatura)) as text,( (x.rgt - x.lft) = 1) as leaf,x.codigo,'externa' as tipo,  CONCAT('geticon?icon=',x.idnomeav)  as icon,x.denominacion")
            ->from('DatEstructura x ')
            ->where($SQLwhere)
            ->orderBy('x.idnomeav')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    public function getHijoss($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructura<>x.idpadre" : "x.idestructura=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,x.abreviatura as text,( (x.rgt - x.lft) = 1) as leaf,'externa' as tipo, x.denominacion")
            ->from('DatEstructura x ')
            ->where($SQLwhere)
            ->orderBy('x.idestructura')
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
    public function getHijosOrga($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructura<>x.idpadre" : "x.idestructura=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,x.abreviatura as text,false as leaf,'externa' as tipo
										, (1 = 0 ) as checked,
										x.idpadre as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon,x.idnomeav as idnomeav, ( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")
            ->from('DatEstructura x ')
            ->where($SQLwhere)
            ->orderBy('x.idestructura')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $result;
    }

    public function getHijosReporte($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructura<>x.idpadre" : "x.idestructura=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,x.denominacion as text,( (x.rgt - x.lft) = 1) as leaf,'externa' as tipo
										, (1 = 0 ) as checked,
										x.idpadre as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon,x.idnomeav as idnomeav, ( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")
            ->from('DatEstructura x ')
            ->where($SQLwhere)
            ->orderBy('x.idestructura')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $result;
    }

    /**
     *          Para el componente de interfaz que presta servicios.
     *
     */
    public function getHijosCompnente($idPadre)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idestructura<>x.idpadre" : "x.idestructura=x.idpadre";
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,x.codigo,x.denominacion,x.abreviatura as text,false as leaf,'externa' as tipo
										, 
										x.idpadre as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon,x.idnomeav as idnomeav, ( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")
            ->from('DatEstructura x ')
            ->where($SQLwhere)
            ->orderBy('x.idestructura')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $result;
    }

    /**
     *          Para el componente de interfaz que presta servicios.
     *
     */
    function getEstructurasInternasComponente($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop,x.denominacion,x.codigo,x.idestructuraop as id,CONCAT( x.abreviatura,CONCAT('-' ,no.abrevorgano ) )  as text,( (x.rgt - x.lft) = 1) as leaf,'interna' as tipo,
										x.idestructura as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon, ( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")
            ->from('DatEstructuraop   x ')
            ->innerJoin('x.NomOrgano no')
            ->where("x.idestructura='$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
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
    public function getArbol($idPadre = 'Estructuras')
    {
        try {
            if ($idPadre == 'Estructuras') {
                $izq = 0;
                $der = 10000000;
            } else {

                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('lft as left,rgt as right')
                    ->from('DatEstructura  ')
                    ->where("idestructura='$idPadre' ")
                    ->execute()
                    ->toArray();
                $izq = isset($result[0]['left']) ? $result[0]['left'] : 0;
                $der = isset($result[0]['right']) ? $result[0]['right'] : 0;
            }
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q1 = Doctrine_Query::create($conn);

            $result1 = $q1->select("*")
                ->from(' DatEstructura  ')
                ->where("
										 '$izq' <= lft AND
										 '$der' >= rgt 
										")
                ->orderBy('lft')
                ->execute()
                ->toArray();
            //echo '<pre>';
            //print_r($result1);
            //					echo ();

            return $result1;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    // buscar todas las estructuras que pertenecen a una tabla determinada

    public function getEstructurasTablas($idTabla)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("f.idfila,f.idnomeav,f.dominio, x.idestructura,x.denominacion,x.abreviatura, o.idorgano,o.denorgano,o.abrevorgano,x.iddpa")
            ->from('NomFilaestruc   f ')
            ->innerJoin('f.DatEstructura x ')
            ->innerJoin('x.NomOrgano o ')
            //->innerJoin('x.NomDpa dpa ')
            ->where("x.idpadre='$idTabla'")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    // buscar todas las estructuras hijas
    public function getEstructurasTablashijas($idTabla)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("f.idfila,f.idnomeav,f.dominio, x.idestructura,x.denominacion,x.abreviatura ,x.iddpa, o.idorgano,o.denorgano,o.abrevorgano,n.idnomeav,n.nombre")
            ->from('NomFilaestruc   f ')
            ->innerJoin('f.DatEstructura x ')
            ->innerJoin('x.NomOrgano o ')
            ->innerJoin('x.NomNomencladoreavestruc n')
            //->innerJoin('x.NomDpa dpa ')
            ->where("x.idpadre='$idTabla' and x.idestructura != '$idTabla'")
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();

        return $result;
    }


    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new DatEstructura();
    }

    //------------------------------------------
    public function  modificarEstructura($pidestructura, $pfechaini, $pfechafin, $pdenominacion, $pabreviatura, $organo, $codigo, $iddpa, $idnivelestr, $idespecialidad)
    {
        try {
            $this->instance = $this->conn->getTable('DatEstructura')->find($pidestructura);

            $this->instance->fechaini = $pfechaini;
            $this->instance->fechafin = $pfechafin;
            $this->instance->denominacion = $pdenominacion;
            $this->instance->abreviatura = $pabreviatura;
            $this->instance->idorgano = $organo;
            $this->instance->codigo = $codigo;
            $this->instance->iddpa = $iddpa;
            $this->instance->idnivelestr = $idnivelestr;
            $this->instance->idespecialidad = $idespecialidad;
            $this->instance->save();
            return true;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**
     * Obtener estructuras internas segun la fecha.
     *
     * @param unknown_type $idEstructura
     * @param unknown_type $fehca
     * @param unknown_type $soloRaices
     * @return unknown
     */
    function getEstructurasInternasFecha($idEstructura, $fecha = '2000-01-01', $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop as id,x.abreviatura as text,( 2= 1) as leaf,CONCAT('geticon?icon=',x.idnomeav)  as icon,x.codigo,'interna' as tipo")
            ->from('DatEstructuraop   x ')
            ->where("x.idestructura='$idEstructura' AND x.fechafin > '$fecha'" . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    /**---------------------------------------------------------
     *  Obtener las estructuras internas dada una estructura
     *
     * @param unknown_type $idEstructura
     * @param unknown_type $soloRaices
     * @param unknown_type $fecha
     * @return array
     */
    function getEstructurasInternas($idEstructura, $soloRaices = false, $fecha = '2000-01-01')
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop as id,x.abreviatura as text,( 2= 1) as leaf,CONCAT('geticon?icon=',x.idnomeav)  as icon,x.codigo,'interna' as tipo")
            ->from('DatEstructuraop   x ')
            ->where("x.idestructura='$idEstructura' AND x.fechafin > '$fecha'" . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function getEstructurasInternasServicio($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND xs.idestructuraop = xs.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("xs.*,xs.idestructuraop as id,xs.abreviatura as text,( 2 = 1) as leaf,CONCAT('geticon?icon=',xs.idnomeav)  as icon,'externa' as tipo,y.idorgano,y.denorgano,y.abrevorgano,y.idnomeav")
            ->from('DatEstructuraop   xs ')
            ->innerJoin('xs.NomOrgano y')
            ->where("xs.idestructura='$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('xs.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function getEstructurasInternasOrg($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop,x.idestructuraop as id,CONCAT( x.abreviatura,CONCAT('-' ,no.abrevorgano ) )  as text,( (x.rgt - x.lft) = 1) as leaf,'folder' as cls,'externa' as tipo, (1 = 0 ) as checked,
										x.idestructura as idpadre,CONCAT('geticon?icon=',x.idnomeav)  as icon, ( x.idnomeav <> 2 ) as pintar,( x.idnomeav <> 2 ) as pintado")
            ->from('DatEstructuraop   x ')
            ->innerJoin('x.NomOrgano no')
            ->where("x.idestructura='$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    //-------Mostrar campos de las estructuras de seguridad--------------//
    public function MostrarCamposEstructuraSeguridad($idestructura)
    {
        try {
            $query = Doctrine_Query::create();
            $resul = $query->select('e.idestructura id, e.denominacion text, true leaf')
                ->from('DatEstructura e')
                ->where("e.idestructura = '$idestructura' ")
                ->execute()->toArray(true);
            return $resul;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

    function eliminarEstructuraPorTabla($idnomeav)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructura')->from('DatEstructura')->where("idnomeav = '$idnomeav'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            //throw new ZendExt_Exception('CONT010', $ee);
            return false;
        }
    }

    public function existeCodigoEstexterna($pCod)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->select('idestructura')->from('DatEstructura')->where("codigo = '$pCod'")->execute()->count();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }

    }


    public function tieneHijos($pId)
    {
        try {
            $query = Doctrine_Query::create();


            $result = $query->select('count(e.idestructura) cont')
                ->from('DatEstructura e')
                ->where("e.idpadre = '$pId' and e.idestructura!='$pId'")
                ->execute()
                ->toArray();
            $resul = $query->select('count(e.idestructuraop) cont')
                ->from('DatEstructuraop e')
                ->where("e.idestructura='$pId'")
                ->execute()
                ->toArray();

            $p = $result[0]['cont'] + $resul[0]['cont'];
            return $p == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    public function DameEstructurasinChecked($idestructura)
    {
        if ($idestructura == 0)
            $sql = 'e.idestructura = e.idpadre';
        else
            $sql = "e.idpadre ='$idestructura' AND e.idestructura <> e.idpadre";
        $q = Doctrine_Query::create();
        $consulta = $q->select('e.idestructura id, e.denominacion text,(e.rgt - e.lft = 1) as leaf')
            ->from('DatEstructura e')
            ->where($sql)
            ->execute();
        return $consulta->toArray();
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
     *
     * /** --------------------------------------------
     * Obtener las estructuras internas dada una estructura
     *
     * @param int $idEstructura
     * @param bool $soloRaices
     *
     * @return array
     */
    function DameEstructurasInternasSeguridad($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? 'AND x.idestructuraop = x.idpadre ' : '';
        $q = Doctrine_Query::create();

        $result = $q->select("x.idestructuraop as id, x.idestructuraop ,x.abreviatura as text,( x.rgt - x.lft = 1) as leaf, 'interna' as tipo, false checked")
            ->from('DatEstructuraop   x ')
            ->where("x.idestructura = '$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function DameEstructurasInternasSeguridadSinCheked($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? 'AND x.idestructuraop = x.idpadre ' : '';
        $q = Doctrine_Query::create();
        $result = $q->select("x.idestructuraop as id, x.idestructuraop ,x.abreviatura as text,( x.rgt - x.lft = 1) as leaf, 'interna' as tipo")
            ->from('DatEstructuraop   x ')
            ->where("x.idestructura = '$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function DameEstructurasPorArrayIds($estructuras)
    {
        $q = Doctrine_Query::create();
        $result = $q->select("x.idestructura as id, x.codigo as codigo, ");
    }


    /**
     * Devuelve el id del padre.
     *
     * @param unknown_type $idhijo
     * @return idpadre
     *
     */

    public function getPadre($pIdhijo)
    {

        $query = Doctrine_Query::create();

        $result = $query->select('idpadre as id')
            ->from('DatEstructura')
            ->where("idestructura ='$pIdhijo'")
            ->execute()
            ->toArray();
        return $result;

    }

    public function getInfoPadre($pIdhijo, $pTipo)
    {
        $query = Doctrine_Query::create();
        if ($pTipo) {
            $result = $query->select("idpadre as id, 'externa' as tipo")
                ->from('DatEstructura')
                ->where("idestructura ='$pIdhijo'")
                ->execute()
                ->toArray();

        } else {
            $result = $query->select("idpadre as id, idestructura, 'interna' as tipo")
                ->from('DatEstructuraop')
                ->where("idestructuraop ='$pIdhijo'")
                ->execute()
                ->toArray();
        }

        return $result;
    }

    /*c3
    Verificar si existe alg�n �rea de resp en la BD
    que no est� en ese arreglo, en caso de que exista
    al menos 1 �rea que est� en la BD y no est� en el
    arreglo se devuelve 1, en caso contrario se devuelve 0.
    */
    function ExistenNoAsociadas($idAreasArray)
    {

        try {

            $query = new Doctrine_Query ();
            $result = $query->select('a.idestructuraop')->from('DatEstructuraop a')
                ->whereIn("a.idestructuraop", $idAreasArray)
                ->execute()
                ->toArray();


            //llama al metodo en el DatEstructura domain y return un bool
            $DatEstructura = new DatEstructura();
            $resultBool = $DatEstructura->ExistenNoAsociadasDomain($result);
            return $resultBool;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }


    }

}
