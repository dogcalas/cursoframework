<?php

class ReportesController extends ZendExt_Controller_Secure
{

    function init()
    {
        parent::init();
    }


    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function buscarhijosAction()
    {

        //$idest			= ( isset( $_POST['node'] ) && $_POST['node'] != '' ) ? ($_POST['node']=='Estructuras')? false: $_POST['node'] : die("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
        $idest = $this->_request->getPost('node');
        if ($idest == '') {
            echo("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
            return;
        }
        if ($idest == 'Estructuras')
            $idest = false;

        $modelEstructura = new EstructuraModel();
        $estructura = $modelEstructura->getHijosOrga($idest);
        $estructuraop = array();

        if ($idest)
            $estructuraop = $modelEstructura->getEstructurasInternasOrg($idest, true);


        if (count($estructura) == 0 && count($estructuraop) == 0) {

            $modelEstrOp = new EstructuraopModel();

            $estructuras = $modelEstrOp->getHijosOrg($idest);


            echo(json_encode($estructuras));
            return;
        }
        /*echo '<pre>';
        print_r($estructura);die;*/
        echo(json_encode(array_merge_recursive($estructuraop, $estructura)));

    }

    function buscarhijosreportesAction()
    {

        //$idest			= ( isset( $_POST['node'] ) && $_POST['node'] != '' ) ? ($_POST['node']=='Estructuras')? false: $_POST['node'] : die("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
        $idest = $this->_request->getPost('node');
        if ($idest == '') {
            echo("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
            return;
        }
        if ($idest == 'Estructuras')
            $idest = false;

        $modelEstructura = new EstructuraModel();
        $estructura = $modelEstructura->getHijosReporte($idest);
        $estructuraop = array();

        //if($idest)
        //$estructuraop	= $modelEstructura->getEstructurasInternasOrg($idest, true);

        /*if(	count($estructura) == 0 && count($estructuraop) == 0)
        {
            $modelEstrOp	= new EstructuraopModel();
            $estructuras	= $modelEstrOp->getHijosOrg( $idest );


            echo(json_encode($estructuras));
            return;
        }*/


        //echo(json_encode(array_merge_recursive($estructuraop,$estructura)));
        echo(json_encode($estructura));

    }

    function buscarcomposicionAction()
    {
        //$idestructura	= isset( $_POST['idestructura'] ) ? $_POST['idestructura'] : die("");  ;
        //$idop			= isset( $_POST['node'] ) ? $_POST['node'] : die("");  ;

        $idestructura = $this->_request->getPost('idestructura');
        $idop = $this->_request->getPost('node');
        //-- si el id enviado es COmposicion entonces se  cambia de arbol al de estructuras internas
        if ($idop == $idestructura) {
            $estructuras = array();
            if ($idestructura != 'Estructuras') {
                $modelEst = new EstructuraModel();
                $estructuras = $modelEst->getEstructurasInternasOrg($idestructura, true);
            }
            echo json_encode($estructuras);
        } else {
            //-- si el id op es diferentede estructuras
            if ($idop != 'Estructuras') {
                $modelEst = new EstructuraopModel();
                $estructuras = $modelEst->getHijosOrg($idop);
                echo json_encode($estructuras);
            } else
                echo json_encode(array());

        }

    }

    /**
     * guarda el orgaigrama
     *
     */
    function guardarorganigramaAction()
    {
        //$nombre	= $_POST['nombre'];
        //$datos	= ($_POST['nodos']);
        //$notas  =$_POST['notas'];

        $nombre = $this->_request->getPost('nombre');
        $datos = $this->_request->getPost('nodos');
        $notas = $this->_request->getPost('notas');

        $datos = '{nodes:' . $datos . ',notas:[{"texto":"' . $notas . '"}]}';

        if (file_exists('./comun/recursos/organigramas/' . $nombre . '.org')) {
            echo("{'codMsg':3,'mensaje': 'Ya existe un organigrama con ese nombre. '}");
            return;
        }
        if (file_put_contents('./comun/recursos/organigramas/' . $nombre . '.org', $datos)) {
            echo("{'codMsg':1,'mensaje': 'Guardado correctamente. '}");
            return;
        } else {
            echo("{'codMsg':3,'mensaje': 'Error al guardar organigrama. '}");
            return;
        }

    }

    function modificarnombreorganigramaAction()
    {
        //$nombre=$_POST['nombre'];
        //$nuevoNombre=$_POST['nuevoNombre'];
        $nombre = $this->_request->getPost('nombre');
        $nuevoNombre = $this->_request->getPost('nuevoNombre');
        if (rename('./comun/recursos/organigramas/' . $nombre . '.org', './comun/recursos/organigramas/' . $nuevoNombre . '.org')) {
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente. '}");
            return;
        } else {
            echo("{'codMsg':3,'mensaje': 'Error al modificar nombre del organigrama. '}");
            return;
        }


    }


    function eliminarorganigramaAction()
    {
        //$nombre	= $_POST['nombre'];
        $nombre = $this->_request->getPost('nombre');


        if (!file_exists('./comun/recursos/organigramas/' . $nombre . '.org')) {
            echo("{'codMsg':3,'mensaje': 'No existe organigrama con ese nombre. '}");
            return;
        }
        if (unlink('./comun/recursos/organigramas/' . $nombre . '.org')) {
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente. '}");
            return;
        } else {
            echo("{'codMsg':3,'mensaje': 'Error eliminando. '}");
            return;
        }
    }

    function mostrarorganigramasAction()
    {
        //$inicio=$_POST['start'];
        //$fin=$_POST['limit'];
        $inicio = $this->_request->getPost('start');
        $fin = $this->_request->getPost('limit');
        $d = dir('./comun/recursos/organigramas/');
        $archivos = array();
        $row = 0;
        while ($nombre_archivo = $d->read()) {
            if ($nombre_archivo != "." && $nombre_archivo != ".." && $nombre_archivo != ".svn") {
                $row++;
                if ($row > $inicio && $row < $inicio + $fin) {
                    $nombre = substr($nombre_archivo, 0, -4);
                    $archivos[] = array('idorganigrama' => $row, 'Nombre' => $nombre);
                }


            }

        }

        echo json_encode(array('cant_filas' => $row, 'filas' => ($archivos)));
        return;

    }


    function recuperarorganigramaAction()
    {
        $nombre = $this->_request->getPost('nombre');

        echo stripcslashes(file_get_contents('./comun/recursos/organigramas/' . $nombre . '.org'));
    }

    function gestionarreportesAction()
    {
        $this->render();
    }

    function geticonAction()
    {

        header('Content-type: image/gif');
        $module_path = Zend_Registry::getInstance()->config->modulo_path;
        $img = imagecreatefromgif($module_path . 'comun/recursos/iconos/' . $_GET['icon'] . '.gif');
        imagegif($img);
    }

    function mostarnivelAction()
    {


    }

    function cargosporareaAction()
    {


        $ides = 1; //$_POST['algo'];
        $carg = new DatcargoModel();
        $cargos = $carg->buscarCargo(1000, 0, 26);
        echo '<pre>';
        print_r($cargos);
        /*$pdf = new FPDF();
        $pdf->Open();
        $pdf->AddPage();
        $pdf->Output();*/
    }

    function frepplantillacargosAction() //----------------------------------------------------------------------------------
    {
        $pdf = new generadorpdf();
        $pdf->Open();
        $title = 'Reporte plantilla de cargos';
        $pdf->SetAuthor('Julio Verne');
        $pdf->SetTitle($title);
        $pdf->AddPage();
        $carg = new ConsultaDatos();
        $cargos = $carg->f_m_rep_plantillacargos();
        /*echo '<pre>';
        print_r($cargos);*/
        $datos = array();
        foreach ($cargos as $cargo) {

            $datos[] = array('deno',
                $cargo['dencargociv'],
                $cargo['cant_cargos'],
                $cargo['abre'],
                $cargo['abre1'],
                $cargo['abrevtecn'],
                $cargo['abrevespecia']);
        }

        $cabeceras = array('Cant' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'GE' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'CO/GM' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'RM' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Esp' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => ''));
        $fuente = array('tamano' => 10, 'tipo' => 'Arial', 'estilo' => '');

        $pdf->crearTabla($title, $cabeceras, $datos, true, $fuente, 10);

        $pdf->Output();
    }

    function frepcargosporgrupocompleAction() //---------------------------------------------------------------------------
    {
        //$idnivel    = $_POST['idnivel1'];
        $idnivel = $this->_request->getPost('idnivel1');
        $pdf = new generadorpdf();
        $title = 'Resumen de cargos por grupos de complejdad y categoria ocupacional';
        $pdf->SetAuthor('Julio Verne');
        $pdf->SetTitle($title);
        $pdf->AddPage();
        $carg = new ConsultaDatos();
        $cargos = $carg->f_m_rep_cargosporgrupocomple($idnivel);
        //echo '<pre>';
        //print_r($cargos);
        $datos = array();
        foreach ($cargos as $cargo) {

            $datos[] = array('abre',
                $cargo['dencate'],
                $cargo['cant']);
        }

        $cabeceras = array('Ge' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Administrativos' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Servicios' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Operarios' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Tecnicos' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Dirigentes' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Total' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => ''));
        $fuente = array('tamano' => 10, 'tipo' => 'Arial', 'estilo' => '');

        $pdf->crearTabla('tabla 1', $cabeceras, $datos, true, $fuente, 10);

        $pdf->Output();
    }

    function frepcargosareascategocupacionalAction() //----------------------------------------------------------------------
    {
        //55,2
        //$idestruc 	= $_POST['idestruct'];
        $idestruc = $this->_request->getPost('idestruct');
        $pdf = new generadorpdf();
        $pdf->Open();
        $title = 'Resumen de puestos de trabajos por area y categoria ocupacional';
        $pdf->SetAuthor('Lazaro');
        $pdf->SetTitle($title);
        $pdf->AddPage();
        $cons = new ConsultaDatos();
        //$dat 	= $cons->f_m_rep_cargosareascategocupacional($idestruc);//4
        $dat = $cons->f_m_rep_cargosareascategocupacional(4);
        $datos = array();
        foreach ($dat as $a) {
            $datos[] = array(++$i,
                $a['deno'],
                $a['total'],
                $a['cant_cargo'],
                $a['abre']);
        }
        /*echo '<pre>';
        print_r($datos);*/

        $cabeceras = array('No' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Denominacion' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Total' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'A' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'S' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => ''));
        /*,'O'				=>array('colorletra'=>'','colorfondo'=>'', 'estilo'=>'', 'alineacion'=>'' )
        ,'T'				=>array('colorletra'=>'','colorfondo'=>'', 'estilo'=>'', 'alineacion'=>'' )
        ,'D'				=>array('colorletra'=>'','colorfondo'=>'', 'estilo'=>'', 'alineacion'=>'' ));*/

        $fuente = array('tamano' => 10, 'tipo' => 'Arial', 'estilo' => '');

        $pdf->crearTabla($title, $cabeceras, $datos, true, $fuente, 10);

        $pdf->Output();
    }

    function fmreprelacionentidadesperfeccionamientoAction() //----------------------------------------------------------------
    {
        /*$ideavnivel 				= $_POST['ideavnivel'];
        $ideavagrupacion 			= $_POST['ideavagrupacion'];
        $ideaventidad 				= $_POST['ideaventidad'];
        $idcampoperfeccionamiento 	= $_POST['idcampoperfeccionamiento'];
        $idcampocodone 				= $_POST['idcampocodone'];
        $idcampocategoria 			= $_POST['idcampocategoria'];
        $idcampopagoadicional 		= $_POST['idcampopagoadicional'];*/

        $ideavnivel = $this->_request->getPost('ideavnivel');
        $ideavagrupacion = $this->_request->getPost('ideavagrupacion');
        $ideaventidad = $this->_request->getPost('ideaventidad');
        $idcampoperfeccionamiento = $this->_request->getPost('idcampoperfeccionamiento');
        $idcampocodone = $this->_request->getPost('idcampocodone');
        $idcampocategoria = $this->_request->getPost('idcampocategoria');
        $idcampopagoadicional = $this->_request->getPost('idcampopagoadicional');


        $pdf = new generadorpdf();
        $pdf->Open();
        $title = 'Relacion de entidades en perfeccionamiento empresarial por agrupacion.';
        $pdf->SetAuthor('Lazaro');
        $pdf->SetTitle($title);
        $pdf->AddPage();
        $cons = new ConsultaDatos();
        //$dat 	= $cons->f_m_rep_relacion_entidades_perfeccionamiento($ideavnivel, $ideavagrupacion, $ideaventidad, //$idcampoperfeccionamiento, $idcampocodone,$idcampocategoria,$idcampopagoadicional);//4
        //$dat  =$cons->f_m_rep_relacion_entidades_perfeccionamiento();
        $datos = array();
        foreach ($dat as $a) {
            $datos[] = array(++$i,
                $a['deno'],
                $a['total'],
                $a['cant_cargo'],
                $a['abre']);
        }
        /*echo '<pre>';
        print_r($datos);*/

        $cabeceras = array('No' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Denominacion Entidades' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Codigo ONE' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Abreviatura' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Categoria' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Aplica perfeccionamiento' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => '')
        , 'Aplica pago adicional' => array('colorletra' => '', 'colorfondo' => '', 'estilo' => '', 'alineacion' => ''));

        $fuente = array('tamano' => 10, 'tipo' => 'Arial', 'estilo' => '');

        $pdf->crearTabla($title, $cabeceras, $datos, true, $fuente, 10);

        $pdf->Output();
    }


    //---------------------Para mostrar el visor del reporteador-----------------
    function mostrarreporteAction()
    {

        $idreporte = 100; //$this->_request->getPost('idreporte');
        $uri = Zend_Registry::get('config')->report_systems->uri_visor;
        //Integracion - nivel de reporte
        header("Location: {$uri}/report/" . $idreporte);
    }

    function mostrarreportesusuarioAction()
    {
        $uri = Zend_Registry::get('config')->report_systems->uri_visor;
        //Integracion - nivel de modulos
        header("Location: {$uri}/category/26");
    }

    function obteneridestructuraAction()
    {
        $global = ZendExt_GlobalConcept::getInstance();
        $idestructura = $global->Estructura->idestructura;

        $modelo = new EstructuraModel();
        $estructura = $modelo->getEstructuraId($idestructura);
        $estructura = $estructura[0]['denominacion'];

        echo json_encode(array('estructura' => $estructura));
    }

    function mostrarcalificadorAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');


        $modelcalif = new NomcalificadorModel();
        $total = $modelcalif->contNomcalificador();
        $tablas = $modelcalif->buscarNomcalificador($limit, $start, $denom);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    function obtenerformatodocumentoAction()
    {
        $direccion = 'Location: http://' . $_SERVER['HTTP_HOST'] . '/' . 'report_generator.php/api/getFormats';
        echo header($direccion);
    }

}

?>
