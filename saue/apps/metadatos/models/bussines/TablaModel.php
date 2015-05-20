<?php
class  TablaModel extends ZendExt_Model
{

    public function TablaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomNomencladoreavestruc();
    }

    /** -------------------------------------------------
     * insertar una nueva tabla estructura
     */

    public function insertar($nombre, $fechaini, $fechafin, $idpadre = false, $recursiva = 0, $entidad = 0)
    {

        $this->Instancia();

        //-- buscar el proximo id de tabla
        $idnomeav = $this->buscaridproximo();
        $idpadre = $idpadre ? $idpadre : $idnomeav;

        $this->instance->idnomeav = $idnomeav;
        $this->instance->nombre = $nombre;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        $this->instance->idpadre = $idpadre;
        $this->instance->recursiva = $recursiva;
        //$this->instance->entidad	= $entidad;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** -------------------------------------------------
     * Listar las tablas de las estructuras
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarTablas($idTabla = false, $limit = 1000, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            if ($idTabla) {
                $result = $q->from('NomNomencladoreavestruc t')
                    ->where(" t.idnomeav='$idTabla' ")
                    ->limit($limit)
                    ->offset($start)

                    ->execute()
                    ->toArray();
            } else {
                $result = $q->from('NomNomencladoreavestruc a')
                    ->limit($limit)
                    ->offset($start)
                    ->execute()
                    ->toArray();
            }
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /** -------------------------------------------------
     * Lista Todos los campos de una tabla
     */
    public function buscarTablasCampos($idTabla, $mostrarOcultos = false)
    {
        try {
            $sqlTabla = $idTabla ? " AND t.idnomeav = '$idTabla'" : '';
            $sqlVisi = $mostrarOcultos ? "  " : "  AND c.visible = '1'  ";

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('t.*,c.*')
                ->from('NomNomencladoreavestruc t')
                ->innerJoin('t.NomCampoestruc c')
                ->where('t.idnomeav = c.idnomeav ' . $sqlTabla . $sqlVisi)
                ->orderBy(' c.idcampo ')
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** -------------------------------------------------
     * Listar todas los valores-campos de una tabla
     */
    public function buscarTablasCamposValores($idTabla = false, $idfila = false)
    {
        try {
            $sqlfila = $idfila ? " AND v.idfila='$idfila' " : '';
            $sqlTabla = $idTabla ? " AND t.idnomeav='$idTabla' " : '';
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('t.*,c.*,f.*,v.*')
                ->from('NomNomencladoreavestruc  t')
                ->innerJoin('t.NomCampoestruc c')
                ->innerJoin('t.NomFilaestruc  f')

                //->innerJoin('f.DatEstructura  e')
                ->leftJoin('c.NomValorestruc v')

                //->innerJoin('v.NomCampoestruc x')
                ->where(' t.idnomeav = c.idnomeav ' . $sqlTabla . $sqlfila)
                ->orderBy(' c.idcampo ')
                ->limit($limit)
                ->offset($start)
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /** -------------------------------------------------
     * Elimina una tabla
     */
    public function eliminarTabla($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idnomeav')
                ->from('NomNomencladoreavestruc')
                ->where("idnomeav = '$pId'")
                ->execute();
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
        $result = $query->select('max(a.idnomeav) as maximo')
            ->from('NomNomencladoreavestruc a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new NomNomencladoreavestruc();
    }


    public function modificar($idTabla, $nombreTabla, $fechaini, $fechafin, $root, $concepto, $externa)
    {

        $this->instance = $this->conn->getTable('NomNomencladoreavestruc')->find($idTabla);
        $this->instance->nombre = $nombreTabla;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        $this->instance->root = $root;
        $this->instance->concepto = $concepto;
        $this->instance->externa = $externa;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC23', $ee);

            return false;
        }
    }

    /** --------------------------------------------
     * Devuelve todas las tablas hijas
     *
     * @param int $idPadre
     * @return array
     */
    public function getHijos($idPadre = false)
    {

        $SQLwhere = ($idPadre) ? "x.idpadre=$idPadre AND x.idnomeav<>x.idpadre" : "x.idnomeav=x.idpadre";
        $SQLpadre = ($idPadre) ? " x.idpadre  " : " null ";

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $q = Doctrine_Query::create($conn);


        $result = $q->select("x.idnomeav as _id, $SQLpadre   as _parent,'geticon?icon=6' as _icon ,( (x.rgt - x.lft) = 1) as _is_leaf,x.nombre,x.fechaini,x.fechafin")
            ->from('NomNomencladoreavestruc x ')
            ->where($SQLwhere)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $result;
    }

    /** --------------------------------------------
     * Devuelve todas las tablas subordinadas sin importar el nivel al que se encuentren
     *
     * @param int $idPadre
     * @return array
     */
    public function getSubordinados($idPadre = 1)
    {

        try {
            $recursivo = false;
            if ($idPadre == 'Estructuras') {
                $izq = 0;
                $der = 10000000;
            } else {

                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select('lft as left,rgt as right, recursiva as recursi ')
                    ->from('NomNomencladoreavestruc  ')
                    ->where("idnomeav='$idPadre' ")
                    ->execute()
                    ->toArray();
                $izq = isset($result[0]['left']) ? $result[0]['left'] : 0;
                $der = isset($result[0]['right']) ? $result[0]['right'] : 0;
                $recursivo = isset($result[0]['recursi']) ? $result[0]['recursi'] : 0;
            }

            $sqlRec = ($recursivo) ? " '$izq' <= lft AND '$der' >= rgt " : " '$izq' < lft AND '$der' > rgt ";
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q1 = Doctrine_Query::create($conn);

            $result1 = $q1->select("idnomeav as id, nombre as text")
                ->from(' NomNomencladoreavestruc  ')
                ->where($sqlRec)
                ->orderBy('lft')
                ->execute()
                ->toArray();


            return $result1;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     * Buscar todas las aristas de la tabla enviada
     * si se envia falso entonces buscar las que son raices
     *
     * @param int $idTabla
     * @param bool $externa
     * @return array
     */
    function buscarConexiones($idTabla, $externa = false, $todas = false)
    {

        $sqlTipo = '';
        // buscar solo los de estructura interna
        if ($externa === true) {
            $sqlTipo = " AND ( y.externa = 2 OR y.externa = 3 )";
        } elseif ($externa === false) // buscar los de tipo interna
        {
            $sqlTipo = "AND ( y.externa = 1 OR y.externa = 3 )";
            //$sqlTipo		= " AND  ( y.externa || 1 ) = y.externa ";
        }

        if (!$idTabla) {

            $sqlTipo = "y.root='1' " . $sqlTipo;

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("y.idnomeav as id, y.nombre as text, y.*")
                ->from('NomNomencladoreavestruc y')
                ->where($sqlTipo)
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            return $result;
        }
        if ($todas)
            $sqlTipo = '';
        $q = new Doctrine_Query ();
        $result = $q->select("y.idnomeav as id, y.nombre as text, y.*, h.*")
            ->from('NomNomencladoreavestruc y')
            ->innerJoin('y.NomArista h')
            ->where("h.idorigen='$idTabla' " . $sqlTipo)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();


        return $result;
    }

    function insertarRelacion($idTabla, $idRelacion)
    {
        $this->instance = new NomArista();

        $this->instance->idorigen = $idTabla;
        $this->instance->iddestino = $idRelacion;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    function eliminarRelacion($idTabla, $idRelacion)
    {
        try {
            /*  $mg = ZendExt_Aspect_TransactionManager::getInstance();
             $conn = $mg->getConnection('metadatos');*/
            $query = new Doctrine_Query();

            $result = $query->delete('idnomeav')
                ->from('NomArista')
                ->where("idorigen = '$idTabla' AND iddestino = '$idRelacion'")
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** -------------------------------------------------
     * insertar una nueva tabla estructura
     */

    public function insertarn($nombre, $fechaini, $fechafin, $root = 0, $concepto = 0, $externa = 0)
    {
        $this->Instancia();

        //-- buscar el proximo id de tabla
        $idnomeav = $this->buscaridproximo();
        //$idpadre					= $idpadre ? $idpadre : $idnomeav ;

        $this->instance->idnomeav = $idnomeav;
        $this->instance->nombre = $nombre;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;
        $this->instance->root = $root;
        $this->instance->concepto = $concepto;
        $this->instance->externa = $externa;
        //$this->instance->entidad	= $entidad;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC23', $ee);

            return false;
        }
    }

    public function contTablas()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);


            $resul = $q->select('count(idnomeav) cont')
                ->from('NomNomencladoreavestruc ')
                ->execute()
                ->toArray();

            $result = $resul[0]['cont'];


            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function usandoEav($pId)
    {
        $q = Doctrine_Query::create();

        $result = $q->select('count(e.idnomeav) cont')
            ->from('DatEstructura  e')
            ->innerJoin('e.NomNomencladoreavestruc eav')
            ->where("e.idnomeav ='$pId'")
            ->execute();


        if ($result[0]['cont'] == 0) {
            $result = $q->select('count(op.idnomeav) cont')
                ->from('DatEstructuraop op')
                ->innerJoin('op.NomNomencladoreavestruc eav')
                ->where("op.idnomeav ='$pId'")
                ->execute();
            if ($result[0]['cont'] == 0) {
                $result = $q->select('count(o.idnomeav) cont')
                    ->from('NomOrgano o')
                    ->innerJoin('o.NomNomencladoreavestruc eav')
                    ->where("o.idnomeav ='$pId'")
                    ->execute();
                return ($result[0]['cont'] != 0);
            } else return true;
        } else {
            return true;
        }
    }

}

?>
