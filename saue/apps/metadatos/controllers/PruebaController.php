<?php

class PruebaController extends ZendExt_Controller_Secure
{

    function init()
    {
        parent::init();
    }

    function serviceAction()
    {
        $p = new NomencladorService();
        $pp = $p->DameDatosAgrupaciones(10, 0);
        echo '<pre>';
        print_r($pp);

    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function gethijosAction()
    {
        $p = new IEstructura();
        $pp = $p->getHijosEstructura(1);
        echo '<pre>';
        print_r($pp);

    }

    function listadoestructurasAction()
    {
        $p = new IEstructura();
        $pp = $p->listadoEstructuras(100, 0);
        echo '<pre>';
        print_r($pp);

    }

// ------------------------------prueba de Nomencladores ------------
    // -----------------------------------------------------------------------

    // Devuelve un arreglo con los datos de todos los Nomencladores existentes
    //desde el id numero $start hasta el id $limit

    function datosAction()
    {

        $p = new NomagrupacionesModel();
        $pp = $p->buscarNomAgrupacion(10, 0);
        echo '<pre>';
        print_r($pp);
    }


    //Devuelve true si existe un Nomenclador con un id  dado

    function existeAction()
    {
        $p = new INomencladores();
        $pp = $p->existeAgrupacion(1);
        echo '<pre>';
        print_r($pp);
    }


    // Devuelve el proximo id que va a ser utilizado al insertar un nuevo Nomenclador

    function idAction()
    {
        $p = new INomencladores();
        $pp = $p->getIdProximoAgrupacion();
        echo '<pre>';
        print_r($pp);

    }


    function operaAction()
    {
        $p = new ConsultaDatos();
        $pp = $p->prueba("MINReporte");
        echo '<pre>';
        print_r($pp);

    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function lAction()
    {
        $p = new ICargos();
        $pp = $p->GetDatosMilitares(10, 0);
        echo '<pre>';
        print_r($p);

    }


    function lazaAction()
    {
        /*$p	= new ConsultaDatos();
        $pp	= $p->f_m_rep_localizunidadesporentidad(138,3,6,65,59,58,77,61,78);
        echo '<pre>';
        print_r($pp);*/
        $p = new DattecnicaModel();
        $arreglo = $p->buscardatosTecnica(100000);
        //echo json_encode( array( 'cant' => count( $arreglo ),'datos' => $arreglo) );
        echo '<pre>';
        print_r($arreglo);


    }


}

?>