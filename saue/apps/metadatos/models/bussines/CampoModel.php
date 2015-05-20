<?php
class  CampoModel extends ZendExt_Model
{

    public function CampoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomCampoestruc();
    }

    /** -------------------------------------------
     * insertar un nuevo campo en la estructura
     */

    public function insertar($pidnomeav, $pnombre, $ptipo, $plongitud, $pnombre_mostrar, $pregex, $visible, $tipocampo, $descripcion)
    {

        $this->Instancia();

        //-- verificar que no se repita el campo en la tabla
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        $result = $query->from('NomCampoestruc ')
            ->where("idnomeav='$pidnomeav' AND nombre='$pnombre'")
            ->execute()->count();
        if ($result > 0)
            return false;

        //-- buscar el id del proximo campo
        $idCampoNuevo = $this->buscaridproximo();

        //-- insertar los valores
        $this->instance->idcampo = $idCampoNuevo;
        $this->instance->idnomeav = $pidnomeav;
        $this->instance->nombre = $pnombre;
        $this->instance->tipo = $ptipo;
        $this->instance->longitud = $plongitud;
        $this->instance->nombre_mostrar = $pnombre_mostrar;
        $this->instance->regex = $pregex;
        $this->instance->visible = $visible;
        $this->instance->tipocampo = $tipocampo;
        $this->instance->descripcion = $descripcion;

        try {
            $this->instance->save();
            return $idCampoNuevo;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /** -----------------------------------------
     * Eliminar un campo de la base de datos
     *
     * @param int $pCampo
     * @return bool
     */
    public function eliminarCampo($pCampo)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);
            // eliminar el campo
            $result = $query->delete('idcampo')
                ->from('NomCampoestruc')
                ->where("idcampo = '$pCampo'")
                ->execute();
            return $result != 0;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    /** -----------------------------------------
     * Devuelve a que tabla pertenece un campo detereminado
     *
     * @param int $idCampo
     * @return array
     */
    public function buscarTablaPertenece($idCampo)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('t.idnomeav,t.nombre,t.fechaini,t.fechafin,t.root,t.concepto,t.externa,
		c.idcampo,c.idnomeav,c.nombre,c.tipo,c.longitud,c.nombre_mostrar,c.regex,c.descripcion,c.tipocampo,c.visible')
            ->from('NomNomencladoreavestruc t')
            ->innerJoin('t.NomCampoestruc c')
            ->where("c.idcampo='$idCampo'")
            ->execute()
            ->toArray();
        $arreglo = isset($result[0]) ? $result[0] : array();
        return $arreglo;
    }

    /** -----------------------------------------
     * Buscar el proximo id a escribir
     *
     * @return int
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        // eliminar el campo
        $result = $query->select('max(a.idcampo) as maximo')
            ->from('NomCampoestruc a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /** -----------------------------------------
     * Buscar el proximo id a escribir
     *
     * @return int
     */
    public function buscarCampos($limit, $start, $idtabla)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        // eliminar el campo
        $result = $query->select('a.idcampo,a.idnomeav,a.nombre,a.tipo,a.longitud,a.nombre_mostrar,a.regex,a.descripcion,a.tipocampo,a.visible')
            /* $result = $query*/
            ->from('NomCampoestruc a')
            ->where("idnomeav='$idtabla'")
            ->limit($limit)
            ->offset($start)
            ->execute()
            ->toArray();
        return $result;
    }

    function contCampos($idtabla)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->select('count(a.idcampo)  cont')
                ->from('NomCampoestruc a')
                ->where("idnomeav='$idtabla'")
                ->execute()
                ->toArray();
            $result = $result[0]['cont'];
            return $result;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    function b()
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('v.idfila,v.idcampo,v.valor')
            ->from('NomValorestruc v')
            ->where("v.idcampo='1'")
            ->execute()
            ->toArray();
        print_r($result);
    }

    /**
     * Devuelve los valortes que presenta el campo parametrizado en la funcion
     *
     * @param int $idCampo
     * @return array
     */
    function valores($idCampo)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('v.valor')
            ->from('NomValorestruc v')
            ->where("v.idcampo='$idCampo'")
            //->groupBy('v.valor')
            ->execute()
            ->toArray();
        return $result;
    }


    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new NomCampoestruc();
    }

    public function modificar($idcampo, $pidnomeav, $pnombre, $ptipo, $plongitud, $pnombre_mostrar, $pregex, $visible, $tipocampo, $descripcion)
    {
        $this->instance = $this->conn->getTable('NomCampoestruc')->find($idcampo);

        //$this->instance->idnomeav		= $pidnomeav;
        $this->instance->nombre = $pnombre;
        $this->instance->tipo = $ptipo;
        $this->instance->longitud = $plongitud;
        $this->instance->nombre_mostrar = $pnombre_mostrar;
        $this->instance->regex = $pregex;
        $this->instance->visible = $visible;
        $this->instance->tipocampo = $tipocampo;
        $this->instance->descripcion = $descripcion;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    function buscarValoresDefecto($idcampo, $limite = 100)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);
            $result = $query->select('v.idvalordefecto,v.valorid,v.valor,v.idcampo,v.version')
                ->from('NomValorDefecto v')
                ->where("v.idcampo='$idcampo'")
                ->limit($limite)
                ->execute()
                ->toArray();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }
}

?>
