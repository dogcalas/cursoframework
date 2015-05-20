<?php



class SubordinacionService extends ZendExt_Model
{
    /**
     * Referencia al modelo para manejar las estructuras
     *
     * @var EstructurasbModel
     */
    var $estructura;
    /**
     * Referencia al modelo para manejar las estructuras internas
     *
     * @var EstructuraopsbModel
     */
    var $estructuraop;


    /**
     * Constructor de la interfaz, destinado a reservar para objetos de tipo model
     *
     * @return IEstructura
     */
    function __construct()
    {
        $this->estructura = new EstructurasbModel();
        $this->estructuraop = new EstructuraopsbModel();
    }

    /**
     * Componente que mustra la estructura arbolica.
     *
     */

    public static function ComponenteArbol()
    {
        $dir_rel_app = Zend_Registry::get('config')->uri_aplication;
        $dir_controller = $dir_rel_app . '/metadatos/index.php/estructurasb/gestionarestructura';
        header("Location: $dir_controller");

    }


    /**
     * Obtener el id del padre de un estructura.
     */
    function getPadre($pId, $idsubordinacion)
    {
        $arraydata = $this->estructura->getPadre($pId, $idsubordinacion);
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
    function DameHijosEstructura($idEstructura, $tipoSubordinacion)
    {
        $arraydata = $this->estructura->getHijos($idEstructura, $tipoSubordinacion);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }


}

?>
