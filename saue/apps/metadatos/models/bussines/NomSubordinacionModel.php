<?php
class  NomSubordinacionModel extends ZendExt_Model
{

    public function NomSubordinacionModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomSubordinacion();
    }

    /** -------------------------------------------
     * insertar un nuevo campo en la estructura
     */

    public function insertar($denom, $abreviatura)
    {

        $this->Instancia();


        //-- buscar el id del proximo campo
        $idCampoNuevo = $this->buscaridproximo();

        //-- insertar los valores
        $this->instance->idnomsubordinacion = $idCampoNuevo;
        $this->instance->denominacion = $denom;
        $this->instance->abreviatura = $abreviatura;


        try {
            $this->instance->save();
            return $idCampoNuevo;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //die(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);
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
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
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
        $result = $query->select('max(a.idnomsubordinacion) as maximo')
            ->from('NomSubordinacion a')
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
        $result = $query->select('a.*')
            ->from('NomSubordinacion a')
            ->where("idnomsubordinacion='$idtabla'")
            ->limit($limit)
            ->offset($start)
            ->execute()
            ->toArray();
        return $result;
    }

    function b()
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('v.*')
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
                //die(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }
    }

    function buscarNomSubordinacion($limite = 100)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('*')
            ->from('NomSubordinacion v')
            ->limit($limite)
            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
            ->execute();

        return $result;

    }

    function buscarNomSubordinacionSB($limite = 100)
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        $result = $query->select('*')
            ->from('NomSubordinacion v')
            ->limit($limite)

            ->execute()
            ->toArray();
        return $result;


    }
}

?>
