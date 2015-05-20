<?php

/**
 * Enter description here...
 *
 * @author El Kangri
 * @package defaultPackage
 */

class ConsultaDatos extends ZendExt_Model
{

    public function  __construct()
    {

        parent::ZendExt_Model();

    }


    /* Se le da solucion al reporte Detalles del calificador de cargos */

    public function f_m_rep_calificadorcargos($idCalif)
    {
        //3
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_calificadorcargos\"('$idCalif')
                AS (cod varchar,deno_cargo varchar,cat_ocup varchar,grup_comple varchar,tareasFuncio varchar,nvelutili varchar,requis varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /*Da solucion al reporteResumen de cargos  por areas y categoria
    ocupacional, dada una estructura devuelve la denominacion de las
    areas y la cantidad de cargos por areas*/
    public function f_m_rep_cargosareascategocupacional($idEstruc)
    {
        //4
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_cargosareascategocupacional\"('$idEstruc')
                 AS (deno varchar,total_cargos numeric,cant_cargo_cat_ocupacional numeric,abre varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /*Solucion al reporte Reporte Resumen de Cargos por Grupos de Complejidad y Categor�a Ocupacional */
    public function f_m_rep_cargosporgrupocomple($IdNivel1)
    {
        //108
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_cargosporgrupocomple\"('$IdNivel1')
                 AS (abre varchar,dencate varchar,cant numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Solucion al reporte Relacion de la localizacion de las unidades por nivel
estructural   */
    public function f_m_rep_localizacionunidadesnivel($idPadre, $EavUnidad, $idorganotipo1, $IdCampoDomincilio, $IdCampoLocalidad, $IdCampoMunicipio, $IdCampoProvincia, $IdCampoTelefPizarra, $IdCampoExtensionDirecc, $IdCampoFax, $IdCampoEmail)
    {

        //138,6,8080000011,65,64,59,58,60,77,61,78
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_localizacionunidadesnivel\"('$idPadre','$EavUnidad','$idorganotipo1','$IdCampoDomincilio','$IdCampoLocalidad','$IdCampoMunicipio','$IdCampoProvincia','$IdCampoTelefPizarra','$IdCampoExtensionDirecc','$IdCampoFax','$IdCampoEmail')
                 AS (idestr numeric,denominacion varchar,abreviatura varchar,domicilio varchar,localidad varchar,municipio varchar,provincia  varchar,telefono_pizarra  varchar,extencion  varchar,telef_direccion  varchar,fax varchar,email varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Solucion al reporte Relacion
de la localizacion de las unidades por entidad*/

    public function f_m_rep_localizunidadesporentidad($IdNivel, $idEavEntidad, $IdEavUnidad, $IdCampoDomincilio, $IdCampoMunicipio, $IdCampoProvincia, $IdCampoTlefono, $IdCampoFax, $IdCampoEmail)
    {

        //138,3,6,65,59,58,77,61,78
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_localizunidadesporentidad\"('$IdNivel','$idEavEntidad','$IdEavUnidad','$IdCampoDomincilio','$IdCampoMunicipio','$IdCampoProvincia','$IdCampoTlefono','$IdCampoFax','$IdCampoEmail')
                 AS (deno_nivel1_titulo varchar,idestr numeric,deno_entidades varchar,deno_unidades varchar,abreviatura varchar,domicilio_legal  varchar,localidad  varchar,municipio  varchar,provincia  varchar,tel�fono_pizarra  varchar,email  varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /*Solucion al reporte Relación de Niveles 1 según su Clasificación.*/

    public function f_m_rep_nivel1porclasif($IdNivel1, $IdAgrupaciones, $IdEntidad)
    {
        //1,1,1
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_nivel1porclasif\"('$IdNivel1','$IdAgrupaciones','$IdEntidad')
                 AS (denoOrg varchar,abreviatura varchar,denominacion varchar,total_agrupacion numeric,total_entidades  numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /*Solucion al reporte Reporte Plantilla de Cargos.*/
    public function f_m_rep_plantillacargos()
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_plantillacargos\"()
                 AS (deno varchar,dencargociv varchar,cant_cargos numeric,abre varchar,abre1 varchar,abrevtecn  varchar,abrevespecia  varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /*Relacion de entidades
en perfeccionamiento empresarial por agrupacion.*/

    public function f_m_rep_relacion_entidades_perfeccionamiento($ideavnivel, $ideavagrupacion, $ideaventidad, $idcampoperfeccionamiento, $idcampocodone, $idcampocategoria, $idcampopagoadicional)
    {

        //138,2,3,74,74,74,74
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_relacion_entidades_perfeccionamiento\"('$ideavnivel','$ideavagrupacion','$ideaventidad','$idcampoperfeccionamiento','$idcampocodone','$idcampocategoria','$idcampopagoadicional')
                 AS (deno_nivel1_titulo varchar,estructura numeric,deno_agrupacion varchar,deno_entidades varchar,abreviatura varchar,cod_one varchar,categoria varchar,aplica_perfeccionamiento varchar,aplica_pago_adicional varchar)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Funcion para darle solucion alReporteRelacion
de Agrupaciones por Nivel 1., listo para funcionar*/

    public function f_m_rep_relacionagruppornivel1($EavNivel1, $EavAgrup, $EavEntida, $IdCampoCategoria)
    {
        //1,2,3,57
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_relacionagruppornivel1\"('$EavNivel1','$EavAgrup','$EavEntida','$IdCampoCategoria')
                 AS (deno_nivel1 varchar,idestr numeric,denominacion varchar,abreviatura varchar,tipo_estructura varchar,categoria varchar,totalEntidades numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Funcion para darle solucion al reporte
Relacion de entidades por agrupacion*/


    public function f_m_rep_relacionentidadesporagrupaciones($IdNivel, $EavAgrup, $EavEntidad, $EavUnidad, $IdcampoCategoria, $idcampoActivEcon)
    {

        //138,2,3,6,68,74
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_relacionentidadesporagrupaciones\"('$IdNivel','$EavAgrup','$EavEntidad','$EavUnidad','$IdcampoCategoria','$idcampoActivEcon')
                 AS (deno_titulo varchar,idestr numeric,deno_agrupacion varchar,denominacion varchar,abreviatura varchar,tipo_estructura varchar,categoria varchar,act_economica varchar,total numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Funcion para darle solucion al reporte
Resumen de agrupaciones por nivel 1 segun su clasificacion */

    public function f_m_rep_resumen_agrupaciones_nivel_segun_clasificacion($IdNivel1, $ideavagrupacion, $ideaventidad, $ideavunidad)
    {
        //138,2,3,6
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_resumen_agrupaciones_nivel_segun_clasificacion\"('$IdNivel1','$ideavagrupacion','$ideaventidad','$ideaventidad')
                 AS (clasificacion varchar,total_agrupacion numeric,total_entidades numeric,total_unidades numeric)");

            return $res;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* funcion para darle solucion al reporte
Resumen de categoria de las entidades por agrupaciones */

    public function f_m_rep_resumen_categoria_entidades_agrupaciones($IdNivel, $ideaventidadp, $idcampocategoria, $ideavagrupacion)
    {
        //138,3,57,2
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_resumen_categoria_entidades_agrupaciones\"('$IdNivel','$ideaventidadp','$idcampocategoria','$ideavagrupacion')
                 AS (nivel_titulo varchar, abervgrupo varchar , nombregruppo varchar , entidades numeric, cat1 numeric, cat2 numeric,cat3 numeric, cat4 numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Funcion para darle solucion al reporte Resumen
de entidades en perfeccionamiento empresarial */

    public function f_m_rep_resumen_entidade_perfeccionamiento_empresarial($IdNivel1, $ideaventidad, $idcampoperfeccionamiento, $ideavagrupacion)
    {
        //138,3,57,2
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_resumen_entidade_perfeccionamiento_empresarial\"('$IdNivel1','$ideaventidad','$idcampoperfeccionamiento','$ideavagrupacion')
                 AS (denominacion_nivel varchar,abreviatura_nivel varchar,abreviatura_grupo varchar,denominacion_grupo varchar,total_entidades numeric,total_entidades_perfeccionamiento numeric,total_entidades_pagos_adicionales numeric)");

            return $res;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    /* Funcion para darle solucion al reporte Resumen
    de entidades por agrupacion segun su clasificacion */

    public function f_m_rep_resumentidporagrupaciporclasificac($IdNivel1, $IdEavAgrup, $IdNomOrgaEstatal, $IIdNomOrgaMixta, $IdNomOrgaCAEI, $IdCampoActEcEmpr, $IdEavEntidad)
    {

        //138,2,8080000013,13,14,15,3
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_m_rep_resumentidporagrupaciporclasificac\"('$IdNivel1','$IdEavAgrup','$IdNomOrgaEstatal','$IIdNomOrgaMixta','$IdNomOrgaCAEI','$IdCampoActEcEmpr','$IdEavEntidad')
                 AS (clasificacion varchar,abre_nivel_titulo varchar,abreviatura_grupo varchar,denominacion varchar,total_entidades numeric,total_entidades_estatales numeric,total_entidades_mixta numeric,total_CAEI numeric,total_economica_empresarial numeric,total_economica_presupuestada numeric)");

            return $res;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

    public function prueba($IdNivel1)
    {

        //138,2,8080000013,13,14,15,3
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');

            $res = $conn->fetchAll("select * from \"f_rep_relacion_registro_entidades_agrupacion\"('$IdNivel1')");

            return $res;

        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }

}

?>