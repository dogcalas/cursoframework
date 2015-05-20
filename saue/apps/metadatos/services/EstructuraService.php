<?php



class EstructuraService extends ZendExt_Model
{
    /**
     * Referencia al modelo para manejar las estructuras
     *
     * @var EstructuraModel
     */
    var $estructura;
    /**
     * Referencia al modelo para manejar las estructuras internas
     *
     * @var EstructuraopModel
     */
    var $estructuraop;


    /**
     * Constructor de la interfaz, destinado a reservar para objetos de tipo model
     *
     * @return IEstructura
     */
    function __construct()
    {
        $this->estructura = new EstructuraModel();
        $this->estructuraop = new EstructuraopModel();
    }

    /**
     * Componente que mustra la estructura arbolica.
     *
     */

    public static function ComponenteArbol()
    {
        $dir_rel_app = Zend_Registry::get('config')->uri_aplication;
        $dir_controller = $dir_rel_app . '/metadatos/index.php/estructura/interfazestructura';
        header("Location: $dir_controller");

    }


    /**
     * Obtener el id del padre de un estructura.
     */
    function IdPadre($pId)
    {
        $arraydata = $this->estructura->getPadre($pId);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Obterner el id y el tipo del padre
     *
     */
    function ObtenerTipoPadre($pIdhijo, $pTipo)
    {
        $arraydata = $this->estructura->getInfoPadre($pIdhijo, $pTipo);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $pId
     * @return array
     */
    function MostrarCamposEstructura($pId, $campo)
    {
        $arraydata = $this->estructura->Mostrarcamposestruc($pId, $campo);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * -- Devuelve un arreglo con los datos de la estructura
     * si en $idEstructura tiene el valor de "Estructuras" devuelve los que son raices
     *
     * @param int $idEstructura
     * @return array
     */
    function DameEstructura($idEstructura)
    {
        $arraydata = $this->estructura->getEstructuraId($idEstructura);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    function DameEstructuraSeguridad($idEstructura)
    {
        return $this->estructura->DameEstructuraSeguridad($idEstructura);
    }


    /**
     * --Listado de estructuras de forma lineal
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function ListadoEstructuras($limit, $start)
    {
        $arraydata = $this->estructura->getArrayEstructuras($limit, $start);

        $result = ZendExt_ClassStandard::ArrayObject($arraydata['datos']);

        return array('cant' => $arraydata['cant'], 'datos' => $result);


    }

    /**
     * Enter description here...
     *
     * @return array
     */
    function ListadoEstructurasT()
    {   

        $arraydata = $this->estructura->getArrayEstructurasTodas();
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * --Buscar todos los hijos de una estructura
     * el parametro es false entonce devuelve las raices
     *
     * @param int $idPadre
     * @return array
     */
    function  DameHijosEstructura($idPadre)
    {
        $arraydata = $this->estructura->getHijos($idPadre);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /** --------------------------------------------
     * --Obtener las estructuras internas dada una estructura
     * si se activa el segundo parametro devyuelve solo las que estan
     * directamente subordinadas a la estructura
     * central
     *
     * @param int $idEstructura
     * @param bool $soloRaices
     *
     * @return array
     */
    function DameEstructurasInternas($idEstructura, $soloRaices = false)
    {
        $arraydata = $this->estructura->getEstructurasInternasServicio($idEstructura, $soloRaices);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }


    /**
     * ----------------- Trabajo con las estructuras internas ------------
     */

    /**
     * Verifica si existe una estructura interna dado su ID
     *
     * @param int $idEstructura
     * @return bool
     */
    function ExisteEstructuraOp($idEstructura)
    {
        return $this->estructuraop->exiteDatestructuraop($idEstructura);
    }

    /**
     * Devuelve los datos de un area dada
     */
    function DameAreasPorId($id)
    {
        return $this->estructuraop->buscarEstructurabyId($id);
    }


    /**
     * --Devuelve un listado lineal de estructurasinternas
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function ListadoEstructurasInternas($limit = 10, $start = 0)
    {
        $this->estructuraop->initConnection('metadatos');
        $arraydata = $this->estructuraop->buscarDatestructuraop($limit, $start);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata['datos']);

        return array('cant' => $arraydata['cant'], 'datos' => $result);
    }

    /**
     * --Devuelve los hijos de la estructura ionterna seleccionada
     *
     * @param int $idPadre
     * @return array
     */
    function DameHijosInterna($idPadre)
    {
        $arraydata = $this->estructuraop->getHijos($idPadre);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    //----Campos de las estructuras para seguridad--///////////////////
    function MostrarCamposEstructuraSeguridad($idestructura)
    {
        return $this->estructura->MostrarCamposEstructuraSeguridad($idestructura);
    }

    function DameEstructurasinChecked($idEstructura)
    {
        return $this->estructura->DameEstructurasinChecked($idEstructura);
    }

    /**
     * --Devuelve los hijos de la estructura ionterna seleccionada
     *
     * @param int $idPadre
     * @return array
     */
    function DameHijosInternaSeguridad($idPadre)
    {
        return $this->estructuraop->DameHijosInternaSeguridad($idPadre);
    }

    /** --------------------------------------------
     * --Obtener las estructuras internas dada una estructura
     * si se activa el segundo parametro devyuelve solo las que estan
     * directamente subordinadas a la estructura
     * central
     *
     * @param int $idEstructura
     * @param bool $soloRaices
     *
     * @return array
     */
    function DameEstructurasInternasSeguridad($idEstructura, $soloRaices = false)
    {
        return $this->estructura->DameEstructurasInternasSeguridad($idEstructura, $soloRaices);
    }

    function DameEstructurasInternasSeguridadSinCheked($idEstructura, $soloRaices = false)
    {
        return $this->estructura->DameEstructurasInternasSeguridadSinCheked($idEstructura, $soloRaices);
    }

    /* Devuelve los datos de un area dada dado id
     */
    function EstructurasInternasDadoIDSeguridad($idarea)
    {
        return $this->estructuraop->EstructurasInternasDadoIDSeguridad($idarea);
    }

    function DameHijosInternaSeguridadSinCheked($idPadre)
    {
        return $this->estructuraop->DameHijosInternaSeguridadSinCheked($idPadre);
    }

    function cantidadEstructuras()
    {
        return $this->estructura->cantidadEstructuras();
    }

    function ObtenerDatosEstructurasPorArrayId($estructuras)
    {
        return $this->estructura->getEstructurasPorId($estructuras);
    }

    /* Verifica si existe alg�n �rea de responsabilidad que este asociada a centros de costos ,c3
    */
    function ExistenNoAsociadas($idAreasArray)
    {
        return $this->estructura->ExistenNoAsociadas($idAreasArray);
    }
}

?>
