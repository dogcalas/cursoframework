<?php

class NomencladorController extends ZendExt_Controller_Secure
{

    function init()
    {
        parent::init();
    }

    function gestionarnomencladorAction()
    {
        $this->render();
    }

    //-------------------------------Nomenclador de Agrupaciones-----------------
    //---------------------------------------------------------------------------


    //-------------------------------Nomcalificador------------------------------
    //---------------------------------------------------------------------------
    function verificarcomboclasificadordecargosAction()
    {
        $modelNomcat = new NomcategoriaocupacionalModel();
        $categoriaop = $modelNomcat->cantNomCategoriaOcup();
        if ($categoriaop == 0)
            $categoriaop = 'categoria ocupacional';
        else
            $categoriaop = '';
        $modeltipocalif = new NomtipocalificadorModel();
        $tipoclif = $modeltipocalif->contNomtipocalificador();
        if ($tipoclif == 0)
            $tipoclif = 'tipo de calificador de cargo.';
        else
            $tipoclif = '';

        $band = 0;
        if ($categoriaop != '') {
            $mensaje = $categoriaop . '.<br/>';
            $band = 1;
        }
        if ($tipoclif != '') {
            $mensaje = $mensaje . $tipoclif . '.';
            $band = 1;
        }


        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");

    }


    function mostrarcalificadorAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelcalif = new NomcalificadorModel();
        $total = $modelcalif->contNomcalificador($denom);
        $tablas = $modelcalif->buscarNomcalificador($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    /**
     * SUBORDINACIONES
     * */
    function mostrarTipoSubordinacionAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $denominacion = $this->_request->getPost('den');
        //print_r($denominacion);echo (':den:');die;
        //$denominacion = $denominacion != '' ? $denominacion : false;

        $subordinacionModel = new NomSubordinacionModel();

        $total = $subordinacionModel->contarNomSubordinacion($denominacion);
        $tablas = $subordinacionModel->obtenerNomSubordinacion($limit, $start, $denominacion);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    function insertarTipoSubordinacionAction()
    {
        $abreviatura = $this->_request->getPost('abrev');
        $denominacion = $this->_request->getPost('denom');
        $fechaFin = $this->_request->getPost('ffin');
        $fechaIni = $this->_request->getPost('fini');
        $orden = $this->_request->getPost('orden');
        $color = $this->_request->getPost('color');

        $subordinacionModel = new NomSubordinacionModel();

        if ($subordinacionModel->insertarTipoSubordinacion($abreviatura, $denominacion, $orden, $color))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function modificarTipoSubordinacionAction()
    {
        $abreviatura = $this->_request->getPost('abrev');
        $denominacion = $this->_request->getPost('denom');
        $fechaFin = $this->_request->getPost('ffin');
        $fechaIni = $this->_request->getPost('fini');
        $orden = $this->_request->getPost('orden');
        $id = $this->_request->getPost('idnomsubordinacion');
        $color = $this->_request->getPost('color');

        $subordinacionModel = new NomSubordinacionModel();

        if ($subordinacionModel->modificarTipoSubordinacion($id, $abreviatura, $denominacion, $orden, $color))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarTipoSubordinacionAction()
    {
        $id = $this->_request->getPost('idnomsubordinacion');

        $subordinacionModel = new NomSubordinacionModel();

        if ($subordinacionModel->eliminarTipoSubordinacion($id))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    //------------------------------------------------------------------

    function insertarcalificadorAction()
    {

        $idtipocalifica = $this->_request->getPost('idtipocalificador');
        $idcategoriaocup = $this->_request->getPost('idcategocup');
        $dencalificador = $this->_request->getPost('denom');
        $abrevcalificador = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $codigo = $this->_request->getPost('codigo');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelNomgrad = new NomcalificadorModel();
        /*      if($modelNomgrad->exiteNomcalificadorDenomAbrev($dencalificador, $abrevcalificador)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                   return;
                }*/
        if ($modelNomgrad->insertarNomcalificador($idcategoriaocup, $idtipocalifica, $dencalificador, $abrevcalificador, $orden, $codigo, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    //*******************************************************************************************
    function modificarcalificadorAction()
    {

        $idcalificador = $this->_request->getPost('idcalificador');
        $idtipocalifica = $this->_request->getPost('idtipocalificador');
        $idcategoriaocup = $this->_request->getPost('idcategocup');
        $dencalificador = $this->_request->getPost('denom');
        $abrevcalificador = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $codigo = $this->_request->getPost('codigo');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelcalif = new NomcalificadorModel();
        if ($modelcalif->modificarNomcalificador($idcalificador, $idcategoriaocup, $idtipocalifica, $dencalificador, $abrevcalificador, $orden, $codigo, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        //else
        //echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }


    function eliminarcalificadorAction()
    {


        $idcalif = $this->_request->getPost('idcalificador');
        $modelcalif = new NomcalificadorModel();
        /*if ($modelcalif->usandoNomcalificador($idcalif))
		  echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque posee asignaciones en el nomenclador de cargo civil.'}");
		  else {*/
        if ($modelcalif->eliminarNomcalificador($idcalif))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");


        // }


    }

    // ------------------------------ Nomenclador de prefijos ------------
    // -----------------------------------------------------------------------
    /**
     *
     *
     */
    function mostrarprefijoAction()
    {


        $limit = ($limit != 0) ? $limit : 10;
        $start = ($start != 0) ? $start : 0;

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $modelNomPref = new NomprefijoModel();
        $total = $modelNomPref->cantNomprefijo();
        $tablas = $modelNomPref->buscarNomprefijo($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    function insertarprefijoAction()
    {

        $prefijo = $this->_request->getPost('prefijo');
        $desclugar = $this->_request->getPost('lugar');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $orden = $this->_request->getPost('orden');
        $modelNomPref = new NomprefijoModel();
        if ($modelNomPref->insertarNomprefijo($prefijo, $desclugar, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    function modificarprefijoAction()
    {

        $idprefijo = $this->_request->getPost('idpref');
        $prefijo = $this->_request->getPost('prefijo');
        $desclugar = $this->_request->getPost('lugar');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $orden = $this->_request->getPost('orden');
        $modelNomPref = new NomprefijoModel();
        if ($modelNomPref->modificarNomprefijo($idprefijo, $prefijo, $desclugar, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error Modificando.'}");

    }


    function eliminarprefijoAction()
    {

        $idprefijo = $this->_request->getPost('idpref');
        $modelNomEsp = new NomprefijoModel();
        if ($modelNomEsp->eliminarNomprefijo($idprefijo))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'No se pudo eliminar.'}");

    }


    //--------------------------nomenlador tipo de calificador-------------------------------

    function mostrartipocalificadorAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modeltipocalif = new NomtipocalificadorModel();
        $total = $modeltipocalif->contNomtipocalificador($denom);
        $tablas = $modeltipocalif->buscarNomtipocalificador($limit, $start, $denom);


        $mostrar = array('cant' => $total, 'datos' => $tablas);
        echo(json_encode($mostrar));

    }

    function insertartipocalificadorAction()
    {


        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');


        $modeltipocalif = new NomtipocalificadorModel();
        if ($modeltipocalif->insertarNomtipocalificador($denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function modificartipocalificadorAction()
    {

        $idtipocalificador = $this->_request->getPost('idtipocalificador');
        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');


        $modeltipocalif = new NomtipocalificadorModel();

        if ($modeltipocalif->modificarNomtipocalificador($idtipocalificador, $denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminartipocalificadorAction()
    {


        $idtipocalificador = $this->_request->getPost('idtipocalificador');
        $modeltipocalif = new NomtipocalificadorModel();

        if ($modeltipocalif->eliminarNomtipocalificador($idtipocalificador))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    function verificarcombogradomilitarAction()
    {
        $modelNomcat = new NomsubcategoriaModel();
        $subcateg = $modelNomcat->cantNomsubcategoria();
        if ($subcateg == 0)
            $subcateg = 'subcategor&iacute;a';
        else
            $subcateg = '';
        $band = 0;
        if ($subcateg != '') {
            $mensaje = $subcateg;
            $band = 1;
        }
        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje.','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");


    }

    // ------------------------------ nomenclador de grado militar  ------------
    // -----------------------------------------------------------------------
    /**
     * muestra las especialidades existentes en la base de datos
     *
     */
    function mostrargradomilitarAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomgrad = new NomgradomilitarModel();
        //$total			= $modelNomgrad->cantGradomtar();
        $tablas = $modelNomgrad->buscarGradomtar($limit, $start, $denom);

        $mostrar = array('cant' => $modelNomgrad->cantGradomtar($denom), 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    function insertargradomilitarAction()
    {


        $idgsubcateg = $this->_request->getPost('idgsubcateg');
        $dengradomilit = $this->_request->getPost('denom');
        $abrevgradomilit = $this->_request->getPost('abrev');
        $esmarina = $this->_request->getPost('esmarina');
        $anterior = $this->_request->getPost('valanterior');
        $sucesor = $this->_request->getPost('valsucesor');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $homologoterr = $this->_request->getPost('homologoterr');
        $modelNomgrad = new NomgradomilitarModel();
        if (($anterior != '')and ($sucesor != '') and ($anterior == $sucesor)) {
            echo("{'codMsg':3,'mensaje':'El campo anterior y sucesor no pueden ser iguales.'}");
            return;
        }
        /*     if($modelNomgrad->existeGradoDenomAbrev($dengradomilit, $abrevgradomilit)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelNomgrad->insertarNomGradomilitar($idgsubcateg, $dengradomilit, $abrevgradomilit, $esmarina, $homologoterr, $anterior, $sucesor, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error al tratar de insertar el grado.'}");

    }

    function modificargradomilitarAction()
    {


        $idgradomilit = $this->_request->getPost('idgradomilit');
        $idgsubcateg = $this->_request->getPost('idgsubcateg');
        $dengradomilit = $this->_request->getPost('denom');
        $abrevgradomilit = $this->_request->getPost('abrev');
        $esmarina = $this->_request->getPost('esmarina');
        $anterior = $this->_request->getPost('valanterior');
        $sucesor = $this->_request->getPost('valsucesor');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $homologoterr = $this->_request->getPost('homologoterr');
        if ($anterior == $sucesor) {
            echo("{'codMsg':3,'mensaje':'El campo anterior y superior no pueden ser iguales.'}");
            return;
        }

        $modelNomgrad = new NomgradomilitarModel();
        if ($modelNomgrad->modificarNomGradomilitar($idgradomilit, $idgsubcateg, $dengradomilit, $abrevgradomilit, $esmarina, $homologoterr, $anterior, $sucesor, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error al tratar de modificar el grado.'}");

    }


    function eliminargradomilitarAction()
    {

        $idgradomilit = $this->_request->getPost('idgradomilit');
        $modelNomgrad = new NomgradomilitarModel();
        if ($modelNomgrad->usandoGradoMtar($idgradomilit)) {
            echo("{'codMsg':3,'mensaje': 'El objeto est&aacute; siendo usado.'}");
            return;
        }
        if ($modelNomgrad->eliminarGradomtar($idgradomilit))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'No se pudo eliminar.'}");


    }


    // ------------------------------ Nomenclador de especialidad ------------
    // -----------------------------------------------------------------------

    /**
     * inserta especialidades en la base de datos
     *
     */


    /**
     * muestra las especialidades existentes en la base de datos
     *
     */
    function mostrarespAction()
    {
        $modelNomEsp = new NomespecialidadModel();
        $total = $modelNomEsp->cantNomespecialidad();
        $tablas = $modelNomEsp->buscarNomespecialidad(100, 0);

        if ($total == 0) echo("{'codMsg':4,'mensaje': 'No existe ninguna escala salarial en la base de datos','detalles':'Debe ir a la funcionalidad Gestionar nomenclador e insertar una escala salarial.'}");
        else {
            $mostrar = array('cant' => $total, 'datos' => $tablas);
            echo(json_encode($mostrar));
        }
    }


    // ------------------------------ Nomenclador de organo --------------------
    // -------------------------------------------------------------------------


    function mostrartiposeavAction()
    {

        $modelEAV = new TablaModel();
        $datos = $modelEAV->buscarTablas(false, 1000, 0);
        if (count($datos) == 0)

            echo("{'codMsg':4,'mensaje': 'Debe definir un nivel estructural','detalles':'Debe ir a la funcionalidad Definir nivel estructural.'}");

        else

            echo json_encode(array('cant' => count($datos), 'datos' => $datos));
        //echo ("{'codMsg':4,'mensaje': 'Debe definir un nivel estructural','detalles':'Debe ir a la funcionalidad Definir nivel estructural   '}");


    }

    function insertarorganosAction()
    {

        $denorgano = $this->_request->getPost('denom');
        $abreviatura = $this->_request->getPost('abrev');
        $idespecialidad = $this->_request->getPost('idespecialidad');

        if ($idespecialidad == 'idespecialidad') {
            $idespecialidad = 1;
            //  echo ("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            //  return;
        }

        $idnivelstr = $this->_request->getPost('idnivelestr');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $idnomeav = $this->_request->getPost('idnomeav');

        $modelNomOrg = new NomorganoModel();

        /*if($modelNomOrg->existeNomorganoDenomAbrev($denorgano, $abreviatura)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                           return;
                }*/
        if ($idorgano = $modelNomOrg->insertarNomorgano($denorgano, $abreviatura, $idnivelstr, $idespecialidad, $orden, $fechaini, $fechafin, $idnomeav)) {

            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");

        } else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    function verificarcomboorganoAction()
    {
        $modelNomNiv = new NomnivelestrModel();
        $nivelstr = $modelNomNiv->cantNomNivelestr();
        if ($nivelstr == 0)
            $nivelstr = 'nivel estructural';
        else
            $nivelstr = '';
        $modelEAV = new TablaModel();
        $eav = $modelEAV->buscarTablas(false, 1000, 0);
        if ($eav == 0)
            $eav = 'eav';
        else
            $eav = '';
        $obj = new ZendExt_Nomencladores_ADT();
        $especialidad = $obj->getForest("nom_especialidad");
        $especialidad = count($especialidad);
        if ($especialidad == 0)
            $especialidad = 'especialidad';
        else
            $especialidad = '';

        $band = 0;
        if ($nivelstr != '') {
            $mensaje = $nivelstr . '.<br/> ';
            $band = 1;
        }
        if ($eav != '') {
            $mensaje = $mensaje . $eav . '.<br/>';
            $band = 1;
        }
        if ($especialidad != '') {
            $band = 1;
            $mensaje = $mensaje . $especialidad . '.';
        }

        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es):<br/> $mensaje','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");

    }

    /**
     * muestra las especialidades existentes en la base de datos
     *
     */
    function mostrarorganosAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomOrg = new NomorganoModel();
        $total = $modelNomOrg->CantElementos($denom);
        $tablas = $modelNomOrg->buscarorganos($limit, $start, $denom);
        $cont = 0;
        $obj = new ZendExt_Nomencladores_ADT();
        foreach ($tablas as $k) {
            $idesp = $k['idespecialidad'];
            $datosEsp = $obj->getElement("nom_especialidad", $idesp);
            $tablas[$cont]['Especialidad'] = $datosEsp['denespecialidad'];
            $cont++;
        }
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    /**
     *
     *
     */
    function modificarorganosAction()
    {


        $idorga = $this->_request->getPost('idorgano');
        $denorgano = $this->_request->getPost('denom');
        $abreviatura = $this->_request->getPost('abrev');
        $idespecialidad = $this->_request->getPost('idespecialidad');

        if ($idespecialidad == 'idespecialidad') {
            $idespecialidad = 1;
            //echo ("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            //return;
        }
        $idnivelstr = $this->_request->getPost('idnivelestr');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $idnomeav = $this->_request->getPost('idnomeav');

        /* $organo = $this->conn->getTable('NomOrgano')->find($idorga);
                if(($organo->denorgano == $denorgano || $organo->abrevorgano == $abreviatura )&&
                    ($organo->idnivelestr == $idnivelstr && $organo->idespecialidad == $idespecialidad &&
                     $organo->orden == $orden && $organo->idnomeav == $idnomeav &&
                        $organo->fechaini == $fechaini     && $organo->fechafin == $fechafin
                )){
                     echo("{'codMsg':3,'mensaje': 'No se debe repetir el mismo objeto.'}");
                           return;
                }*/
        $modelNomOrg = new NomorganoModel();
        if ($modelNomOrg->modificarNomorgano($idorga, $denorgano, $abreviatura, $idnivelstr, $idespecialidad, $orden, $idnomeav, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error Modificando.'}");


    }

    function eliminarorganosAction()
    {

        //-- verfiicar que el organo no se este utilizando
        $idorga = $this->_request->getPost('idorgano');
        $modelNomSc = new NomorganoModel();
        if ($modelNomSc->verificarUsado($idorga) > 0) {
            echo("{'codMsg':3,'mensaje': 'Este &oacute;rgano est&aacute; siendo usado por estructuras.'}");
        } else {
            if ($modelNomSc->eliminarNomorgano($idorga))
                echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
            else
                echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        }


    }

    // ------------------------------ Nomenclador de nivel --------------------
    // -------------------------------------------------------------------------


    /**
     * muestra las especialidades existentes en la base de datos
     *
     */
    function mostrarnvelestrAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelNomNiv = new NomnivelestrModel();
        $total = $modelNomNiv->cantNomNivelestr($denom);
        $tablas = $modelNomNiv->buscarNomNivelestr($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }


    /**
     * Insertar nivel estructural
     *
     */
    function insertarnvelestrAction()
    {

        $denom = $this->_request->getPost('denom');
        if (($denom{60})) {
            echo("{'codMsg':3,'mensaje': 'La denominacion debe ser una cadena menor de 60 caracteres.'}");
            return;
        }

        $abrev = $this->_request->getPost('abrev');

        $orden = $this->_request->getPost('orden');
        if (!is_numeric($orden)) {
            echo("{'codMsg':3,'mensaje': 'el orden debe tener un valor numerico.'}");
            return;
        }


        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelNomSc = new NomnivelestrModel();
        /*    if($modelNomSc->existeNomNivelEstrPorDenomAbrv($denom, $abrev)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                           return;
                }*/
        if ($modelNomSc->insertarNomNivelestr($denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificarnvelestrAction()
    {

        $idnvelestr = $this->_request->getPost('idnvelestr');

        $denom = $this->_request->getPost('denom');
        if (($denom{60})) {
            echo("{'codMsg':3,'mensaje': 'La denominacion debe ser una cadena menor de 60 caracteres.'}");
            return;
        }

        $abrev = $this->_request->getPost('abrev');

        $orden = $this->_request->getPost('orden');
        if (!is_numeric($orden)) {
            echo("{'codMsg':3,'mensaje': 'el orden debe tener un valor numerico.'}");
            return;
        }

        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        /* $nivelstr = $this->conn->getTable('NomNivelestr')->find($idnvelestr);
                if(($nivelstr->dennivelestr==$denom || $nivelstr->abrevnivelestr == $abrev)&&
                    ($nivelstr->orden == $orden )
                ){
                     echo("{'codMsg':3,'mensaje': 'No se debe repetir el mismo objeto.'}");
                           return;
                }*/

        $modelNomSc = new NomnivelestrModel();
        if ($modelNomSc->modificarNomNivelEstr($idnvelestr, $denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }

    function eliminarnvelestrAction()
    {
        $idnvelestr = $this->_request->getPost('idnvelestr');
        $modelNomSc = new NomnivelestrModel();

        if ($modelNomSc->usandoNivelsestr($idnvelestr))
            echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque posee asignaciones en el nomenclador de organo.'}");
        else {
            if ($modelNomSc->eliminarNomNivelEstr($idnvelestr))
                echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
            else
                echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        }


    }


    // ------------------------------ Nomenclador de sub categorias --------------------
    // -------------------------------------------------------------------------


    /**
     * Nomenclador de subcategorias
     *
     */

    function mostrarsbcategoriaAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomcat = new NomsubcategoriaModel();
        $total = $modelNomcat->cantNomsubcategoria($denom);
        $tablas = $modelNomcat->buscarNomsubcategoria($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }


    function insertarsbcategoriaAction()
    {


        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelNomSc = new NomsubcategoriaModel();
        if ($modelNomSc->existeNomsubcategoriaDenomAbrev($denom)) {
            echo("{'codMsg':3,'mensaje': 'No se debe repetir un  objeto.'}");
            return;
        }
        if ($modelNomSc->insertarNomsubcategoria($denom, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificarsbcategoriaAction()
    {


        $idsbcat = $this->_request->getPost('idsbcategorias');
        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');


        $modelsbcat = new NomsubcategoriaModel();

        if ($modelsbcat->modificarNomespecialidad($idsbcat, $denom, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarsbcategoriaAction()
    {

        $idsbcat = $this->_request->getPost('idsbcategorias');
        $modelNomSc = new NomsubcategoriaModel();

        if ($modelNomSc->eliminarNomSubcategoria($idsbcat))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    // ------------------------------ Nomenclador de tipo de cifras --------------------
    // -------------------------------------------------------------------------

    /**
     * Nomenclador de tipo de cifras
     *
     */

    function mostrartipocifraAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        //$limit 			=	( $limit != 0 ) ? $limit : 20 ;
        //	$start			=	( $start != 0 ) ? $start : 0 ;

        $modelNomtipo = new NomtipocifraModel();
        $total = $modelNomtipo->cantNomtipocifra($denom);
        $tablas = $modelNomtipo->buscarNomtipocifra($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    function insertartipocifraAction()
    {

        $denom = $this->_request->getPost('denom');
        $esacargable = $this->_request->getPost('iddesacargable');
        $cifracargo = $this->_request->getPost('idcifracargo');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        if ($fechafin == '')
            $fechafin = '05/05/3000';

        $modelNomSc = new NomtipocifraModel();
        /*       if($modelNomSc->existeNomtipocifraDenom($denom)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                           return;
                }*/
        if ($modelNomSc->insertarNomtipocifra($denom, $cifracargo, $esacargable, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificartipocifraAction()
    {


        $idcifra = $this->_request->getPost('idtipocifra');
        $denom = $this->_request->getPost('denom');
        $esacargable = $this->_request->getPost('iddesacargable');
        $cifracargo = $this->_request->getPost('idcifracargo');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        if ($fechafin == '')
            $fechafin = '05/05/3000';
        /*  $tipocifra = $this->conn->getTable('NomTipocifra')->find($idcifra);
                
               
                if($tipocifra->dentipocifra == $denom &&  $tipocifra->orden == $orden  ){
                         echo("{'codMsg':3,'mensaje': 'No se debe repetir el mismo objeto.'}");
                           return;
                    }*/
        $modelNomSc = new NomtipocifraModel();
        if ($modelNomSc->modificarNomtipocifra($idcifra, $denom, $cifracargo, $esacargable, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }

    function eliminartipocifraAction()
    {

        $idcifra = $this->_request->getPost('idtipocifra');
        $modelNomSc = new NomtipocifraModel();
        if ($modelNomSc->usandoNomtipocifra($idcifra))
            echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque posee asignaciones en los cargos.'}");
        else {
            if ($modelNomSc->eliminarNomtipocifra($idcifra))
                echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
            else
                echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        }

    }

    // ------------------------------ Nomenclador de categoria civil --------------------
    // -------------------------------------------------------------------------

    function mostrarcatgriacvilAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelNomciv = new NomcategcivilModel();
        $total = $modelNomciv->cantNomcategcivil($denom);
        $tablas = $modelNomciv->buscarNomcategcivil($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    function insertarcatgriacvilAction()
    {


        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $abrev = $this->_request->getPost('abrev');
        $idessueldo = $this->_request->getPost('idessueldo');
        $modelNomSc = new NomcategcivilModel();
        /*     if($modelNomSc->existeNomcategcivilDenomAbrev($denom, $abrev)){
                     echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                           return;
                }*/
        if ($modelNomSc->insertarNomcategoriacivil($denom, $abrev, $idessueldo, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    function modificarcatgriacvilAction()
    {


        $idcateg = $this->_request->getPost('idcatgriacvil');
        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $abrev = $this->_request->getPost('abrev');
        $idessueldo = $this->_request->getPost('idessueldo');
        $modelNomSc = new NomcategcivilModel();

        /*   $catgria = $this->conn->getTable('NomCategcivil')->find($idcateg);
                
                if(($catgria->dencategcivil == $denom || $catgria->abrevcategcivil == $abrev) &&
                  ( $catgria->essueldo == $idessueldo   && $catgria->orden == $orden           &&
                    $catgria->fechaini == $fechaini     && $catgria->fechafin == $fechafin)){
                            echo("{'codMsg':3,'mensaje': 'No se debe repetir el mismo objeto.'}");
                           return;
                        
                    
                }*/
        if ($modelNomSc->modificarNomcategoriacivil($idcateg, $denom, $abrev, $idessueldo, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }

    function eliminarcatgriacvilAction()
    {


        $idcatcivil = $this->_request->getPost('idcatgriacvil');
        $modelNomSc = new NomcategcivilModel();

        if ($modelNomSc->eliminarNomcategcivil($idcatcivil))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    // ------------------------------ Nomenclador de categoria ocupacional --------------------
    // -------------------------------------------------------------------------

    function mostrarcatgriaocpnalAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelNomcat = new NomcategoriaocupacionalModel();
        $total = $modelNomcat->cantNomCategoriaOcup($denom);
        $tablas = $modelNomcat->buscarNomCategoriaOcup($limit, $start, $denom);


        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    function insertarcatgriaocpnalAction()
    {

        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $abrev = $this->_request->getPost('abrev');

        $modelcat = new NomcategoriaocupacionalModel();
        /*if($modelcat->existeNomCategDenomAbrev($denom, $abrev)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelcat->insertarNomCategoriaOcup($denom, $orden, $fechaini, $fechafin, $abrev))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function modificarcatgriaocpnalAction()
    {

        $idcatocup = $this->_request->getPost('idcatgriaocpnal');
        $denom = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $abrev = $this->_request->getPost('abrev');

        $modelcat = new NomcategoriaocupacionalModel();
        if ($modelcat->modificarNomCategoriaOcup($idcatocup, $denom, $orden, $fechaini, $fechafin, $abrev))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarcatgriaocpnalAction()
    {


        $idcatocup = $this->_request->getPost('idcatgriaocpnal');
        $modelcat = new NomcategoriaocupacionalModel();

        if ($modelcat->eliminarNomCategoriaOcup($idcatocup))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        //}
    }

    // ------------------------------ Nomenclador de preparacion militar --------------------
    // -------------------------------------------------------------------------

    function mostrarpmilitarAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelprep = new NomprepmilitarModel();
        $total = $modelprep->cantNomPrepmilitar($denom);
        $tablas = $modelprep->buscarNomPrepmilitar($limit, $start, $denom);


        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    function insertarpmilitarAction()
    {


        $denprepmilitar = $this->_request->getPost('denom');
        $abrevprepmilitar = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelpre = new NomprepmilitarModel();
        /*        if($modelpre->existeNompmilitarDenomAbrev($denprepmilitar, $abrevprepmilitar)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelpre->insertarNomPrepmilitar($denprepmilitar, $abrevprepmilitar, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificarpmilitarAction()
    {


        $idprepmilitar = $this->_request->getPost('idprepmilitar');
        $denprepmilitar = $this->_request->getPost('denom');
        $abrevprepmilitar = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelpre = new NomprepmilitarModel();
        if ($modelpre->modificarNomPrepmilitar($idprepmilitar, $denprepmilitar, $abrevprepmilitar, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }

    function eliminarpmilitarAction()
    {

        $idpm = $this->_request->getPost('idprepmilitar');
        $modelcat = new NomprepmilitarModel();

        if ($modelcat->eliminarNomPrepmilitar($idpm))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    // ------------------------------ Nomenclador de MODULOS --------------------
    // -------------------------------------------------------------------------

    function mostrarnmoduloAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $modelNomNiv = new NommoduloModel();
        $total = $modelNomNiv->cantNommodulo();
        $tablas = $modelNomNiv->buscarNommodulo($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    function insertarnmoduloAction()
    {


        $deno = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelpre = new NommoduloModel();
        if ($modelpre->insertarNommodulo($deno, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificarnmoduloAction()
    {


        $idmod = $this->_request->getPost('idmodulo');
        $deno = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelpre = new NommoduloModel();
        if ($modelpre->modificarNommodulo($idmod, $deno, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");


    }

    function eliminarnmoduloAction()
    {


        $idpm = $this->_request->getPost('idmodulo');
        $modelcat = new NommoduloModel();

        if ($modelcat->eliminarNommodulo($idpm))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    // ------------------------------ Nomenclador de TECNICA --------------------
    // -------------------------------------------------------------------------

    function mostrarntecnicaAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomNiv = new NomtecnicaModel();
        $total = $modelNomNiv->cantNomtecnica($denom);
        $tablas = $modelNomNiv->buscarNomtecnica($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    function insertarntecnicaAction()
    {


        $pcodtecnica = $this->_request->getPost('codigo');
        $dentecnica = $this->_request->getPost('denom');
        $pabrevtecnica = $this->_request->getPost('abrev');
        $pvaplantilla = $this->_request->getPost('idvalor');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');


        $modelpre = new NomtecnicaModel();
        /*         if($modelpre->existeNomtecnicaDenomAbrev($dentecnica, $pabrevtecnica)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelpre->insertarNomtecnica($pcodtecnica, $dentecnica, $pabrevtecnica, $pvaplantilla, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");


    }

    function modificarntecnicaAction()
    {

        $idtecnica = $this->_request->getPost('idtecnica');
        $pcodtecnica = $this->_request->getPost('codigo');
        $dentecnica = $this->_request->getPost('denom');
        $pabrevtecnica = $this->_request->getPost('abrev');
        $pvaplantilla = $this->_request->getPost('idvalor');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelpre = new NomtecnicaModel();
        if ($modelpre->modificarNomtecnica($idtecnica, $pcodtecnica, $dentecnica, $pabrevtecnica, $pvaplantilla, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }


    function eliminarntecnicaAction()
    {

        $idtecnica = $this->_request->getPost('idtecnica');

        $modelcat = new NomtecnicaModel();

        if ($modelcat->eliminarNomtecnica($idtecnica))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");

    }


    // ------------------------------ Nomenclador de Cargo Militar --------------------
    // -------------------------------------------------------------------------

    function mostrarcargomtarAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomCargo = new NomcargomilitarModel();
        $total = $modelNomCargo->cantNomcargMilitar($denom);
        $tablas = $modelNomCargo->buscarNomcargomtar($limit, $start, $denom);
        $cont = 0;
        $obj = new ZendExt_Nomencladores_ADT();
        foreach ($tablas as $k) {
            $idesp = $k['idespecialidad'];
            $datosEsp = $obj->getElement("nom_especialidad", $idesp);
            $tablas[$cont]['Especialidad'] = $datosEsp['denespecialidad'];
            $cont++;
        }

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    function insertarcargomtarAction()
    {

        $denmtar = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad')
            $idespecialidad = 1;
        $idprepmilitar = $this->_request->getPost('idprepmilitar');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');


        $modelcargocivil = new NomcargomilitarModel();
        /*if($modelcargocivil->existeNomcargomtarDenomAbrev($denmtar, $abrev)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelcargocivil->insertarNomcargomilitar($denmtar, $abrev, $idespecialidad, $orden, $idprepmilitar, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }


    function modificarcargomtarAction()
    {
        $idcargo = $this->_request->getPost('idcargomilitar');
        $denmtar = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            // echo ("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            // return;
            $idespecialidad = 1;
        }
        $idprepmilitar = $this->_request->getPost('idprepmilitar');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelcargocivil = new NomcargomilitarModel();

        if ($modelcargocivil->modificarNomcargomilitar($idcargo, $denmtar, $abrev, $idespecialidad, $orden, $idprepmilitar, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarcargomtarAction()
    {

        $idcargo = $this->_request->getPost('idcargomilitar');
        $modelcat = new NomcargomilitarModel();

        if ($modelcat->eliminarNomcargomtar($idcargo))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }

    function verificarcombocargomilitarAction()
    {
        $obj = new ZendExt_Nomencladores_ADT();
        $especialidad = $obj->getForest("nom_especialidad");
        $especialidad = count($especialidad);
        $modelprep = new NomprepmilitarModel();
        $permilitar = $modelprep->cantNomPrepmilitar();
        if ($especialidad == 0)
            $especialidad = 'especialidad';
        else
            $especialidad = '';
        if ($permilitar == 0)
            $permilitar = 'preparaci&oacute;n militar';
        else
            $permilitar = '';

        $band = 0;
        if ($especialidad != '') {
            $mensaje = $especialidad . '.<br/>';
            $band = 1;
        }
        if ($permilitar != '') {
            $mensaje = $mensaje . $permilitar . '.';
            $band = 1;
        }

        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");
    }


    // ------------------------------ Nomenclador de Cargo Civil --------------------
    // -------------------------------------------------------------------------

    function verificarcombocargocivilAction()
    {

        $modelni = new NomnivelutilModel();
        $nivelutiliz = $modelni->contNomnivelurtilizacion();
        $modelgrupocomple = new NomgrupocompleModel();
        $grupocomple = $modelgrupocomple->contNomgrupocomple();
        $modelcalif = new NomcalificadorModel();
        $calificador = $modelcalif->contNomcalificador();
        $modelNomcat = new NomcategoriaocupacionalModel();
        $categop = $modelNomcat->cantNomCategoriaOcup();
        $obj = new ZendExt_Nomencladores_ADT();
        $especialidad = $obj->getForest("nom_especialidad");
        $especialidad = count($especialidad);
        if ($especialidad == 0)
            $especialidad = 'especialidad';
        else
            $especialidad = '';
        if ($nivelutiliz == 0)
            $nivelutiliz = 'nivel utilizaci&oacute;n';
        else
            $nivelutiliz = '';
        if ($grupocomple == 0)
            $grupocomple = 'grupo de complejidad';
        else
            $grupocomple = '';
        if ($categop == 0)
            $categop = 'categor&iacute;a ocupacional';
        else
            $categop = '';
        if ($calificador == 0)
            $calificador = 'calificador de cargo';
        else
            $calificador = '';

        $band = 0;
        if ($nivelutiliz != '') {
            $mensaje = $nivelutiliz . '.<br/>';
            $band = 1;
        }
        if ($calificador != '') {
            $mensaje = $mensaje . $calificador . '.<br/>';
            $band = 1;
        }
        if ($especialidad != '') {
            $mensaje = $mensaje . $especialidad . '.<br/>';
            $band = 1;
        }
        if ($categop != '') {
            $mensaje = $mensaje . $categop . '.<br/>';
            $band = 1;
        }
        if ($grupocomple != '') {
            $band = 1;
            $mensaje = $mensaje . $grupocomple . '.';
        }

        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");

    }


    function mostrarcargocivilAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelNomCargo = new NomcargocivilModel();
        $total = $modelNomCargo->cantNomcargocivil($denom);
        $tablas = $modelNomCargo->buscarNomcargocivil($limit, $start, $denom);
        $cont = 0;
        $obj = new ZendExt_Nomencladores_ADT();
        foreach ($tablas as $k) {
            $idesp = $k['idespecialidad'];
            $datosEsp = $obj->getElement("nom_especialidad", $idesp);
            $tablas[$cont]['Especialidad'] = $datosEsp['denespecialidad'];
            $cont++;
        }


        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    function insertarcargocivilAction()
    {

        $dencivil = $this->_request->getPost('denom');

        $abrevcivil = $this->_request->getPost('abrev');

        //$idcatgriacvil			=1;
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad')
            $idespecialidad = 1;

        $idcatgriaocpnal = $this->_request->getPost('idcategocup');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $codigo = $this->_request->getPost('codigo');
        $descripcion = $this->_request->getPost('descrip');
        $requisito = $this->_request->getPost('requisito');
        $idgrupocomple = $this->_request->getPost('idgrupocomplejidad');
        $idnivelutil = $this->_request->getPost('idnivelutilizacion');
        $idcalificador = $this->_request->getPost('idcalificador');

        $modelcargocivil = new  NomcargocivilModel();

        if ($modelcargocivil->insertarNomcargocivil($dencivil, $abrevcivil, $idespecialidad, $idcatgriaocpnal, $idcatgriacvil, $orden, $fechaini, $fechafin, $codigo, $descripcion, $requisito, $idcalificador, $idgrupocomple, $idnivelutil))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function modificarcargocivilAction()
    {

        $idcargoC = $this->_request->getPost('idcargociv');
        $dencivil = $this->_request->getPost('denom');
        $abrevcivil = $this->_request->getPost('abrev');
        // $idcatgriacvil			=1;
        $idespecialidad = $this->_request->getPost('idespecialidad');

        //print_r($idespecialidad);die;

        if ($idespecialidad == 'idespecialidad') {
            //echo ("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            $idespecialidad = 1;
            //return;
        }
        $idcatgriaocpnal = $this->_request->getPost('idcategocup');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $codigo = $this->_request->getPost('codigo');
        $descripcion = $this->_request->getPost('descrip');
        $requisito = $this->_request->getPost('requisito');
        $idgrupocomple = $this->_request->getPost('idgrupocomplejidad');
        $idnivelutil = $this->_request->getPost('idnivelutilizacion');
        $idcalificador = $this->_request->getPost('idcalificador');

        $modelcargocivil = new NomcargocivilModel();

        if ($modelcargocivil->modificarNomcargocivil($idcargoC, $dencivil, $abrevcivil, $idespecialidad, $idcatgriaocpnal, $idcategoriacivil, $orden, $fechaini, $fechafin, $codigo, $descripcion, $requisito, $idcalificador, $idgrupocomple, $idnivelutil))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarcargocivilAction()
    {

        $idcargo = $this->_request->getPost('idcargociv');
        $modelcat = new NomcargocivilModel();


        //if( $modelcat->usadoNomcargocivil($idcargo) )
        //echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque posee asignaciones en los cargos.'}");
        //else
        //{
        if ($modelcat->eliminarNomcargocivil($idcargo))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        //}
    }

    // ------------------------------ Nomenclador de agrupaciones --------------------
    // -------------------------------------------------------------------------

    function mostrarnagrupacionAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelNomNiv = new NomagrupacionesModel();
        $total = $modelNomNiv->cantNomAgrup($denom);
        $tablas = $modelNomNiv->buscarNomAgrupacion($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    function insertarnagrupacionAction()
    {


        $denagrupacion = $this->_request->getPost('denom');
        $abrevagrupacion = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelpre = new NomagrupacionesModel();
        /*        if($modelpre->existeNomAgrupacionDenomAbrev($denagrupacion, $abrevagrupacion)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelpre->insertarNomAgrupacion($denagrupacion, $abrevagrupacion, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function modificarnagrupacionAction()
    {


        $idagrupacion = $this->_request->getPost('idagrupacion');
        $denagrupacion = $this->_request->getPost('denom');
        $abrevagrupacion = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelpre = new NomagrupacionesModel();
        if ($modelpre->modificarNomAgrupacion($idagrupacion, $denagrupacion, $abrevagrupacion, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarnagrupacionAction()
    {


        $idagrupacion = $this->_request->getPost('idagrupacion');

        $modelcat = new NomagrupacionesModel();
        if ($modelcat->eliminarNomAgrupacion($idagrupacion))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");


    }


    // ------------------------------ Nomenclador de Escala Salarial --------------------
    // -------------------------------------------------------------------------

    function insertarescalasalarialAction()
    {


        $denomescala = $this->_request->getPost('denom');
        $abrevescala = $this->_request->getPost('abrev');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $orden = $this->_request->getPost('orden');
        $modelescala = new NomescalasalarialModel();
        /*        if($modelescala->existeNomEscalaslaria($denomescala, $abrevescala)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelescala->insertarNomescalasalarial($denomescala, $abrevescala, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    function mostrarescalasalarialAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelescala = new NomescalasalarialModel();
        $total = $modelescala->countNomescalasalarial($denom);
        $tablas = $modelescala->buscarNomescalasalarial($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        //if total==0

        echo(json_encode($mostrar));

    }

    function modificarescalasalarialAction()
    {


        $idescalasalarial = $this->_request->getPost('idescalasalarial');
        $denomescala = $this->_request->getPost('denom');
        $abrevescala = $this->_request->getPost('abrev');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $orden = $this->_request->getPost('orden');

        $modelescalasalarial = new NomescalasalarialModel();

        if ($modelescalasalarial->modificarNomEscalasalarial($idescalasalarial, $denomescala, $abrevescala, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }

    function eliminarescalasalarialAction()
    {


        $idescalasalarial = $this->_request->getPost('idescalasalarial');
        $modelescalasalarial = new NomescalasalarialModel();

        //if ($modelescalasalarial->usandoNomescalasalarial($idescalasalarial))
        // echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque se esta usando'}");
        // else {
        if ($modelescalasalarial->eliminarNomEscalasalarial($idescalasalarial))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        // }


    }

    // ------------------------------ Nomenclador de Salario --------------------
    // -------------------------------------------------------------------------


    function insertarsalarioAction()
    {


        $grupocomple = $this->_request->getPost('idgrupocomplejidad');
        $escalasalarial = $this->_request->getPost('idescalasalarial');
        $orden = $this->_request->getPost('orden');
        $salario = $this->_request->getPost('salario');
        $tarifa = $this->_request->getPost('tarifa');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $salarioentero = intval($salario, 10);
        $tarifaentero = intval($tarifa);

        if (strlen($salarioentero) > 4) {
            echo("{'codMsg':3,'mensaje': 'El campo escala salarial no permite más de 4 dígitos enteros.'}");
            return;
        }
        if (strlen($tarifaentero) > 4) {
            echo("{'codMsg':3,'mensaje': 'El campo tarifa no permite más de 4 dígitos enteros.'}");
            return;
        }
        if (isset($orden{6})) {
            echo("{'codMsg':3,'mensaje': 'El campo orden no permite más de 6 dígitos.'}");
            return;
        }
        $modelsalario = new  NomsalarioModel();
        if ($modelsalario->verificargrupoescala($grupocomple, $escalasalarial)) {
            echo("{'codMsg':3,'mensaje': 'El grupo y la escala seleccionada ya tienen un salario.'}");
        } else {
            if ($modelsalario->insertarSalario($grupocomple, $escalasalarial, $salario, $tarifa, $orden, $fechaini, $fechafin))
                echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
            else
                echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
        }

    }

    function  verificarcombosalarioAction()
    {
        $modelgrupocomple = new NomgrupocompleModel();
        $grupocomple = $modelgrupocomple->contNomgrupocomple();

        $modelescala = new NomescalasalarialModel();
        $escala = $modelescala->countNomescalasalarial();

        if ($grupocomple == 0)
            $grupocomple = 'grupo de complejidad';
        else
            $grupocomple = '';
        if ($escala == 0)
            $escala = 'escala salarial';
        else
            $escala = '';


        if ($grupocomple != '') {
            $mensaje = $grupocomple . '.<br/>';
            $band = 1;
        }
        if ($escala != '') {
            $mensaje = $mensaje . $escala . '.';
            $band = 1;
        }

        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje','detalles':'Debe dirigirse a los nomencladores especificados y adicionarles al menos un dato.'}");
    }

    function mostrarsalarioAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelsalario = new NomsalarioModel();
        $total = $modelsalario->contNomsalario($denom);
        $tabla = $modelsalario->buscarNomsalario($limit, $start, $denom);
        $mostrar = array('cant' => $total, 'datos' => $tabla);

        echo(json_encode($mostrar));

    }

    function modificarsalarioAction()
    {


        $Idsala = $this->_request->getPost('idsalario');
        $grupocomple = $this->_request->getPost('idgrupocomplejidad');
        $escalasalarial = $this->_request->getPost('idescalasalarial');
        $orden = $this->_request->getPost('orden');
        $salario = $this->_request->getPost('salario');
        $tarifa = $this->_request->getPost('tarifa');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $salarioentero = intval($salario, 10);
        $tarifaentero = intval($tarifa);

        if (strlen($salarioentero) > 4) {
            echo("{'codMsg':3,'mensaje': 'El campo escala salarial no permite más de 4 dígitos enteros.'}");
            return;
        }
        if (strlen($tarifaentero) > 4) {
            echo("{'codMsg':3,'mensaje': 'El campo tarifa no permite más de 4 dígitos enteros.'}");
            return;
        }
        if (isset($orden{6})) {
            echo("{'codMsg':3,'mensaje': 'El campo orden no permite más de 6 dígitos.'}");
            return;
        }


        $modelSala = new NomsalarioModel();

        if ($modelSala->modificarNomSalario($Idsala, $grupocomple, $escalasalarial, $salario, $tarifa, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");


    }

    function eliminarsalarioAction()
    {


        $idsalario = $this->_request->getPost('idsalario');

        $modelsalario = new NomsalarioModel();

        /* if ($modelsalario->usandoNonsalario($idsalario))
		    echo("{'codMsg':3,'mensaje': 'No se puede eliminar porque posee asignaciones en el nomenclador de cargo civil.'}");
		    else {*/
        if ($modelsalario->eliminarNomsalario($idsalario))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        //  }


    }

    // ------------------------------ Nomenclador de Grupo de complejidad --------------------
    // --------------------------------------------------------------------------


    function insertargrupocomplejidadAction()
    {


        $modelescala = new NomescalasalarialModel();
        $datescala = $modelescala->buscarNomescalasalarial(10, 0);
        if (count($datescala) == 0) {
            echo("{'codMsg':4,'mensaje': 'Debe adicionar una escala salarial.'}");
            return;
        } else
            $idescalasalarial = $datescala[0]['idescalasalarial'];

        $salarioescala = 1;

        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelgrupocomple = new NomgrupocompleModel();
        /*if($modelgrupocomple->existeNomgrupocompleDenomAbrev($denom, $abrev)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelgrupocomple->insertarNomgrupocomple($denom, $abrev, $idescalasalarial, $salarioescala, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }


    function mostrargrupocomplejidadAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelgrupocomple = new NomgrupocompleModel();
        $total = $modelgrupocomple->contNomgrupocomple($denom);
        $tablas = $modelgrupocomple->buscarNomgrupocomple($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    function modificargrupocomplejidadAction()
    {


        /*$idescalasalarial	=   1;
		$salarioescala		=	1;*/

        $idgrupocompljidad = $this->_request->getPost('idgrupocomplejidad');
        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $modelgrupocomple = new NomgrupocompleModel();

        if ($modelgrupocomple->modificarNomgrupocomple($idgrupocompljidad, $denom, $abrev, $idescalasalarial, $salarioescala, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminargrupocomplejidadAction()
    {


        $idgrupocompljidad = $this->_request->getPost('idgrupocomplejidad');
        $modelgrupocomplejidad = new NomgrupocompleModel();


        if ($modelgrupocomplejidad->eliminarNomgrupocomple($idgrupocompljidad))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");


    }

    // ------------------------------ Nomenclador de Nivel utilizacion --------------------
    // --------------------------------------------------------------------------

    function insertarnivelutilizacionAction()
    {


        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelnivelut = new NomnivelutilModel();
        /*        if($modelnivelut->existeNomnivelDenomAbrev($denom, $abrev)){
                    echo("{'codMsg':3,'mensaje': 'No se debe repetir un objeto.'}");
                    return;
                }*/
        if ($modelnivelut->insertarNomnivelutilizacion($denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }


    function mostrarnivelutilizacionAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelni = new NomnivelutilModel();
        $total = $modelni->contNomnivelurtilizacion($denom);
        $tablas = $modelni->buscarNomnivelutilizacion($limit, $start, $denom);


        $mostrar = array('cant' => $total, 'datos' => $tablas);
        echo(json_encode($mostrar));

    }

    function modificarnivelutilizacionAction()
    {

        $idnivelutil = $this->_request->getPost('idnivelutilizacion');
        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');

        $idnivelutil = $this->_request->getPost('idnivelutilizacion');
        $denom = $this->_request->getPost('denom');
        $abrev = $this->_request->getPost('abrev');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelutil = new  NomnivelutilModel();
        if ($modelutil->modificarNomnivelutilizacion($idnivelutil, $denom, $abrev, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarnivelutilizacionAction()
    {


        $id = $this->_request->getPost('idnivelutilizacion');
        $model = new NomnivelutilModel();
        if ($model->eliminarNomnivelutilizacion($id))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");


    }


    //-------------------------Nomenclador de clasificacion-------------------------------
    //--------------------------------------------------------------------------------------

    function mostrarclasificacionAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $modelclasif = new NomclasificacioncargoModel();
        $total = $modelclasif->conNomclasificacion($denom);
        $tablas = $modelclasif->buscarNomclasificacion($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    function insertarclasificacionAction()
    {

        $denominacion = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelclasif = new NomclasificacioncargoModel();

        if ($modelclasif->insertarNomclasificacion($denominacion, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");

    }

    function modificarclasificacionAction()
    {


        $idclasificacion = $this->_request->getPost('idclasificacion');
        $denominacion = $this->_request->getPost('denom');
        $orden = $this->_request->getPost('orden');
        $fechaini = $this->_request->getPost('fini');
        $fechafin = $this->_request->getPost('ffin');
        $modelclasif = new NomclasificacioncargoModel();

        if ($modelclasif->modificarNomclasificacion($idclasificacion, $denominacion, $orden, $fechaini, $fechafin))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }

    function eliminarclasificacionAction()
    {


        $idclasificacion = $this->_request->getPost('idclasificacion');
        $modelclasif = new NomclasificacioncargoModel();

        if ($modelclasif->eliminarNomclasificacion($idclasificacion))
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
    }


    // ------------------------------ Nomenclador de DPA y TipoDpa --------------------
    // --------------------------------------------------------------------------


    // --- trabajo con el arbol de DPA


    /**
     * FUNCION PARA RENDERIZAR LOS ELEMENTOS
     */


    //-----------Nomneclador especialidad-------------
    function hijoespecialidadAction()
    {


        $idesp = $this->_request->getPost('node');
        //$idesp		=	($idesp =='idespecialidad' ) ? $idesp : $idesp;	 


        $obj = new ZendExt_Nomencladores_ADT();
        /*
		$mostrar = $obj->getTree("nom_especialidad",$idesp,1);
		    $arreglo =$mostrar->Childrens(); 
		  if (count($arreglo)==0)
        	       echo json_encode(array());
             else {    	
    	                 for($i=0 ;$i<count($arreglo);$i++)
    	                 { 
		                      $hijo[] = array('id'=>$arreglo[$i]->_id,'text'=>$arreglo[$i]->_values['denespecialidad']);	
		                  }
		                   echo json_encode($hijo);
		           }*/

        if ($idesp == 'idespecialidad') {
            $arreglo = $obj->getForest("nom_especialidad");
            for ($i = 0; $i < count($arreglo); $i++) {
                $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denespecialidad']);
            }
            echo json_encode($hijo);


        } else {
            $mostrar = $obj->getTree("nom_especialidad", $idesp);
            $arreglo = $mostrar->Childrens();

            if (count($arreglo) == 0)
                echo json_encode(array());
            else {
                for ($i = 0; $i < count($arreglo); $i++) {
                    $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denespecialidad']);
                }
                echo json_encode($hijo);
            }


        }


    }


}

?>
