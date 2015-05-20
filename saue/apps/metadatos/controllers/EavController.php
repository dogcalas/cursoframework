<?php

class EavController extends ZendExt_Controller_Secure
{

    function init()
    {
        parent::init();
    }

    // ---------------------------------------------- tablas
    function tablasAction()
    {
        $modelo = new TablaModel();
        $idTabla = $this->_request->getPost('idtabla') ? $this->_request->getPost('idtabla') : false;
        $start = $this->_request->getPost('start');
        $limit = $this->_request->getPost('limit');
        $cantidad = $modelo->contTablas();
        $tab = $modelo->buscarTablas($idTabla, $limit, $start);
        echo(json_encode(array('cant' => $cantidad, 'datos' => $tab)));
    }


    // ---------------------------------------------- insercion tablas
    function insertatablasAction()
    {

        $nombreTabla = $this->_request->getPost('nombre');
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $idpadre = ($this->_request->getPost('idpadre')) ? $this->_request->getPost('idpadre') : false;
        $recursiva = ($this->_request->getPost('recursiva')) && $this->_request->getPost('recursiva') == 1 ? 1 : 0;
        $entidad = ($this->_request->getPost('entidad')) && $this->_request->getPost('entidad') == 1 ? 1 : 0;

        $modeloTabla = new TablaModel();
        if ($modeloTabla->insertar($nombreTabla, $fechaini, $fechafin, $idpadre, $recursiva, $entidad))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }


    // ---------------------------------------------- insercion campos
    function insertarcamposAction()
    {


        $idTabla = $this->_request->getPost('idtabla');
        $nombreCampo = $this->_request->getPost('nombre');
        $tipo = $this->_request->getPost('tipo');
        $longitud = $this->_request->getPost('longitud');
        $nombrec = $this->_request->getPost('nombre_mostrar');
        $regex = $this->clasificador($tipo);
        $visible = ($this->_request->getPost('visible')) && $this->_request->getPost('visible') == 1 ? 1 : 0;
        $tipocampo = $this->_request->getPost('tipocampo');
        $descripcion = $this->_request->getPost('descripcion') != '' ? $this->_request->getPost('descripcion') : ' ';

        $modeloCampo = new CampoModel();

        if ($valoridCampo = $modeloCampo->insertar($idTabla, $nombreCampo, $tipo, $longitud, $nombrec, $regex, $visible, $tipocampo, $descripcion)) {
            //-- buscar las filas que ya se insertaron de esa tabla
            $modelFilas = new FilaModel();
            $filas = $modelFilas->buscarFilas($idTabla);

            // insertar los valores por defecto

            $arregloDefecto = json_decode(stripcslashes($this->_request->getPost('vpdcb')));
            if (is_array($arregloDefecto)) {

                $modelValorD = new ValordefectoModel();
                foreach ($arregloDefecto as $elemento) {
                    $modelValorD->insertar($valoridCampo, $elemento->idvalor, $elemento->idvalor);
                }
            }

            //-- buscar el valor por defecto a poner segun el campo
            $defecto = " ";
            switch ($tipo) {
                case 'int':
                case 'float':
                case 'bit':
                    $defecto = 0;
                    break;
            }
            $modelValores = new ValorModel();

            //-- por cada fila insertar un valor por defecto para el nuevo campo
            foreach ($filas as $fila) {
                $modelValores->insertar($fila['idfila'], $valoridCampo, $defecto);
            }
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }


    // ---------------------------------------------- eliminar  tablas
    function eliminartablaAction()
    {

        $idTabla = $this->_request->getPost('idtabla');
        $modelTabla = new TablaModel();
        if ($modelTabla->eliminarTabla($idTabla)) {
            $modelEst = new EstructuraModel();
            $cantidad = $modelEst->eliminarEstructurasporTabla($idTabla);
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente . '}");
            //echo("{'codMsg':1,'mensaje': 'Eliminado correctamente . ($cantidad estructuras eliminadas)'}");
        } else
            echo("{'codMsg':3,'mensaje': 'No se pudo eliminar .'}");
    }


    // ----------------------------------------------  eliminar campos
    function eliminarcamposAction()
    {

        $idCampo = $this->_request->getPost('idcampo');
        $modelCampo = new CampoModel();
        $tabla = $modelCampo->buscarTablaPertenece($idCampo);

        if ($modelCampo->eliminarCampo($idCampo)) {
            //-- verficar si es el ultimo campo de la tabla para eliminar las filas;
            /*$modelTabla	= new TablaModel();
            $campos		= $modelTabla->buscarTablasCampos( $tabla['idnomeav'] );

            //-- si no quedan campos mandar a eliminar las filas que quedan
            $eliminar	= ( $campos[0]['NomCampoestruc'][0] ) ? false :  true ;
            if( $eliminar )
            {
                //
                $modelFila	= new FilaModel();
                $modelFila->eliminarFilasTabla( $tabla['idnomeav'] );
                // eliminar las estructuras que quedan
                $modelEst	= new EstructuraModel();
                if( SUFIX == 'op')
                    $modelEst	= new EstructuraopModel( );

                $modelEst->eliminarEstructuraPorTabla(  $tabla['idnomeav']  );

            }*/
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        } else {
            echo("{'codMsg':3,'mensaje': 'No se pudo eliminar .'}");
        }
    }


    // ----------------------------------------------- muestra todos los campos que posee una tabla
    function mostrarcamposAction()
    {

        $idtabla = $this->_request->getPost('idtabla');
        $limit = ($this->_request->getPost('limit')) ? $this->_request->getPost('limit') : 10;
        $start = ($this->_request->getPost('start')) ? $this->_request->getPost('start') : 0;
        $modelCampo = new CampoModel();
        $campos = $modelCampo->buscarCampos($limit, $start, $idtabla);
        $cantidad = $modelCampo->contCampos($idtabla);
        $mostrar = array('cant' => $cantidad, 'datos' => $campos);

        echo(json_encode($mostrar));
    }


    // ----------------------------------------------- muestra todas los tablas que posee la estructura
    function mostrartablasAction()
    {
        $limit = ($this->_request->getPost('limit')) ? $this->_request->getPost('limit') : 10;
        $start = ($this->_request->getPost('start')) ? $this->_request->getPost('start') : 0;
        $modelTabla = new TablaModel();
        $tablas = $modelTabla->buscarTablas(false, $limit, $start);
        $mostrar = array('cant' => count($tablas), 'datos' => $tablas);

        echo(json_encode($mostrar));
    }


    //-------------------------------------------------------- mostrar los tipos de datos definidos
    function mostrartiposdatosAction()
    {

        $tipos = array(
            array('id' => 'integer', 'tipo' => 'integer'),
            array('id' => 'integer unsigned', 'tipo' => 'integer unsigned'),
            array('id' => 'float', 'tipo' => 'float'),
            array('id' => 'float unsigned', 'tipo' => 'float unsigned'),
            array('id' => 'varchar', 'tipo' => 'varchar'),
            array('id' => 'text', 'tipo' => 'text'),
            array('id' => 'correo', 'tipo' => 'correo')
        );
        echo(json_encode(array('cant' => count($tipos), 'datos' => $tipos)));
    }


    //-------------------------------------------------------- mostrar los tipos de datos definidos
    function mostrartiposcamposAction()
    {
        $tipos = array(
            array('id' => 'textfield', 'tipo' => 'textfield'),
            array('id' => 'textarea', 'tipo' => 'textarea'),
            array('id' => 'datefield', 'tipo' => 'datefield'),
            //array('id'=>'numberfield','tipo'=>'numberfield'),
            array('id' => 'combo', 'tipo' => 'combo')
        );
        echo(json_encode(array('cant' => count($tipos), 'datos' => $tipos)));
    }


    //--------------------------------------------------------
    function modificarcamposAction()
    {

        $idcampo = $this->_request->getPost('idcampo');
        $idTabla = $this->_request->getPost('idtabla');
        $nombreCampo = $this->_request->getPost('nombre');
        $tipo = $this->_request->getPost('tipo');
        $longitud = $this->_request->getPost('longitud');
        $nombrec = $this->_request->getPost('nombre_mostrar');
        $regex = $this->clasificador($tipo);
        $visible = ($this->_request->getPost('visible')) && $this->_request->getPost('visible') == 1 ? 1 : 0;
        $tipocampo = $this->_request->getPost('tipocampo');
        $descripcion = $this->_request->getPost('descripcion');
        //-- verificar el tipo de dato que tiene


        $modeloCampo = new CampoModel();

        if ($valoridCampo = $modeloCampo->modificar($idcampo, $idTabla, $nombreCampo, $tipo, $longitud, $nombrec, $regex, $visible, $tipocampo, $descripcion)) {

            $arregloDefecto = json_decode(stripcslashes($this->_request->getPost('vpdcb')));
            if (is_array($arregloDefecto)) {

                $modelValorD = new ValordefectoModel();
                $modelValorD->eliminarValorbyCampo($idcampo);
                foreach ($arregloDefecto as $elemento) {
                    $modelValorD->insertar($idcampo, $elemento->idvalor, $elemento->valor);
                }
            }
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");

        } else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");

    }


    //--------------------------------------------------------
    function cargarhijosAction()
    {
        $idpadre = ($this->_request->getPost('anode')) ? $this->_request->getPost('anode') : false;

        $modelTabla = new TablaModel();
        $hijos = $modelTabla->getHijos($idpadre);

        $datos = array("success" => true, "total" => count($hijos));

        $datos['datos'] = $hijos;
        echo(json_encode($datos));
    }


    // -------------- ACCIONES DE VISUALIZACION  --------------------------------//
    function gestionareavAction()
    {
        $this->render();
    }


    // ----------------------------------------------
    public function cargaretiquetasAction()
    {
        $idioma = $this->_request->getPost('idioma');
        $vista = $this->_request->getPost('vista');
        $controller = 'nivel';
        echo file_get_contents("views/idioma/$idioma/$controller/$vista.json");
        exit();
    }


    /**
     * Inserta un valor por defecto
     *
     */
    function insertarvalorcomboAction()
    {


        //-- comprobar si es un textfield verificar si ya tiene datos no insertar uno nuevo

        //-- si es un combo adicionar un nuevo valor

        $idcampos = $this->_request->getPost('idcombo');
        $valor = $this->_request->getPost('valor');
        $idvalor = $this->_request->getPost('idvalor');
        $modelValorD = new ValordefectoModel();
        if ($modelValorD->insertar($idcampos, $idvalor, $valor)) {
            echo("{'codMsg':1,'mensaje': ' Insertado correctamente.'}");
        } else {
            echo("{'codMsg':3,'mensaje': ' Error insertando el valor por defecto.'}");
        }
    }


    /**
     * Mostrar los valores por defecto de un campo
     *
     */
    function mostrarvalorcomboAction()
    {


        $idcampos = $this->_request->getPost('idcampo');
        $modelValorD = new ValordefectoModel();
        //$modelValorD->
        $arreglo = $modelValorD->buscarValoresCampo($idcampos);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }


    /**
     * Mostrar los valores por defecto de un campo
     *
     */
    function mostrariconosAction()
    {
        $module_path = Zend_Registry::getInstance()->config->modulo_path;
        $dir = $module_path . 'comun/recursos/iconos/';
        $d = dir($dir);
        $archivos = array();
        if (!file_exists($dir)) {
            echo('no existe');
            return;
        }

        $row = 0;
        while ($nombre_archivo = $d->read()) {
            if ($nombre_archivo != "." && $nombre_archivo != ".." && $nombre_archivo != ".svn") {
                $row++;
                $archivos[] = array('id' => $row, 'valor' => $nombre_archivo, 'icono' => $nombre_archivo);
            }
        }

        echo json_encode(array('cant' => count($archivos), 'datos' => $archivos));


    }


    function clasificador($tipo)
    {
        switch ($tipo) {
            case 'integer':
                return '/^-?\d*$/';
                break;
            case 'integer unsigned':
                return '/^\d*$/';
                break;
            case 'varchar':
                return '/^([a-zA-Z????????????????]+ ?[a-zA-Z????????????????]*)+$/';
                break;
            case 'text':
                return '';
                break;
            case 'float':
                return '/^-?\d+(\.\d*)?$/';
                break;
            case 'float unsigned':
                return '/^\d+(\.\d*)?$/';
            case 'correo':
                return '/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/';
                break;
            default:
                return '';

        }
    }

    function geticonAction()
    {
        header('Content-type: image/gif');
        $img = imagecreatefromgif('./comun/recursos/iconos/' . $_GET['icon']);
        imagegif($img);
    }

    function buscarconexionesAction()
    {
        $modelTabla = new TablaModel();

        $idTabla = $this->_request->getPost('idtabla');
        $arreglo = $modelTabla->buscarConexiones($idTabla, false, true);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }

    function insertarconexionesAction()
    {


        $idTabla = $this->_request->getPost('idtabla');
        $idrelacion = $this->_request->getPost('idrelacion');
        $modelTabla = new TablaModel();
        if (!$modelTabla->insertarRelacion($idTabla, $idrelacion)) {
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
        } else
            echo("{succes:true,'codMsg':1,'mensaje': 'Insertado correctamente.'}");
    }

    function eliminarconexionesAction()
    {

        $idTabla = $this->_request->getPost('idtabla');
        $idrelacion = $this->_request->getPost('idrelacion');
        $modelTabla = new TablaModel();
        $eli = $modelTabla->eliminarRelacion($idTabla, $idrelacion);
        if (!$eli) {
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");
        } else
            echo("{succes:true,'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
    }

    // ---------------------------------------------- insercion tablas
    function insertarTablasAction()
    {

        $nombreTabla = $this->_request->getPost('nombre');
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $root = ($this->_request->getPost('root')) ? 1 : 0;
        $concepto = ($this->_request->getPost('concepto')) ? 1 : 0;
        //$externa		= 	($this->_request->getPost('externa')) ?  2 :1;

        //echo  $externa ; die();
        if ($this->_request->getPost('externa') && $this->_request->getPost('interna'))
            $externa = 3;
        elseif ($this->_request->getPost('externa'))
            $externa = 2;
        else $externa = 1;
        $modeloTabla = new TablaModel();
        if ($modeloTabla->insertarn($nombreTabla, $fechaini, $fechafin, $root, $concepto, $externa))
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    //--------------------------------------------------------
    function modificarTablasAction()
    {


        $idTabla = $this->_request->getPost('idtabla');
        $nombreTabla = $this->_request->getPost('nombre');
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $root = ($this->_request->getPost('root')) ? 1 : 0;
        $concepto = ($this->_request->getPost('concepto')) ? 1 : 0;
        //$externa		= 	($this->_request->getPost('externa')) ? 2 :1;

        if ($this->_request->getPost('externa') && $this->_request->getPost('interna'))
            $externa = 3;
        elseif ($this->_request->getPost('externa'))
            $externa = 2;
        else $externa = 1;
        $modeloTabla = new TablaModel();
        if ($modeloTabla->modificar($idTabla, $nombreTabla, $fechaini, $fechafin, $root, $concepto, $externa))
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'Error modificadno  .'}");
    }


}

?>
