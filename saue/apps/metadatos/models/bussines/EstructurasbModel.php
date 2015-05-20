<?php

/**
 * EstModel
 *
 * @author zcool
 * @version
 */
class EstructurasbModel extends ZendExt_Model
{

    var $consecutivo;

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
            $consulta = $q->select('e.idestructura,e.denominacion,e.abreviatura,e.idnomeav,e.idorgano,e.idnivelestr,e.idespecialidad,e.iddpa,e.codigo,o.denorgano,o.abrevorgano,n.abrevnivelestr,n.dennivelestr')
                ->from('DatEstructura e')
                ->innerJoin('e.NomOrgano o ')
                //->innerJoin('e.NomEspecialidad es ')
                ->innerJoin('e.NomNivelestr n ')
                //->innerJoin('e.NomDpa d')
                ->where($sql)
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();
            return $consulta;


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
            $resultado = $result->toArray();
            //$solve 			= array ('cant' => $resultTotal->count (), 'datos' => $resultado);
            return $resultado;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

    /**
     * Funcion para mostra los valores y campos de una estructura dada.
     * Utilizada para la integracion con otros modulos.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function Mostrarcamposestruc($pId)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $resul = $query->select('c.nombre,v.valor')
                ->from('NomCampoestruc c ')
                ->innerJoin('c.NomValorestruc v')
                ->innerJoin('v.NomFilaestruc f ')
                ->innerJoin('f.DatEstructura e')
                ->where("e.idestructura = '$pId' ")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();


            return $resul;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
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
     * @param int $idPadre
     * @return array
     */
    public function    getHijos($idPadre, $tipoSubordinacion = false)
    {
        $sqlTipoSubordinacion = $tipoSubordinacion ? ' AND ns.idnomsubordinacion = ' . $tipoSubordinacion : '';

        $SQLwhere = ($idPadre) ? 'su.idpadre=' . $idPadre . ' AND su.idhijo<>su.idpadre' : 'su.idhijo=su.idpadre';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,fe.idfila,su.idhijo,CONCAT(x.codigo,CONCAT('<font color=',CONCAT(ns.color,CONCAT(CONCAT(' > ',x.abreviatura),' </ font>  ')))) as text,'externa' as tipo,  CONCAT('geticon?icon=',x.idnomeav)  as icon,x.denominacion
		")
            ->from('DatEstructura x ')
            ->innerJoin('x.NomFilaestruc fe')
            ->innerJoin('fe.hijo su')
            ->innerJoin('su.NomSubordinacion ns')
            ->where($SQLwhere . $sqlTipoSubordinacion)
            ->orderBy('x.idnomeav')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;

    }


    public function getPadre($pIdhijo, $tipoSubordinacion = false)
    {
        $sqlTipoSubordinacion = $tipoSubordinacion ? ' AND ns.idnomsubordinacion = ' . $tipoSubordinacion : '';
        //	$SQLwhere 		= ($pIdhijo) ?'su.idhijo='.$pIdhijo.' AND su.idhijo<>su.idpadre':'su.idhijo=su.idpadre';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructura as id,fe.idfila,su.idhijo,CONCAT(x.codigo,CONCAT('<font color=',CONCAT(ns.color,CONCAT(CONCAT(' > ',x.abreviatura),' </ font>  ')))) as text,'externa' as tipo,  CONCAT('geticon?icon=',x.idnomeav)  as icon,x.denominacion
		")
            ->from('DatEstructura x ')
            ->innerJoin('x.NomFilaestruc fe')
            ->innerJoin('fe.padre su')
            ->innerJoin('su.NomSubordinacion ns')
            ->where('su.idhijo= ' . $pIdhijo . $sqlTipoSubordinacion)
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
            //					die();

            return $result1;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    // buscar todas las estructuras que pertenecen a una tabla determinada

    public function getEstructurasTablas($idTabla)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("f.idfila,f.idnomeav,f.dominio, x.idestructura,x.denominacion,x.abreviatura, o.idorgano,o.denorgano,o.abrevorgano")
            ->from('NomFilaestruc   f ')
            ->innerJoin('f.DatEstructura x ')
            ->innerJoin('x.NomOrgano o ')
            //->innerJoin('x.NomDpa dpa ')
            ->where("x.idnomeav='$idTabla'")
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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** --------------------------------------------
     * Obtener las estructuras internas dada una estructura
     *
     * @param int $idEstructura
     * @param bool $soloRaices
     *
     * @return array
     */
    function getEstructurasInternas($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);

        $result = $q->select("x.idestructuraop as id,x.abreviatura as text,( 2 = 1) as leaf,CONCAT('geticon?icon=',x.idnomeav)  as icon,'externa' as tipo")
            ->from('DatEstructuraop   x ')
            ->where("x.idestructura='$idEstructura' " . $sqlSoloRaiz)
            ->orderBy('x.idestructuraop')
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function getEstructurasInternasServicio($idEstructura, $soloRaices = false)
    {
        $sqlSoloRaiz = ($soloRaices) ? ' AND x.idestructuraop = x.idpadre ' : '';
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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }

    }

    function reordenarHijos($idelemento)
    {

        if ($idelemento != false) {
            // poner el numero consecutivo en su izquierdo
            $instanciaIzquierda = $this->conn->getTable('DatEstructura')->find($idelemento);
            $instanciaIzquierda->lft = $this->consecutivo;
            $instanciaIzquierda->save();
            $this->consecutivo++;
        }
        $sql = "idpadre = '$idelemento'";
        if ($idelemento == false)
            $sql = "idpadre = idestructura";

        // buscar todos sus hijos
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('idestructura')
            ->from('DatEstructura')
            ->where($sql)
            ->execute()
            ->toArray();
        print_r($this->consecutivo);
        echo '<br>';
        foreach ($result as $fila) {
            $idestructura = $fila['idestructura'];
            if ($idestructura != false)
                $this->reordenarHijos($idestructura);

        }

        // mandar a hacer funcion recursiva para cada uno de sus hijos
        if ($idelemento != false) {
            //  poner el numero consecutivo en su derecho
            $instanciaIzquierda = $this->conn->getTable('DatEstructura')->find($idelemento);
            $instanciaIzquierda->rgt = $this->consecutivo;
            $instanciaIzquierda->save();
            $this->consecutivo++;
        }
    }


}
