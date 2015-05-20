<?php


class INomencladores extends ZendExt_Model
{
    // ------------------------------ variables globales --------------------
    // -------------------------------------------------------------------------
    /**
     * Objeto para invocar al nomenclador de Organo
     *
     * @var NomorganoModel
     */
    var $modelorgano;
    /**
     * Objeto para invocar al nomenclador de sub-catgoria
     *
     * @var NomsubcategoriaModel
     */
    var $modelsubcate;
    /**
     * Objeto para invocar al nomenclador de nivel estructural
     *
     * @var NomnivelestrModel
     */
    var $modelnivelestr;
    /**
     * Objeto para invocar al nomenclador de Tipo Cifra
     *
     * @var NomtipocifraModel
     */
    var $modeltpcifras;
    /**
     * Objeto para invocar al nomenclador de Cargo civil
     *
     * @var NomcargocivilModel
     */
    var $modelcargocvil;
    /**
     * Objeto para invocar al nomenclador de Cargi Militar
     *
     * @var NomcargomilitarModel
     */
    var $modelcargomitar;
    /**
     * Objeto para invocar al nomenclador de Categoria civil
     *
     * @var NomcategcivilModel
     */
    var $modelcatcvil;
    /**
     * Objeto para invocar al nomenclador de Categoria Ocupacional
     *
     * @var NomcategoriaocupacionalModel
     */
    var $modelcatocup;
    /**
     * Objeto para invocar al nomenclador de prefijos
     *
     * @var NomprefijoModel
     */
    var $modelpref;
    /**
     * Objeto para invocar al nomenclador de especialidad
     *
     * @var NomEspecialidadModel
     */
    var $modelespcia;
    /**
     * Objeto para invocar al nomenclador de Tecnica
     *
     * @var NomtecnicaModel
     */
    var $modeltecnica;
    /**
     * Objeto para invocar al nomenclador de modulo
     *
     * @var NommoduloModel
     */
    var $modelmodulos;
    /**
     * Objeto para invocar al nomenclador de especialidad
     *
     * @var NomprepmilitarModel
     */
    var $modelpremltar;
    /**
     * Objeto para invocar al nomenclador de agrupaciones
     *
     * @var NomagrupacionesModel
     */
    var $modelagrup;


    // ------------------------------ Constructor --------------------
    // -------------------------------------------------------------------------

    /**
     * Constructor de la interfaz, destinado a reservar para objetos de tipo model
     *
     * @return INomencladores
     */
    function __construct()
    {
        $this->modelorgano = new NomorganoModel();
        $this->modelsubcate = new NomsubcategoriaModel();
        $this->modelnivelestr = new NomnivelestrModel();
        $this->modeltpcifras = new NomtipocifraModel();
        $this->modelcargocvil = new NomcargocivilModel();
        $this->modelcargomitar = new NomcargomilitarModel();
        $this->modelcatcvil = new NomcategcivilModel();
        $this->modelcatocup = new NomcategoriaocupacionalModel();
        $this->modelpref = new NomprefijoModel();
        $this->modelespcia = new NomespecialidadModel();
        $this->modeltecnica = new NomtecnicaModel();
        $this->modelmodulos = new NommoduloModel();
        $this->modelpremltar = new NomprepmilitarModel();
        $this->modelagrup = new NomagrupacionesModel();


    }


    // ------------------------------ Nomenclador de Agrupaciones --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todas las agrupaciones existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosAgrupaciones($limit, $start)
    {

        return $this->modelagrup->buscarNomAgrupacion($limit, $start);
    }

    /**
     * Devuelve true si existe una Agrupacion con un id  igual $idAgrupacion
     *
     *
     * @param int $idAgrupacion
     * @return boolean
     */
    function existeAgrupacion($idAgrupacion)
    {
        return $this->modelagrup->existeNomAgrupacion($idAgrupacion);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva Agrupacion
     *
     * @return int
     */
    function getIdProximoAgrupacion()
    {
        return $this->modelagrup->buscaridproximo();

    }


    // ------------------------------ Nomenclador de prefijos ------------
    // -----------------------------------------------------------------------
    /**
     * Devuelve un arreglo con los datos de todas los prefijos existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosPrefijos($limit, $start)
    {

        return $this->modelpref->buscarNomprefijo($limit, $start);
    }

    /**
     * Devuelve true si existe un prefijo con un id  igual $idprefijo
     *
     *
     * @param int $idprefijo
     * @return boolean
     */
    function existePrefijo($idprefijo)
    {
        return $this->modelpref->existeNomprefijo($idprefijo);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo prefijo
     *
     * @return int
     */
    function getIdProximoPrefijo()
    {
        return $this->modelpref->buscaridproximo();

    }


    // ------------------------------ Nomenclador de especialidad ------------
    // -----------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todas las especialidades existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosEspecialidades($limit, $start)
    {

        return $this->modelespcia->buscarNomespecialidad($limit, $start);
    }

    /**
     * Devuelve true si existe una Especialidad con un id  igual $idespecia
     *
     *
     * @param int $idespecia
     * @return boolean
     */
    function existeEspecialidad($idespecia)
    {
        return $this->modelespcia->existeNomespecialidadId($idespecia);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva especialidad
     *
     * @return int
     */
    function getIdProximoEspecialidad()
    {
        return $this->modelespcia->buscaridproximo();

    }


    // ------------------------------ Nomenclador de Organo ------------
    // -----------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos los organos existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosOrganos($limit, $start)
    {

        return $this->modelorgano->buscarorganos($limit, $start);
    }

    /**
     * Devuelve true si existe un Organo con un id  igual $idorga
     *
     *
     * @param int $idorga
     * @return boolean
     */
    function existeOrgano($idorga)
    {
        return $this->modelorgano->existeNomorgano($idorga);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo organo
     *
     * @return int
     */
    function getIdProximoOrgano()
    {
        return $this->modelorgano->buscaridproximo();

    }

    // ------------------------------ Nomenclador de nivel estructural --------------------
    // -------------------------------------------------------------------------
    /**
     * Devuelve un arreglo con los datos de todos los niveles estructurales existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosNivelEstr($limit, $start)
    {

        return $this->modelnivelestr->buscarNomNivelestr($limit, $start);
    }

    /**
     * Devuelve true si existe un Nivel Estructural con un id  igual $idestr
     *
     *
     * @param int $idestr
     * @return boolean
     */
    function existeNivelEstr($idestr)
    {
        return $this->modelnivelestr->existeNomNivelEstr($idestr);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo Nivel Estructural
     *
     * @return int
     */
    function getIdProximoNivelEstr()
    {
        return $this->modelnivelestr->buscaridproximo();

    }

    // ------------------------------ Nomenclador de sub categorias --------------------
    // -------------------------------------------------------------------------
    /**
     * Devuelve un arreglo con los datos de todos las sub categorias existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosSbCate($limit, $start)
    {

        return $this->modelsubcate->buscarNomsubcategoria($limit, $start);
    }

    /**
     * Devuelve true si existe una sub categoria con un id  igual $idsbcat
     *
     *
     * @param int $idsbcat
     * @return boolean
     */
    function existeSbCate($idsbcat)
    {
        return $this->modelsubcate->existeNomsubcategoriaId($idsbcat);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva sub categoria
     *
     * @return int
     */
    function getIdProximoSbCate()
    {
        return $this->modelsubcate->buscaridproximo();

    }

    // ------------------------------ Nomenclador de tipo de cifras --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos los tipo de cifras existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosTpCifras($limit, $start)
    {

        return $this->modeltpcifras->buscarNomtipocifra($limit, $start);
    }

    /**
     * Devuelve true si existe un tipo de cifras con un id  igual $idtpcifras
     *
     *
     * @param int $idtpcifras
     * @return boolean
     */
    function existeTpCifras($idtpcifras)
    {
        return $this->modeltpcifras->existeNomtipocifra($idtpcifras);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo tipo de cifras
     *
     * @return int
     */
    function getIdProximoTpCifras()
    {
        return $this->modeltpcifras->buscaridproximo();

    }


    // ------------------------------ Nomenclador de categoria civil --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos las categorias civiles existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosCatCvil($limit, $start)
    {

        return $this->modelcatcvil->buscarNomcategcivil($limit, $start);
    }

    /**
     * Devuelve true si existe una categoria civil con un id  igual $idctcvil
     *
     *
     * @param int $idctcvil
     * @return boolean
     */
    function existeCatCvil($idctcvil)
    {
        return $this->modelcatcvil->existeNomcategcivil($idctcvil);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva categoria civil
     *
     * @return int
     */
    function getIdProximoCatCvil()
    {
        return $this->modelcatcvil->buscaridproximo();

    }


    // ------------------------------ Nomenclador de categoria ocupacional --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos las categorias ocupacionales existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosCatOcup($limit, $start)
    {

        return $this->modelcatocup->buscarNomCategoriaOcup($limit, $start);
    }

    /**
     * Devuelve true si existe una categoria ocupacional con un id  igual $idctocup
     *
     *
     * @param int $idctocup
     * @return boolean
     */
    function existeCatOcup($idctocup)
    {
        return $this->modelcatocup->existeNomCategoriaOcupId($idctocup);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva categoria ocupacional
     *
     * @return int
     */
    function getIdProximoCatOcup()
    {
        return $this->modelcatocup->buscaridproximo();

    }


    // ------------------------------ Nomenclador de preparacion militar --------------------
    //---------------------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos las preparaciones militares existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosPrepMltar($limit, $start)
    {

        return $this->modelpremltar->buscarNomPrepmilitar($limit, $start);
    }

    /**
     * Devuelve true si existe una preparacion militar con un id  igual $idpremltar
     *
     *
     * @param int $idpremltar
     * @return boolean
     */
    function existePrepMltar($idpremltar)
    {
        return $this->modelpremltar->existeNomPrepmilitar($idpremltar);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva preparacion militar
     *
     * @return int
     */
    function getIdProximoPrepMltar()
    {
        return $this->modelpremltar->buscaridproximo();

    }


    // ------------------------------ Nomenclador de MODULOS --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos los MODULOS existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosModulo($limit, $start)
    {

        return $this->modelmodulos->buscarNommodulo($limit, $start);
    }

    /**
     * Devuelve true si existe un  MODULO con un id  igual $idmodu
     *
     *
     * @param int $idmodu
     * @return boolean
     */
    function existeModulo($idmodu)
    {
        return $this->modelmodulos->existeNommodulo($idmodu);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo MODULO
     *
     * @return int
     */
    function getIdProximoModulo()
    {
        return $this->modelmodulos->buscaridproximo();

    }

    // ------------------------------ Nomenclador de TECNICA --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos las TECNICAS existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosTecnica($limit, $start)
    {

        return $this->modeltecnica->buscarNomtecnica($limit, $start);
    }

    /**
     * Devuelve true si existe una  TECNICA con un id  igual $idtecni
     *
     *
     * @param int $idtecni
     * @return boolean
     */
    function existeTecnica($idtecni)
    {
        return $this->modeltecnica->existeNomtecnica($idtecni);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar una nueva TECNICA
     *
     * @return int
     */
    function getIdProximoTecnica()
    {
        return $this->modeltecnica->buscaridproximo();

    }

    // ------------------------------ Nomenclador de Cargo Civil --------------------
    // -------------------------------------------------------------------------

    /**
     * Devuelve un arreglo con los datos de todos los Cargos Civiles existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosCargCivl($limit, $start)
    {

        return $this->modelcargocvil->buscarNomcargocivil($limit, $start);
    }

    /**
     * Devuelve true si existe un  Cargo Civil con un id  igual $idcrgcvl
     *
     *
     * @param int $idcrgcvl
     * @return boolean
     */
    function existeCargCivl($idcrgcvl)
    {
        return $this->modelcargocvil->existeNomcargocivil($idcrgcvl);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo Cargo Civil
     *
     * @return int
     */
    function getIdProximoCargCivl()
    {
        return $this->modelcargocvil->buscaridproximo();

    }

    // ------------------------------ Nomenclador de Cargo Militar --------------------
    // -------------------------------------------------------------------------
    /**
     * Devuelve un arreglo con los datos de todos los Cargos Militares existentes
     * desde el id numero $start hasta el id $limit
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    function getDatosCargMltar($limit, $start)
    {

        return $this->modelcargomitar->buscarNomcargomtar($limit, $start);
    }

    /**
     * Devuelve true si existe un  Cargo Militar con un id  igual $idcrgmtar
     *
     *
     * @param int $idcrgmtar
     * @return boolean
     */
    function existeCargMltar($idcrgmtar)
    {
        return $this->modelcargomitar->existeNomcargo($idcrgmtar);
    }

    /**
     * Devuelve el proximo id que va a ser utilizado al insertar un nuevo Cargo Militar
     *
     * @return int
     */
    function getIdProximoCargMltar()
    {
        return $this->modelcargomitar->buscaridproximo();

    }

}

?>