<?php



class IDominio extends ZendExt_Model
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
    function insertardominio($nombre)
    {
        return $this->dominio->insertar($nombre);
    }


    /**
     * Elimina un dominio
     *
     * @param int $iddominio
     * @return bool
     */
    function eliminardominio($iddominio)
    {

        return $this->dominio->eliminarDominio($iddominio);
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
        return $this->dominio->BuscarDomio($limint, $start);
    }

    /**
     * Adiciona o anade una estructura a un dominio si esta no estaba adocionada anteriormente
     *
     * @param int $iddominio
     * @param int $idestructura
     * @return bool
     */
    function anadirestructuraAction($iddominio, $idestructura)
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

    function extraer($iddominio, $idestructura)
    {
        return $this->dominio->extraerEstructura($iddominio, $idestructura);
    }


    /**
     * Verificar si esta la estructura en un dominio dado
     *
     * @param int $iddominio
     * @param int $idestructura
     * @return bool
     */
    function estaestructuraendominio($iddominio, $idestructura)
    {

        return $this->dominio->perteneceEstructura($iddominio, $idestructura);
    }


    /**
     * Lista las estructuras que estan contenidas en un dominio
     *
     * @param int $iddominio
     * @return array
     */
    function listarestructuras($iddominio)
    {
        return $this->dominio->BuscarEstructuras($iddominio);
    }

    /**
     * Devuelve los dominios en los ue esta contenido una estructura pasada por parametro
     *
     * @param int $idestructura
     * @return array
     */
    function dominiosporestructura($idestructura)
    {
        return $this->dominio->dominiosEstructura($idestructura);
    }

    function buscarHijosEstructuras($iddominio = false, $idestructura = false, $checked = false, $checkear = false, $arrayTiposEAV = false)
    {
        return $this->dominio->buscarHijosEstructuras($iddominio, $idestructura, $checked, $checkear, $arrayTiposEAV);
    }

    function buscarArbolHijosEstructuras($idestructura)
    {
        return $this->dominio->buscarArbolHijosEstructuras($idestructura);
    }

    function buscarHijosEstructurasDadoArray($iddominio, $idestructura = false, $checked = false, $arrayIdestructura = false)
    {
        return $this->dominio->buscarHijosEstructurasDadoArray($iddominio, $idestructura, $checked, $arrayIdestructura);
    }

    function buscarIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV)
    {
        return $this->dominio->buscarIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV);
    }

    function cantIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV)
    {
        return $this->dominio->cantIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV);
    }

    function cargarArbolDominios($iddominio)
    {
        return $this->dominio->cargarArbolDominios($iddominio);
    }

    function buscarArbolHijosDominio($arrayPadres)
    {
        return $this->dominio->buscarArbolHijosDominio($arrayPadres);
    }
}

?>
