<?php



class DominioService extends ZendExt_Model
{
    /**
     * Enter description here...
     *
     * @var DominioModel
     */
    var $dominio;


    /**
     * Constructor de la interfaz, destinado a reservar para objetos de tipo model
     *
     * @return IDominio
     */
    function __construct()
    {
        $this->dominio = new DominioModel();

    }


    /**
     * Inserta un dominio nuevo
     *
     * @param string $nombre
     *
     * @return id dominio.
     */
    function InsertarDominio($nombre, $descripcion, $arrayEstructuras)
    {
        return $this->dominio->InsertarDominio($nombre, $descripcion, $arrayEstructuras);
    }

    function ModificarDominio($iddominio, $nombre, $descripcion, $arrayEstructuras)
    {
        return $this->dominio->ModificarDominio($iddominio, $nombre, $descripcion, $arrayEstructuras);
    }

    /**
     * Elimina un dominio
     *
     * @param int $iddominio
     * @return bool
     */
    function EliminarDominio($iddominio)
    {

        return $this->dominio->EliminarDominio($iddominio);
    }

    /**
     * Lista todos los dominios existentes en un rango especificado
     *
     * @param int $limint
     * @param int $start
     * @return array
     */
    function BuscarDomio($limint, $start)
    {
        $hijos = $this->arregloToUnidimensional($this->dominio->BuscarIdDominioHijos());
        $padre = $this->arregloToUnidimensional($this->dominio->BuscarIdDominioPadre());
        $arrayResult = array_unique(array_merge($hijos, $padre));
        $arrayResult = array_diff($arrayResult, array('1'));
        if (count($arrayResult))
            return $this->dominio->BuscarDomios($limint, $start, $arrayResult);
        else
            return array('cant' => 0, 'datos' => array());
    }

    /**
     * Lista todos los dominios existentes en un rango especificado
     *
     * @param int $limint
     * @param int $start
     * @return array
     */
    function BuscarComboDominio($limint, $start)
    {
        $hijos = $this->arregloToUnidimensional($this->dominio->BuscarIdDominioHijos());
        $padre = $this->arregloToUnidimensional($this->dominio->BuscarIdDominioPadre());
        $arrayResult = array_unique(array_merge($hijos, $padre));
        if (count($arrayResult))
            return $this->dominio->BuscarDomios($limint, $start, $arrayResult);
        else
            return array('cant' => 0, 'datos' => array());
    }

    function cargarComboDominioBuscar($filtroDominio)
    {
        return $this->dominio->cargarComboDominioBuscar($filtroDominio);
    }

    function arregloToUnidimensional($array)
    {
        $result = array();
        foreach ($array as $iddominio)
            $result[] = $iddominio['iddominio'];
        return $result;
    }

    function BuscarDominios($limint, $start)
    {
        return $this->dominio->BuscarDominios($limint, $start);
    }

    public function BuscarDomioDadoNombre($limit, $start, $nombredominio)
    {
        $global = ZendExt_GlobalConcept::getInstance();
        $iddominiopadre = $global->Perfil->iddominio;
        $cant = NomDominio::cantNomDominioDadoNombre($iddominiopadre, $nombredominio);
        $result = NomDominio::BuscarDomioDadoNombre($iddominiopadre, $limit, $start, $nombredominio);
        return array('cant' => $cant, 'datos' => $result);
    }

    public function existeNombreDominio($nombredominio, $iddominio)
    {
        return NomDominio::existeNombreDominio($nombredominio, $iddominio);
    }

    public function verificarNombreDominio($nombreDominio)
    {
        return NomDominio::verificarNombreDominio($nombreDominio);
    }

    function DatosDominioDadoID($iddominio)
    {
        $arraydominio = $this->dominio->DatosDominioDadoID($iddominio);
        return $arraydominio;
    }

    function BuscarIdDominioSeguridad()
    {

        return $this->dominio->BuscarIdDominioSeguridad();
    }

    /**
     * Adiciona o anade una estructura a un dominio si esta no estaba adocionada anteriormente
     *
     * @param int $iddominio
     * @param int $idestructura
     * @return bool
     */
    function AnadirEstructuraAction($iddominio, $idestructura)
    {
        return $this->dominio->anadirEstructura($iddominio, $idestructura);
    }

    /**
     * Extrae una extructura de un dominio
     *
     * @param int $iddominio
     * @param int $idestructura
     * @return bool
     */

    function ExtraerEstructura($iddominio, $idestructura)
    {
        return $this->dominio->ExtraerEstructura($iddominio, $idestructura);
    }


    /**
     * Verificar si esta la estructura en un dominio dado
     *
     * @param int $iddominio
     * @param int $idestructura
     * @return bool
     */
    function EstaEstructuraEnDominio($iddominio, $idestructura)
    {

        return $this->dominio->perteneceEstructura($iddominio, $idestructura);
    }


    /**
     * Lista las estructuras que estan contenidas en un dominio
     *
     * @param int $iddominio
     * @return array
     */
    function ListarEstructuras($iddominio)
    {
        $arraydata = $this->dominio->ListarEstructuras($iddominio);
        return $arraydata;
    }

    function ListarEstructurasDadoArrayId($ArrayIddominio)
    {
        $arraydata = $this->dominio->ListarEstructurasDadoArrayId($ArrayIddominio);
        return $arraydata;
    }

    function ListarEstructurasExternasDadoArrayId($ArrayIddominio)
    {
        if (count($ArrayIddominio) == 0) return array();
        $arraydata = $this->dominio->ListarEstructurasExternasDadoArrayId($ArrayIddominio);
        return $arraydata;
    }

    function ListarEstructuraSinCheked($iddominio)
    {
        $arraydata = $this->dominio->ListarEstructuraSinCheked($iddominio);
        return $arraydata;
    }

    function ObtenerEstructurasNoFormales($iddominio)
    {
        return $this->dominio->ObtenerEstructurasNoFormales($iddominio);
    }

    /**
     * Devuelve los dominios en los ue esta contenido una estructura pasada por parametro
     *
     * @param int $idestructura
     * @return array
     */
    function DominiosPorEstructura($idestructura)
    {
        return $this->dominio->DominiosPorEstructura($idestructura);
    }

    function IdEstructurasDominio($iddominio)
    {
        return $this->dominio->arregloEstructuras($iddominio);
    }

    function ObtenerEstructurasPorIdOrgano($iddominio, $param)
    {
        return $this->dominio->ObtenerEstructurasPorIdOrgano($iddominio, $param);
    }

    function cantidadDominios()
    {
        return $this->dominio->cantidadDominios();
    }


}

?>
