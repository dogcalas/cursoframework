<?php



class IEstructura extends ZendExt_Model
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

    function componenteArbol()
    {
        $dir_rel_app = Zend_Registry::get('config')->uri_aplication;
        $dir_controller = $dir_rel_app . '/metadatos/index.php/estructura/interfazestructura';
        header("Location: $dir_controller");

    }


    function mostrarCamposEstructura($pId)
    {
        return $this->estructura->Mostrarcamposestruc($pId);
    }

    /**
     * -- Devuelve un arreglo con los datos de la estructura
     * si en $idEstructura tiene el valor de "Estructuras" devuelve los que son raices
     *
     * @param int $idEstructura
     * @return array
     */
    function getEstructura($idEstructura)
    {
        return $this->estructura->getEstructuraId($idEstructura);
    }


    /**
     * --Listado de estructuras de forma lineal
     *
     * @param int $limit
     * @param int $start
     * @return unknown
     */
    function listadoEstructuras($limit, $start)
    {
        return $this->estructura->getArrayEstructuras($limit, $start);
    }

    function listadoEstructurasT()
    {
        return $this->estructura->getArrayEstructurasTodas();
    }

    /**
     * --Buscar todos los hijos de una estructura
     * el parametro es false entonce devuelve las raices
     *
     * @param int $idPadre
     * @return array
     */
    function  getHijosEstructura($idPadre)
    {
        return $this->estructura->getHijos($idPadre);
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
    function getEstructurasInternas($idEstructura, $soloRaices = false)
    {
        return $this->estructura->getEstructurasInternas($idEstructura, $soloRaices);
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
    function existeEstructuraop($idEstructura)
    {
        return $this->estructuraop->exiteDatestructuraop($idEstructura);
    }

    /**
     * Devuelve los datos de un area dada
     */
    function getAreasbyId($id)
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
    function listadoEstructurasInternas($limit = 10, $start = 0)
    {
        $this->estructuraop->initConnection('metadatos');
        return $this->estructuraop->buscarDatestructuraop($limit, $start);
    }

    /**
     * --Devuelve los hijos de la estructura ionterna seleccionada
     *
     * @param int $idPadre
     * @return array
     */
    function getHijosInterna($idPadre)
    {
        return $this->estructuraop->getHijos($idPadre);
    }


}

?>