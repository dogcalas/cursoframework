<?php
$module_path = Zend_Registry::getInstance()->config->modulo_path;
include($module_path . 'comun/clases/arbolGD/clases/arbol_class.php');
include($module_path . 'comun/clases/arbolGD/clases/nodo_class.php');
include($module_path . 'comun/clases/arbolGD/clases/grafica_class.php');


define('DEBUG_ERP', false);

class EstructuraController extends ZendExt_Controller_Secure
{


    function init()
    {
        parent::init();

    }

    // ---------------------------------------------- tablas
    function tablasAction()
    {
        $modelo = new TablaModel();

        $idTabla = $this->_request->getPost('idtabla');
        echo(json_encode($modelo->buscarTablas($idTabla)));
    }

    // ---------------------------------------------- relacion tablas-campos
    function tablascamposAction()
    {
        $modelo = new TablaModel();

        $idTabla = $this->_request->getPost('idtabla');
        $a = $modelo->buscarTablasCampos($idTabla);
        echo '<pre>';
        print_r($a);
        echo(json_encode($a));
    }

    // ---------------------------------------------- relacion tablas-campos-valores
    function tablascamposvaloresAction()
    {
        $modelo = new TablaModel();

        //-- buscar el idnomeav de la estructura que queremos
        $idTabla = $this->_request->getPost('idtabla');
        $modelEstructura = new EstructuraModel();
        $idnomeav = $modelEstructura->getEstructuraId($idTabla);

        //-- si devuelve 0 filas o falso no encontro nada 


        if (!$idnomeav || count($idnomeav) == 0)
            echo("{'codMsg':3,'mensaje': 'Id tabla no encontrado. '}");
        else {
            $idnomeav = $idnomeav[0]['idnomeav'];

            $a = $modelo->buscarTablasCamposValores($idnomeav);
            echo '<pre>';
            print_r($a);
            echo(json_encode($a));
        }


    }


    // ---------------------------------------------- insercion de valores 
    function insertavaloresAction()
    {
        /*
		elseif ( $suf == 'op' )
		{
			
			$idestructura	= $idpadre;
			$idpadre		= $this->_request->getPost('idop')  */
        $suf = $this->_request->getPost('SUFIX');
        $idTabla = $this->_request->getPost('idtabla');
        if ($suf == 'op')
            $padre = $this->_request->getPost('idop');
        else
            $padre = $this->_request->getPost('idpadre');
        //echo ("idpadre: "); print_r($this->_request->getPost('idpadre')); die;
        //$idprefijo		= 1000;
        $modelpref = new NomprefijoModel();
        $datos = $modelpref->buscarNomprefijo();
        $idprefijo = $datos[0]['idprefijo'];

        if (!$modelpref->existeNomprefijo($idprefijo)) {
            echo("{'codMsg':4,'mensaje': 'No existe prefijo.','detalles':'Debe adicionar un prefijo en el nomenclador de prefijo.'}");
            return;

        }


        //-- buscar todos los campos de la tabla
        $modeloTabla = new TablaModel();
        $campos = $modeloTabla->buscarTablasCampos($idTabla, true);

        //-- insertar una nueva fila
        $modelFilas = new FilaModel();
        //$idFila			= $modelFilas->insertar(  $idTabla );
        $idFila = $modelFilas->insertarConPadre($idTabla, $padre);

        //echo ("idFila: "); print_r($idFila); die;
        if (!$idFila) {
            echo("{'success':false,'codMsg':3,'mensaje': 'No se pudo insertar la fila'}");
            return;
        }
        //-- buscar si se encontro la tabla con sus valores
        $mensajeAlerta = '';
        if (!($campos[0]) || !is_array($campos[0]['NomCampoestruc'])) {
            //echo ("no hay campos");
            //-- si no se encontraron campos enviar mensaje de aviso
            //$mensajeAlerta	= 	' ( no presenta campos espec&iacute;ficos )';

        } else {

            //-- verificar por cada campo lo que estoy recibiendo
            $campos = $campos[0]['NomCampoestruc'];
            $Insertar = array();
            $errores = 0;
            $vacio = false;
            foreach ($campos as $campo) // comprobar e insertar por cada campo
            {
                $campoPost = $this->_request->getPost($campo['nombre']);
                if ($campoPost != '') //-- si el valor fue enviado se inserta
                {
                    $Insertar[$campo['idcampo']] = $campoPost;
                } else {
                    //$errores++;
                    $vacio = $campo['nombre_mostrar'];
                }
            }

            //-- roollback si no se enviaron todos los campos de la tabla
            if ($errores > 0) {


                //-- eliminar la fila creada
                $modelFilas->Instancia();
                $modelFilas->eliminarFila($idFila);
                //-- mensaje de error
                echo("{'success':false,'codMsg':3,'mensaje': 'Campo \"$vacio\" vac&iacute;o.'}");
                return;
            }

            //-- adicionar por cada campo
            $modelValor = new ValorModel();
            foreach ($Insertar as $idCampo => $valor) {
                $modelValor->insertar($idFila, $idCampo, $valor);
            }

        }
        // -------------  INSERTAR DATOS COMUNES DE LAS ESTRUCTURAS--------------


        $idpadre = $this->_request->getPost('idpadre');
        $denominacion = $this->_request->getPost('denominacion');
        $abreviatura = $this->_request->getPost('abreviatura');
        $codigo = $this->_request->getPost('codigo');
        //$idprefijo		= 1000;

        //  verificar tamanos de denominacion y abreviatura

        $fechaini = $this->_request->getPost('fechai');
        $fechafin = $this->_request->getPost('fechaf');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            return;
        }

        $idnivelestr = $this->_request->getPost('idnivelestr');
        $iddpa = $this->_request->getPost('iddpa');
        if ($iddpa == 'iddpa') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una Divisi&oacute;n Pol&iacute;tica Aadministrativa.'}");
            return;
        }

        $obj = new ZendExt_Nomencladores_ADT();
        $existe = $obj->getElement("nom_dpa", $iddpa);
        if ($existe['idtipodpa'] != 3) {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar un Municipio.'}");
            return;
        }
        
        //-- pasar el valor de la tabla en la que se va a insertar dinamicamente
        $idnomeav = $idTabla;

        $nom_organo = $this->_request->getPost('nom_organo');

        if ($suf == '') {
            $modelEst = new EstructuraModel();
            $modelInterna = new EstructuraopModel();
            if (($modelEst->existeCodigoEstexterna($codigo)) || ($modelInterna->existeCodigoEstinterna($codigo))) {
                echo("{'codMsg':3,'mensaje': 'El c&oacute;digo ya existe.'}");
                return;
            }
            $idestructura = $modelEst->insertarEstructura($idFila, $idpadre, $idprefijo, $fechaini, $fechafin, $denominacion, $abreviatura, $idnomeav, $nom_organo, $idespecialidad, $idnivelestr, $iddpa, $codigo);

        } elseif ($suf == 'op') {

            $idestructura = $idpadre;
            $idpadre = $this->_request->getPost('idop');

            $modelEst = new EstructuraopModel();
            $modelExterna = new EstructuraModel();
            /*if (($modelEst->existeCodigoEstinterna($codigo)) || ($modelExterna->existeCodigoEstexterna($codigo)))
			{
				echo("{'codMsg':3,'mensaje': 'El c&oacute;digo ya existe.'}");
				return;
			}*/
            //print_r($idpadre); die;
            $idestructura = $modelEst->insertarEstructuraop($idFila, $idpadre, $idprefijo, $idestructura, $fechaini, $fechafin, $denominacion, $abreviatura, $idnomeav, $nom_organo, $idespecialidad, $idnivelestr, $iddpa, $codigo);

        } else {
            echo("{'success':false,'codMsg':3,'mensaje': 'Error enviando sufijos.'}");
            return;
        }

        if (!$idestructura) {
            //-- eliminar la fila creada
            $modelFilas->Instancia();
            $modelFilas->eliminarFila($idFila);
            echo("{'codMsg':3,'mensaje': 'Error insertando estructura.'}");
        } else
            echo("{'success':true,'codMsg':1,'mensaje': 'Insertado correctamente $mensajeAlerta.'}");
    }


    // ---------------------------------------------- eliminar valores 
    function eliminarvaloresAction()
    {

        $idFila = $this->_request->getPost('idfila');
        $suf = $this->_request->getPost('SUFIX');
        $idcargo = substr($idFila, 0, 1);

        if ($idcargo == 'c') {
            $idFila = substr($idFila, 1);
            $modelcargo = new DatcargoModel();
            if ($modelcargo->eliminarCargo($idFila)) {
                echo("{'success':true,'codMsg':1,'mensaje': 'Eliminado  correctamente.'}");
                return;
            } else {
                echo("{'codMsg':3,'mensaje': 'No se pudo eliminar el cargo.'}");
                return;
            }


        }


        //-- obtener el id de la estructura de la fila a la que pertenece
        $modelFila = new FilaModel();
        $modelEstr = new EstructuraModel();
        $modelEstrop = new EstructuraopModel();
        if ($suf == 'op') {
            if ($modelEstrop->tienehijosop($idFila)) {
                echo("{'codMsg':3,'mensaje': 'Esta estructura no se pude eliminar porque tiene estructuras asociadas.'}");
                return;
            }
        } else {

            if ($modelEstr->tieneHijos($idFila)) {

                echo("{'codMsg':3,'mensaje': 'Esta estructura no se pude eliminar porque tiene estructuras asociadas.'}");
                return;
            }
        }


        if ($modelFila->eliminarFila($idFila))
            echo("{'success':true,'codMsg':1,'mensaje': 'Eliminada  correctamente.'}");
        else
            echo("{'codMsg':3,'mensaje': 'No se pudo eliminar las filas.'}");

    }

    // ----------------------------------------------- muestra todos los campos que posee una tabla
    function mostrarcamposAction()
    {

        $idtabla = $this->_request->getPost('idtabla');
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 100;
        $start = ($start != 0) ? $start : 0;

        $modelCampo = new CampoModel();
        $campos = $modelCampo->buscarCampos($limit, $start, $idtabla);
        $mostrar = array('cant' => count($campos), 'datos' => $campos);
        echo(json_encode($mostrar));

    }


    // ----------------------------------------------- muestra todas los tablas que posee la estructura
    function mostrartablasAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 100;
        $start = ($start != 0) ? $start : 0;
        $modelTabla = new TablaModel();
        $tablas = $modelTabla->buscarTablas(false, $limit, $start);
        $mostrar = array('cant' => count($tablas), 'datos' => $tablas);

        echo(json_encode($mostrar));
    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function construirmenuAction($a)
    {


        echo(json_encode($a));
    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function construirformularioinsercionAction()
    {


        $idtabla = $this->_request->getPost('idtabla');

        $arregloRetorno = array();


        //-- buscar todos los campos de esa tablas
        $modelTabla = new TablaModel();
        $campos = $modelTabla->buscarTablasCampos($idtabla, true);
        $campos = ($campos[0]['NomCampoestruc']) ? $campos[0]['NomCampoestruc'] : array();

        $arregloRetorno    [0] = array();
        // guardar en la posicion 1 la url a la cual el formulario sera enviado
        $arregloRetorno    [1] = array('url' => 'insertavalores');

        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'C&oacute;digo',
            'id' => 'cod',
            'maxLength' => '11',
            'name' => 'codigo',
            'regex' => '/^\d*$/',
            'value' => ''
        );

        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Denominaci&oacute;n',
            'id' => 'den',
            'maxLength' => '255',
            'name' => 'denominacion',
            'regex' => '/^(\W|\w){1,60}$/',
            'value' => ''
        );
        $arr = getdate(time());
        $fecha = $arr["mday"] . "/" . $arr["mon"] . "/" . $arr["year"];
        $arregloRetorno[] = array('xtype' => 'datefield',
            'fieldLabel' => 'Fecha inicio',
            'id' => 'fi',
            'maxLength' => '255',
            'name' => 'fechai',
            'regex' => '',
            'value' => $fecha
        );
        $arregloRetorno[] = array('xtype' => 'datefield',
            'fieldLabel' => 'Fecha fin',
            'id' => 'ff',
            'maxLength' => '255',
            'name' => 'fechaf',
            'regex' => '',
            'value' => ''
        );
        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Abreviatura',
            'id' => 'abre',
            'maxLength' => '20',
            'name' => 'abreviatura',
            'regex' => '/^(\W|\w){1,60}$/',
            'value' => ''
        );


        // buscar todos los posibles datos para el nomenclador de organos
        $modelNomOrg = new NomorganoModel();
        $datos = $modelNomOrg->buscarNomorgano(1000, 0, $idtabla);
        $mensaje = '';
        if (count($datos) == 0) {
            $mensaje = '&oacute;rgano';
        }
        $arregloRetorno[] = array('xtype' => 'combo',
            'fieldLabel' => 'Denominaci&oacute;n del &oacute;rgano',
            'id' => 'nom_organo1',
            'maxLength' => '255',
            'name' => 'nom_organo',
            'regex' => '',
            'value' => '',
            'data' => $datos
        );
        // buscar todos los posibles datos para el nomenclador de organos,


        $obj = new ZendExt_Nomencladores_ADT();


        $especialidad = $obj->getForest('nom_especialidad');


        if (count($especialidad) == 0) {
            $mensaje = $mensaje . 'especialidad' . '.<br/>';
        }

        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Especialidad',
            'id' => 'idespecialidad1',
            'maxLength' => '255',
            'name' => 'nidespecialidad',
            'regex' => '',
            'value' => '',
            'readOnly' => true
        );
        // buscar todos los posibles datos para el nomenclador de organos
        $modelNivelest = new NomnivelestrModel();
        $datos = $modelNivelest->listarNomNivelestr(1000, 0);

        if (count($datos) == 0) {

            $mensaje = $mensaje . 'nivel estructural' . '.<br/>';
        }
        $arregloRetorno[] = array('xtype' => 'combo',
            'fieldLabel' => 'Nivel jer&aacute;rquico',
            'id' => 'idnivelestr1',
            'maxLength' => '255',
            'name' => 'idnivelestr',
            'regex' => '',
            'value' => '',
            'data' => $datos
        );


        $dpa = $obj->getForest('nom_dpa');


        if (count($dpa) == 0) {
            $mensaje = $mensaje . 'Divisi&oacute;n P.A';
        }


        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Divisi&oacute;n P.A',
            'id' => 'iddpa1',
            'maxLength' => '255',
            'name' => 'niddpa',
            'regex' => '',
            'value' => '',
            'valueid' => '',
            'readOnly' => true

        );

        if ($mensaje != '') {
            echo("{'codMsg':4,'mensaje': 'Debe llenar el(los) nomenclador(es) $mensaje.'}");
            return;
        }
        //-- guardar el la posicion 0 el numero de campos
        $arregloRetorno    [0] = array('cantidad' => count($campos) + count($arregloRetorno) - 2);

        // guardar el resto de los campos
        $textareas = array();
        $modelCampos = new CampoModel();
        foreach ($campos as $c) {
            $data = array();
            if ($c['visible'] == 1) $a = false; else $a = true;

            $datos = array('xtype' => $c['tipocampo'],
                'fieldLabel' => ($c['nombre_mostrar']),
                'id' => $c['idcampo'],
                'maxLength' => $c['longitud'],
                'name' => $c['nombre'],
                'regex' => $c['regex'],
                'allowBlank' => $a,
                'value' => ''
            );
            //-- si es un combo hay que agrupar todos los valores que tiene ese campo
            if ($c['tipocampo'] == 'combo') {
                $valores = $modelCampos->buscarValoresDefecto($c['idcampo']);
                $val = array();
                foreach ($valores as $v) {
                    $val[] = array($v['valor'], $v['valor']);
                }
                $datos['data'] = $val;
            } else {
                $val = $modelCampos->buscarValoresDefecto($c['idcampo'], 1);
                $datos['value'] = ($val[0]['valor']) ? $val[0]['valor'] : '';
            }
            if ($c['tipocampo'] == 'textarea')
                $textareas[] = $datos;
            else
                $arregloRetorno[] = $datos;
        }
        //	sleep(2);
        echo(json_encode(array_merge($arregloRetorno, $textareas)));
    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario


    function construirformulariomodificarAction()
    {
        //-- id fila


        $idfila = $this->_request->getPost('idfila');
        $suf = $this->_request->getPost('SUFIX');
        // buscar la tabla a la que pertenece la fila asi como la estructura
        $modelFila = new FilaModel();
        $fila = $modelFila->buscarFilaEstructura($idfila);
        $idest = $idfila;
        $idtabla = $fila[0]['idnomeav'];


        $arregloRetorno = array();


        //-- buscar todos los campos de esa tablas
        $modelTabla = new TablaModel();
        $campos = $modelTabla->buscarTablasCamposValores($idtabla, $idfila);
        $campos = ($campos[0]['NomCampoestruc']) ? $campos[0]['NomCampoestruc'] : array();
        $arregloRetorno    [0] = array();

        // guardar en la posicion 1 la url a la cual el formulario sera enviado
        $arregloRetorno    [1] = array('url' => 'modificarvalores?idtabla=' . $idtabla);

        if ($suf == 'op') {
            //-- buscar los datos del a estructura
            $modelEst = new EstructuraopModel();
            $datosEstr = $modelEst->getEstructuraId($idest);


            $_SESSION['version'] = $datosEstr[0]['version'];

        } else {
            //-- buscar los datos del a estructura
            $modelEst = new EstructuraModel();
            $datosEstr = $modelEst->getEstructuraId($idest);
            $_SESSION['version'] = $datosEstr[0]['version'];


        }

        $idesp = $datosEstr[0]['idespecialidad'];
        $iddpa = $datosEstr[0]['iddpa'];

        $obj = new ZendExt_Nomencladores_ADT();
        $datosDpa = $obj->getElement("nom_dpa", $iddpa);
        $datosEsp = $obj->getElement("nom_especialidad", $idesp);

        //-- guardar en el arreglo la estructura creada
        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'C&oacute;digo',
            'id' => 'cod',
            'maxLength' => '11',
            'name' => 'codigo',
            'regex' => '/^\d*$/',
            'value' => $datosEstr[0]['codigo']
        );

        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Denominaci&oacute;n',
            'id' => 'den',
            'maxLength' => '255',
            'name' => 'denominacion',
            'regex' => '/^(\W|\w){1,60}$/',
            'value' => $datosEstr[0]['denominacion']
        );
        $arregloRetorno[] = array('xtype' => 'datefield',
            'fieldLabel' => 'Fecha inicio',
            'id' => 'fi',
            'maxLength' => '255',
            'name' => 'fechai',
            'regex' => '',
            'value' => $datosEstr[0]['fechaini']
        );
        $arregloRetorno[] = array('xtype' => 'datefield',
            'fieldLabel' => 'Fecha fin',
            'id' => 'ff',
            'maxLength' => '255',
            'name' => 'fechaf',
            'regex' => '',
            'value' => $datosEstr[0]['fechafin']
        );
        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Abreviatura',
            'id' => 'abre',
            'maxLength' => '255',
            'name' => 'abreviatura',
            'regex' => '/^(\W|\w){1,60}$/',
            'value' => $datosEstr[0]['abreviatura']
        );


        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Divisi&oacute;n P.A',
            'id' => 'iddpa1',
            'maxLength' => '255',
            'name' => 'niddpa',
            'regex' => '',
            'valueid' => $datosDpa['iddpa'],
            'value' => $datosDpa['denominacion']

        );

        // buscar todos los posibles datos para el nomenclador de organos
        $modelNomOrg = new NomorganoModel();
        $datos = $modelNomOrg->buscarNomorgano(1000, 0, $idtabla);

        $arregloRetorno[] = array('xtype' => 'combo',
            'fieldLabel' => 'Denominaci&oacute;n del &oacute;rgano',
            'id' => 'nom_organo1',
            'maxLength' => '255',
            'name' => 'nom_organo',
            'regex' => '',
            'valueid' => $datosEstr[0]['NomOrgano']['idorgano'],
            // cambiar la consulta para que coja por defecto el tipo de estructura
            'value' => $datosEstr[0]['NomOrgano']['abrevorgano'],
            'data' => $datos
        );
        //print_r($datosEstr);

        // buscar todos los posibles datos para el nomenclador de organos,
        /*$modelEspecial					= new NomespecialidadModel();
			$datos							= $modelEspecial->listarNomespecialidad( 1000 , 0);
			*/

        $arregloRetorno[] = array('xtype' => 'textfield',
            'fieldLabel' => 'Especialidad',
            'id' => 'idespecialidad1',
            'maxLength' => '255',
            'name' => 'nidespecialidad',
            'regex' => '',
            'valueid' => $datosEsp['idespecialidad'],
            'value' => $datosEsp['denespecialidad']

        );
        // buscar todos los posibles datos para el nomenclador de organos
        $modelNivelest = new NomnivelestrModel();
        $datos = $modelNivelest->listarNomNivelestr(1000, 0);

        $arregloRetorno[] = array('xtype' => 'combo',
            'fieldLabel' => 'Nivel jer&aacute;rquico',
            'id' => 'idnivelestr1',
            'maxLength' => '255',
            'name' => 'idnivelestr',
            'regex' => '',
            'valueid' => $datosEstr[0]['NomNivelestr']['idnivelestr'],
            'value' => $datosEstr[0]['NomNivelestr']['abrevnivelestr'],
            'data' => $datos
        );


        //-- guardar el la posicion 0 el numero de campos
        $arregloRetorno    [0] = array('cantidad' => count($campos) + count($arregloRetorno) - 2);

        // guardar el resto de los campos
        $textareas = array();
        foreach ($campos as $c) {
            $data = array();
            $datos = array('xtype' => $c['tipocampo'],
                'fieldLabel' => /*utf8_decode*/
                    ($c['nombre_mostrar']),
                'id' => $c['idcampo'],
                'maxLength' => $c['longitud'],
                'name' => $c['nombre'],
                'regex' => $c['regex'],
                'valueid' => $c['NomValorestruc'][0]['valor'],
                'value' => $c['NomValorestruc'][0]['valor']
            );
            //-- si es un combo hay que agrupar todos los valores que tiene ese campo
            if ($c['tipocampo'] == 'combo') {
                $modelCampos = new CampoModel();
                $valores = $modelCampos->buscarValoresDefecto($c['idcampo']);
                $val = array();
                foreach ($valores as $v) {
                    $val[] = array($v['valor'], $v['valor']);
                }

                $datos['data'] = $val;
            }
            if ($c['tipocampo'] == 'textarea')
                $textareas[] = $datos;
            else
                $arregloRetorno[] = $datos;
        }
        echo(json_encode(array_merge($arregloRetorno, $textareas)));
    }


    // ----------------------------------------------- dada una tabla envia el json con los campos que va a presentar el grip
    function construircamposgridAction()
    {

        $idtabla = $this->_request->getPost('idtabla');

        $arregloRetorno = array();
        //-- buscar el idnomeav de la estructura que queremos
        $modelEstructura = new EstructuraModel();
        $idnomeav = $modelEstructura->getEstructuraId($idtabla);


        $idnomeav = ($idnomeav[0]['idnomeav']) ? $idnomeav[0]['idnomeav'] : 0;

        //-- buscar todos los campos de esa tablas
        $modelTabla = new TablaModel();
        //$campos			= $modelTabla->buscarTablasCampos($idnomeav);

        //$campos			= ( $campos[0]['NomCampoestruc'] ) ? $campos[0]['NomCampoestruc'] : array();

        //-- guardar las columnas
        $arregloRetorno    [0]['columns'] = array();

        $arreglo = array();
        $arreglo[] = array('id' => 'expandir', 'header' => 'Denominaci&oacute;n', 'dataIndex' => 'denominacion');
        $arreglo[] = array('header' => 'Abreviatura', 'dataIndex' => 'abreviatura');
        $arreglo[] = array('header' => 'Tipo', 'dataIndex' => 'tipo');
        $arreglo[] = array('header' => 'D.P.A.', 'dataIndex' => 'dpa');
        $arreglo[] = array('header' => 'Tipo de Estructura', 'dataIndex' => 'tipoestructura');

        /*foreach ( $campos as $c )
		{
			$arreglo[] = array('header'	=>utf8_decode($c['nombre_mostrar']),
								'dataIndex'	=>$c['nombre']);
		}*/
        $arreglo[] = array('header' => 'Estructura', 'dataIndex' => 'idestructura', 'hidden' => true);
        $arregloRetorno    [0]['columns'] = $arreglo;
        //-- guardar el STORE
        $arregloRetorno    [1]['store'] = array('url' => 'construirvaloresgrid?idtabla=' . $idnomeav,
            'rdRoot' => 'datos',
            'rdTotRec' => 'total',
            'rdId' => 'idestructura'
        );

        //-- para cada campo coger y guardar los nombres
        $arregloRetorno    [1]['store']['rdCampos'] = array();
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'denominacion');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'abreviatura');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'tipo');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'dpa');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'idestructura');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'tipoestructura');

        /*foreach ( $campos as $c )
		{
			$arregloRetorno	[1]['store']['rdCampos'][] = array('name'	=>$c['nombre']);
		}*/

        $idnomeav = $idtabla == 'Estructuras' ? false : $idnomeav;


        $arregloRetorno    [2]['menu'] = $modelTabla->buscarConexiones($idnomeav, true);

        echo(json_encode($arregloRetorno));

    }

    // ----------------------------------------------- dada una tabla envia el json con los campos que va a presentar el grip
    function construirvaloresgridAction()
    {
        $idpadre = $this->_request->getPost('idp');
        if ($idpadre == "Estructuras") {
            return;
        }
        $modelo = new TablaModel();

        if ($_GET['idtabla']) {
            $idTabla = $_GET['idtabla'];
        } else {
            echo("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
            return;
        }

        //$idTabla	= $this->_request->getPost('idtabla');
        $modeloEst = new EstructuraModel();
        $datosEst = $modeloEst->getEstructurasTablashijas($idpadre);

        $valores = array();
        foreach ($datosEst as $dat) {
            $valores[$dat['idfila']]['idestructura'] = $dat['idfila'];

            $valores[$dat['idfila']]['denominacion'] = $dat['DatEstructura']['denominacion'];
            $valores[$dat['idfila']]['abreviatura'] = $dat['DatEstructura']['abreviatura'];
            $valores[$dat['idfila']]['tipo'] = $dat['DatEstructura']['NomOrgano']['denorgano'];
            $valores[$dat['idfila']]['tipoestructura'] = $dat['DatEstructura']['NomNomencladoreavestruc']['nombre'];

            $iddpa = $dat['DatEstructura']['iddpa'];

            $obj = new ZendExt_Nomencladores_ADT();
            $datosDpa = $obj->getElement("nom_dpa", $iddpa);
            $valores[$dat['idfila']]['dpa'] = $datosDpa['denominacion'];


        }

        //- buscar los valores de ese tipo de tabla
        /*$a			= $modelo->buscarTablasCamposValores( $idTabla );
		$campos 	= ($a[0]['NomCampoestruc']) ? $a[0]['NomCampoestruc'] : array() ;
		
		foreach ( $campos as $c	 )
		{
			$nombreCampo	= $c['nombre'];
			foreach ( $c['NomValorestruc'] as $valor )
			{
				$valores[$valor['idfila']][$nombreCampo] = $valor['valor'];
			}
		}*/

        $arr = array();
        $inicio = ($this->_request->getPost('start')) ? $this->_request->getPost('start') : ((($_GET['start']) ? $_GET['start'] : 0));
        $limite = ($this->_request->getPost('limit')) ? $this->_request->getPost('limit') : ((($_GET['limit']) ? $_GET['limit'] : 20));

        $i = 0;
        foreach ($valores as $v) {
            if ($inicio <= $i && $i < ($inicio + $limite))
                $arr[] = $v;

            $i++;
        }
        echo(json_encode(array('total' => count($valores), 'datos' => $arr)));

    }


    /* function construirvaloresgridAction()
	{

		$modelo		= new TablaModel();

		if ($_GET['idtabla']){
			$idTabla = $_GET['idtabla'];
		}else{
		  echo 	("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
		  return ;
		}

		//$idTabla	= $this->_request->getPost('idtabla');
		$modeloEst	= new EstructuraModel();
		$datosEst	= $modeloEst->getEstructurasTablas( $idTabla );

		$valores	= array();
		foreach ($datosEst as $dat)
		{
			$valores[$dat['idfila']]['idestructura'] = $dat['idfila'];

			$valores[$dat['idfila']]['denominacion'] = $dat['DatEstructura']['denominacion'];
			$valores[$dat['idfila']]['abreviatura'] = $dat['DatEstructura']['abreviatura'];
			$valores[$dat['idfila']]['tipo'] = $dat['DatEstructura']['NomOrgano']['denorgano'];

			$iddpa=$dat['DatEstructura']['iddpa'];

			$obj  = new ZendExt_Nomencladores_ADT();
			$datosDpa = $obj->getElement("nom_dpa",$iddpa);
			$valores[$dat['idfila']]['dpa'] = $datosDpa['denominacion'];



		}

		//- buscar los valores de ese tipo de tabla
		$a			= $modelo->buscarTablasCamposValores( $idTabla );
		$campos 	= ($a[0]['NomCampoestruc']) ? $a[0]['NomCampoestruc'] : array() ;

		foreach ( $campos as $c	 )
		{
			$nombreCampo	= $c['nombre'];
			foreach ( $c['NomValorestruc'] as $valor )
			{
				$valores[$valor['idfila']][$nombreCampo] = $valor['valor'];
			}
		}

		$arr	= array();
		$inicio		= ( $this->_request->getPost('start') ) ? $this->_request->getPost('start')  : ( (( $_GET['start']) ? $_GET['start'] : 0 ) ) ;
		$limite		= ( $this->_request->getPost('limit') ) ? $this->_request->getPost('limit') : ( ((  $_GET['limit']) ? $_GET['limit'] : 20 ) ) ;

		$i			= 0;
		foreach ($valores as $v)
		{
			if( $inicio <= $i &&  $i < ( $inicio+$limite ) )
				$arr[]	= $v;

			$i++;
		}
		echo(json_encode(array('total'=>count($valores),'datos'=>$arr)));

	}*/


    /** -----------------------------------------------------------------------
     * Construye los campos que se van a mostrar en el grid de las estructuras op
     *
     */
    function construircamposgridopAction()
    {


        $idtabla = $this->_request->getPost('idtabla');
        $idestructura = $this->_request->getPost('idestructura');

        $idnomeav = 0;
        $datosEstructura = array();

        // verificar si es una estructura interna o una externa
        if ($this->_request->getPost('tipo') == '') {
            $modelEstructura = new EstructuraModel();
            $datosEstructura = $modelEstructura->getEstructuraId($idtabla);
        } else {
            $modelEstructura = new EstructuraopModel();
            $datosEstructura = $modelEstructura->getEstructuraId($idtabla, $idestructura);
        }
        $idestructuraop = ($datosEstructura[0]['idestructuraop']) ? $datosEstructura[0]['idestructuraop'] : 0;
        $idnomeav = ($datosEstructura[0]['idnomeav']) ? $datosEstructura[0]['idnomeav'] : 0;
        //-- buscar todos los campos de esa tablas
        $modelTabla = new TablaModel();
        /*$campos			= $modelTabla->buscarTablasCampos( $idnomeav );
		$campos			= ( $campos[0]['NomCampoestruc'] ) ? $campos[0]['NomCampoestruc'] : array();
		$t				= $modelTabla->buscarTablas();
		*/
        //-- guardar las columnas
        $arregloRetorno = array();
        $arregloRetorno    [0]['columns'] = array();
        $arreglo = array();
        $arreglo[] = array('id' => 'expandir', 'header' => 'Denominaci&oacute;n', 'dataIndex' => 'denominacion');
        $arreglo[] = array('header' => 'Abreviatura', 'dataIndex' => 'abreviatura');
        $arreglo[] = array('header' => 'Tipo', 'dataIndex' => 'tipo');
        $arreglo[] = array('header' => 'Tipo de Estructura', 'dataIndex' => 'tipoestructura');

        /*foreach ( $campos as $c )
		{
			$arreglo[] = array('header'	=>$c['nombre_mostrar'],
								'dataIndex'	=>$c['nombre']);
		}*/
        $arreglo[] = array('header' => 'Estructura', 'dataIndex' => 'idestructura', 'hidden' => true);
        $arregloRetorno    [0]['columns'] = $arreglo;
        //-- guardar el STORE
        $arregloRetorno    [1]['store'] = array('url' => 'construirvaloresgridop?SUFIX=op&idestructuraop=' . $idestructuraop . '&idestructura=' . $idestructura,
            'rdRoot' => 'datos',
            'rdTotRec' => 'total',
            'rdId' => 'idestructura'
        );

        //-- para cada campo coger y guardar los nombres
        $arregloRetorno    [1]['store']['rdCampos'] = array();
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'denominacion');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'abreviatura');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'tipo');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'idestructura');
        $arregloRetorno    [1]['store']['rdCampos'][] = array('name' => 'tipoestructura');

        /*foreach ( $campos as $c )
		{
			$arregloRetorno	[1]['store']['rdCampos'][] = array('name'	=>$c['nombre']);
		}*/
        $idB = $idnomeav;

        $arregloRetorno    [2]['menu'] = $modelTabla->buscarConexiones($idnomeav, false);

        echo(json_encode($arregloRetorno));

    }

    // ----------------------------------------------- dada una tabla envia el json con los campos que va a presentar el grip
    function construirvaloresgridopAction()
    {
        $idestructuraop = $this->_request->getPost('idestructuraop');
        //$modelo			= new TablaModel();

        if ($_GET['idestructuraop']) {
            $idestructuraop = $_GET['idestructuraop'];
        } else {
            if ($_GET['idestructuraop'] == 0) {
                return;
            }
            echo("{'codMsg':3,'mensaje': 'Id estructura interna no enviado'}");
            return;
        }
        if ($_GET['idestructura'] && $_GET['idestructura'] != '') {
            $idestructura = $_GET['idestructura'];
        } else {
            echo("{'codMsg':3,'mensaje': 'Id estructura no enviado.'}");
            return;
        }

        //-- buscar los vaores en la tabla estructuras
        $modeloEst = new EstructuraopModel();
        $datosEst = $modeloEst->getEstructurasTablashijas($idestructuraop, $idestructura);
        $valores = array();
        foreach ($datosEst as $dat) {
            $valores[$dat['idfila']]['idestructura'] = $dat['idfila'];

            $valores[$dat['idfila']]['denominacion'] = $dat['DatEstructuraop']['denominacion'];
            $valores[$dat['idfila']]['abreviatura'] = $dat['DatEstructuraop']['abreviatura'];
            $valores[$dat['idfila']]['tipo'] = $dat['DatEstructuraop']['NomOrgano']['denorgano'];
            $valores[$dat['idfila']]['tipoestructura'] = $dat['DatEstructuraop']['NomNomencladoreavestruc']['nombre'];

        }
        //echo '<pre>';
        //print_r($valores);

        //- buscar los valores de ese tipo de tabla
        /*$a			= $modelo->buscarTablasCamposValores( $idTabla );
		$campos 	= ($a[0]['NomCampoestruc']) ? $a[0]['NomCampoestruc'] : array() ;
		
		foreach ( $campos as $c	 )
		{
			$nombreCampo	= $c['nombre'];
			foreach ( $c['NomValorestruc'] as $valor )
			{
				if( ( $valores[$valor['idfila']] ) )
					$valores[ $valor['idfila'] ][ $nombreCampo ] = $valor['valor'];
			}
		}*/

        $arr = array();
        $inicio = ($this->_request->getPost('start')) ? $this->_request->getPost('start') : ((($_GET['start']) ? $_GET['start'] : 0));
        $limite = ($this->_request->getPost('limit')) ? $this->_request->getPost('limit') : ((($_GET['limit']) ? $_GET['limit'] : 20));
        $i = 0;
        foreach ($valores as $v) {
            if ($inicio <= $i && $i < ($inicio + $limite))
                $arr[] = $v;

            $i++;
        }
        //	echo '<pre>';
        //	print_r($valores);
        echo(json_encode(array('total' => count($valores), 'datos' => $arr)));

    }

    // ----------------------------------------------- dada una tabla envia el json con los campos y valores para contruir el formulario
    function buscarhijosAction()
    {
        $idestructura = $this->_request->getPost('node');
        //$idestructura=100000001;
        if ($idestructura != '') {
            if ($idestructura == 'Estructuras')
                $idest = false;
            else
                $idest = $idestructura;
        } else {
            echo("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
            return;
        }


        $modelEstructura = new EstructuraModel();

        echo(json_encode($modelEstructura->getHijos($idest)));
    }

    // ----------------------------------------------- (para el arbol de agrupaciones)dada una tabla envia el json con los campos y valores para contruir el formulario
    function buscarhijosagrupAction()
    {


        $idest = $this->_request->getPost('node');

        $modelEstructura = new EstructuraModel();

        echo(json_encode($modelEstructura->getHijos($idest)));
    }

    // -----------------------------------------------------------------------------//
    function gestionarestructuraAction()
    {
        $this->render();
    }

    // -----------------------------------------------------------------------------//
    function interfazestructuraAction()
    {
        $this->render();
    }


    public function pruebaAction()
    {
        /*$modelTabla		= new TablaModel();
		$campos			= $modelTabla->buscarTablasCamposValores(1, 1);
		*/


        $idop = 80;

        $modelEst = new EstructuraModel();
        $estructuras = $modelEst->getEstructurasInternasServicio($idop, false);


        //$mo	= new CampoModel();
        echo '<pre>';
        print_r($estructuras);
        return;


        /*$modelFilas	= new FilaModel();
			$filas		= $modelFilas->buscarFilas( 1 );
			echo '<pre>';
			print_r($filas);*/

        /**$modeloTabla    = new TablaModel();
         * $campos            = $modeloTabla->buscarTablasCampos( 1 ) ;
         * echo '<pre>';
         * print_r($campos);

         */
        /*$modeloTabla	= new CampoModel();
		$campos			= $modeloTabla->buscarTablaPertenece( 1 ) ;
		echo '<pre>';
			print_r($campos);*/
        /*$modeloEst	= new EstructuraModel();
		$datosEst	= $modeloEst->getEstructurasTablas( 2 );
			echo '<pre>';
			print_r($datosEst);
		*/
        /*	$modelTabla		= new EstructuraModel();
		$arreglo			= $modelTabla->getArbol(60);
		$llaves=array();
		foreach ( $arreglo as $hi)
		{
			$llaves[]	= $hi['idestructura'];
		}
		foreach ( $arreglo as $hi)
		{
			
			$idpadre	= $hi['idestructura'] == $hi['idpadre'] ? false : $hi['idpadre'];
			if($idpadre && array_search($idpadre,$llaves)=== false)
			$idpadre	= false;
			$hijos[]	= new CNodo($hi['idestructura'],$idpadre,$hi['idestructura'].'-'.$hi['abreviatura']);
		}
		//$campos			= $campos[0]['NomCampoestruc'];*/
        /*
	$modeloTabla	= new TablaModel();
		$datosEst			= $modeloTabla->buscarTablasCampos( 14 ,true) ;
		*/
        /*	$modelNomOrg					= new NomorganoModel();
		$datos							= $modelNomOrg->buscarNomorgano(1000,0,1);
		*/

        $modelEstructura = new EstructuraModel();


        echo '<pre>';
        print_r($modelEstructura->getHijosp(60));
        //echo json_encode($datos);

    }

    //--------------------------------------------------------
    function modificarvaloresAction()
    {

        $idfila = $this->_request->getPost('idfila');
        $suf = $this->_request->getPost('SUFIX');
        // buscar la tabla a la que pertenece la fila asi como la estructura
        if ($suf == 'op') {
            $modelesrt = new EstructuraopModel();
            $datosEstr = $modelesrt->getEstructuraId($idfila);
        } else {
            $modelesrt = new EstructuraModel();
            $datosEstr = $modelesrt->getEstructuraId($idfila);
        }

        $version1 = $datosEstr[0]['version'];


        if ($version1 != $_SESSION['version']) {

            echo("{'codMsg':4,'mensaje': 'Esta estructura fue modificada anteriormente, cierre la ventana y vuelva a intentarlo.'}");
            return;
        }


        $modelFila = new FilaModel();
        $fila = $modelFila->buscarFilaEstructura($idfila);
        $idest = $idfila;
        $idtabla = $fila[0]['idnomeav'];

        $arregloRetorno = array();

        //-- buscar todos los campos de la tabla


        $denominacion = $this->_request->getPost('denominacion');
        $abreviatura = $this->_request->getPost('abreviatura');
        $codigo = $this->_request->getPost('codigo');
        $fechaini = $this->_request->getPost('fechai');
        $fechafin = $this->_request->getPost('fechaf');
        $iddpa = $this->_request->getPost('iddpa');
        if ($iddpa == 'iddpa') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar un Divisi&oacute;n Pol&iacute;tica Aadministrativa.'}");
            return;
        }
        $obj = new ZendExt_Nomencladores_ADT();
        $existe = $obj->getElement("nom_dpa", $iddpa);
        if ($existe['idtipodpa'] != 3) {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar un Municipio.'}");
            return;
        }
        $nom_organo = $this->_request->getPost('nom_organo');
        $nom_nivelestr = $this->_request->getPost('idnivelestr');
        $nom_especialidad = $this->_request->getPost('idespecialidad');
        $suf = $this->_request->getPost('SUFIX');
        if ($suf == 'op') {
            $modelNomOrg = new NomorganoModel();
            $idOrg = $modelNomOrg->getID($nom_organo, $idtabla);

            $modelEst = new EstructuraopModel();
            $modelEst->modificarEstructura($idest, $fechaini, $fechafin, $denominacion, $abreviatura, $nom_organo, $codigo, $iddpa, $nom_nivelestr, $nom_especialidad);
        } else {
            $modelNomOrg = new NomorganoModel();

            $modelEst = new EstructuraModel();
            /*$modelInterna	= new EstructuraopModel();
			
			
			
			if (($modelEst->existeCodigoEstexterna($codigo)) ||( $modelInterna->existeCodigoEstinterna($codigo)))
			{
				echo("{'codMsg':3,'mensaje': 'El c&oacute;digo ya existe.'}");
				return;
			}*/

            $modelEst->modificarEstructura($idest, $fechaini, $fechafin, $denominacion, $abreviatura, $nom_organo, $codigo, $iddpa, $nom_nivelestr, $nom_especialidad);
        }
        $modeloTabla = new TablaModel();
        $campos = $modeloTabla->buscarTablasCampos($idtabla, true);

        //-- buscar si se encontro la tabla con sus valores
        if (($campos[0]) && count($campos[0]['NomCampoestruc']) != 0) {
            //-- verificar por cada campo lo que estoy reciviendo
            $campos = $campos[0]['NomCampoestruc'];
            $Modificar = array();
            $errores = 0;
            $vacio = false;
            foreach ($campos as $campo) {

                if (($this->_request->getPost($campo['nombre'])) && $this->_request->getPost($campo['nombre']) != '') //-- si el valor fue enviado se inserta
                {
                    $Modificar[$campo['idcampo']] = $this->_request->getPost($campo['nombre']);
                } else {
                    $errores++;
                    $vacio = $campo['nombre_mostrar'];
                }
            }

            //-- roollback si no se enviaron todos los campos de la tabla
            /*if( $errores > 0)
			{
				//-- mensaje de error
				echo("{'codMsg':3,'mensaje': 'Campo \"$vacio\" sin enviar.'}");
				return;	
			}*/

            //-- adicionar por cada campo
            $modelValor = new ValorModel();
            foreach ($Modificar as $idCampo => $valor) {
                if (!$modelValor->modificar($idfila, $idCampo, $valor)) {
                    echo("{'success':true,'codMsg':3,'mensaje': 'Error modificando.'}");
                    return;
                }
            }
        }
        echo("{'success':true,'codMsg':1,'mensaje': 'Modificado correctamente.'}");

    }


    function mostrarorganigramaAction()
    {
        $idpadre = ($_GET['idpadre']) ? $_GET['idpadre'] : 'Estructuras';

        $modelEst = new EstructuraModel();
        $arreglo = $modelEst->getArbol($idpadre);
        foreach ($arreglo as $hi) {
            $idpadre = $hi['idestructura'] == $hi['idpadre'] ? -1 : $hi['idpadre'];
            $hijos[] = array("id" => $hi['idestructura'], "idpadre" => $idpadre, "texto" => $hi['idestructura'] . '-' . $hi['abreviatura']);
        }
        echo(json_encode($hijos));
    }


    function mostrarorganigramaestaticoAction()
    {
        $idpadre = ($_GET['idpadre']) ? $_GET['idpadre'] : 'Estructuras';

        $modelEst = new EstructuraModel();
        $arreglo = $modelEst->getArbol($idpadre);
        $llaves = array();
        foreach ($arreglo as $hi) {
            $llaves[] = $hi['idestructura'];
        }
        foreach ($arreglo as $hi) {

            $idpadre = $hi['idestructura'] == $hi['idpadre'] ? false : $hi['idpadre'];
            if ($idpadre && array_search($idpadre, $llaves) === false)
                $idpadre = false;
            $hijos[] = new CNodo($hi['idestructura'], $idpadre, $hi['idestructura'] . '-' . $hi['abreviatura']);
        }


        $arbol = new CArbol($hijos);
        $dib = new CDibujaArbol();
        $dib->dibujarArbol($arbol);
    }


    /**
     * Busca la composicion de una estructura
     *
     */
    function buscarcomposicionAction()
    {

        //-- si el id enviado es COmposicion entonces se  cambia de arbol al de estructuras internas
        $idestructura = $this->_request->getPost('idestructura');
        $idop = $this->_request->getPost('node');

        if ($idop == $idestructura) {
            $estructuras = array();
            if ($idestructura != 'Estructuras') {
                $modelEst = new EstructuraModel();
                $estructuras = $modelEst->getEstructurasInternas($idestructura, true);
            }
            echo json_encode($estructuras);
        } else {
            //-- si el id op es diferentede estructuras
            if ($idop != 'Estructuras') {
                $modelCargos = new DatcargoModel();
                $cargos = $modelCargos->buscarCargoLineal(1000, 0, $idop);
                $modelEst = new EstructuraopModel();
                $estructuras = $modelEst->getHijos($idop);
                $modelMedios = new DattecnicaModel();
                $retorno = array();
                $medios = $modelMedios->buscarDatTecnicaLineal($idop);
                if (($medios[0]))
                    $retorno = array_merge_recursive($cargos, $medios);
                else
                    $retorno = $cargos;
                $retorno = array_merge_recursive($retorno, $estructuras);

                echo json_encode($retorno);
            } else
                echo json_encode(array());

        }

    }


    /**  -------------------------------------------------
     *                    GESTIONAR CARGOS
     *  -------------------------------------------------- */


    function insertarcargocivilAction()
    {

        $modelpref = new NomprefijoModel();
        $datos = $modelpref->buscarNomprefijo(10, 0);
        $idprefijo = $datos[0]['idprefijo'];

        if (!$modelpref->existeNomprefijo($idprefijo)) {
            echo("{'codMsg':4,'mensaje': 'No existe prefijo.','detalles':'Debe adicionar un prefijo en el nomenclador de prefijo.'}");
            return;

        }

        $idestructuraop = $this->_request->getPost('idop');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            return;
        }

        $ctp = $this->_request->getPost('ctp');
        $ctg = $this->_request->getPost('ctg');
        $idtipocifra = $this->_request->getPost('idtipocifra');
        //$idprefijo			= 1000;
        $orden = $this->_request->getPost('orden');
        $estado = 1;
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $idcategcivil = $this->_request->getPost('idcategcivil');
        $idnomcargicivil = $this->_request->getPost('idcargociv');
        $salario = $this->_request->getPost('idsalario');
        $idgrupocomple = $this->_request->getPost('idgrupocomplejidad');
        $idescalasalarial = $this->_request->getPost('idescalasalarial');
        $idclasificacion = $this->_request->getPost('idclasificacion');
        $modificable = $this->_request->getPost('modificable');

        $modelCargo = new DatcargoModel();
        $modelCargoCivil = new DatcargocivilModel();
        $idcargo = false;
        if ($idcargo = $modelCargo->insertarCargo($idestructuraop, $idespecialidad, $idtipocifra, $idprefijo, $ctp, $ctg, $orden, $estado, $fechaini, $fechafin)) {


            if ($modelCargoCivil->insertarCargocivil($idcargo, $idnomcargicivil, $idcategcivil, $salario, $idgrupocomple, $idescalasalarial, $idclasificacion, $modificable))
                echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
            else {
                $modelCargo->eliminarCargo($idcargo);
                echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
            }
        } else {
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
        }


    }


    function modificarcargocivilAction()
    {
        $idcargo = $this->_request->getPost('idcargo');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            return;
        }
        $ctp = $this->_request->getPost('ctp');
        $ctg = $this->_request->getPost('ctg');
        $idtipocifra = $this->_request->getPost('idtipocifra');

        $orden = $this->_request->getPost('orden');
        $estado = 1;
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $idcategcivil = $this->_request->getPost('idcategcivil');
        $idnomcargicivil = $this->_request->getPost('idcargociv');
        $salario = $this->_request->getPost('idsalario');
        $idgrupocomple = $this->_request->getPost('idgrupocomplejidad');
        $idescalasalarial = $this->_request->getPost('idescalasalarial');
        $idclasificacion = $this->_request->getPost('idclasificacion');
        $modificable = $this->_request->getPost('modificable');


        $modelCargo = new DatcargoModel();
        $modelCargoCivil = new DatcargocivilModel();
        if ($modelCargo->modificarCargo($idcargo, $idespecialidad, $idtipocifra, $ctp, $ctg, $orden, $estado, $fechaini, $fechafin)) {

            if ($modelCargoCivil->modificarCargocivil($idcargo, $idnomcargicivil, $idcategcivil, $salario, $idgrupocomple, $idescalasalarial, $idclasificacion, $modificable))
                echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
            else {
                $modelCargo->eliminarCargo($idcargo);
                echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
            }
        } else {
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
        }


    }

    function verificarcombocargocivilAction()
    {
        $modelNomciv = new NomcategcivilModel();

        $categcivil = $modelNomciv->cantNomcategcivil();

        if ($categcivil == 0)
            $categcivil = 'categor&iacute;a civil';
        else
            $categcivil = '';

        $modelCifra = new NomtipocifraModel();
        $nomtipocif = $modelCifra->buscarNomtipocifra(10, 0);
        $nomtipocif = count($nomtipocif);

        if ($nomtipocif == 0)
            $nomtipocif = 'tipo de cifra';
        else
            $nomtipocif = '';


        $modelc = new NomclasificacioncargoModel();
        $clasif = $modelc->conNomclasificacion();

        if ($clasif == 0)
            $clasif = 'clasificaci&oacute;n.';
        else
            $clasif = '';
        $modelCatgc = new NomcargocivilModel();
        $nomcargociv = $modelCatgc->buscarNomcargocivil(10, 0);

        $nomcargociv = count($nomcargociv);

        if ($nomcargociv == 0)
            $nomcargociv = 'Responsabilidad.';
        else
            $nomcargociv = '';


        $band = 0;

        if ($categcivil != '') {
            $mensaje = $categcivil . '.<br/>';
            $band = 1;
        }


        if ($nomtipocif != '') {
            $mensaje = $mensaje . $nomtipocif . '.<br/>';
            $band = 1;
        }

        if ($clasif != '') {
            $mensaje = $mensaje . $clasif . '.<br/>';
            $band = 1;
        }

        if ($nomcargociv != '') {
            $mensaje = $mensaje . $nomcargociv . '.';
            $band = 1;
        }

        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje'}");

    }

    function verificarcombocargomilitarAction()
    {
        $limit = 0;
        $start = 0;
        $modelCifra = new NomtipocifraModel();
        $nomtipocif = $modelCifra->buscarNomtipocifra(10, 0);
        $nomtipocif = count($nomtipocif);

        if ($nomtipocif == 0)
            $nomtipocif = 'tipo de cifra';
        else
            $nomtipocif = '';
        $modelNomCarMtar = new NomcargomilitarModel();
        $nomcargomtar = $modelNomCarMtar->buscarNomcargomtar(10, 0);
        $nomcargomtar = count($nomcargomtar);
        if ($nomcargomtar == 0)
            $nomcargomtar = 'cargo militar';
        else
            $nomcargomtar = '';

        $modelNomGrado = new NomgradomilitarModel();
        $grado = $modelNomGrado->buscarGradomtar($limit, $start);
        $grado = count($grado);
        if ($grado == 0)
            $grado = 'grado militar';
        else
            $grado = '';


        $band = 0;

        if ($nomcargomtar != '') {
            $mensaje = $nomcargomtar . '.<br/>';
            $band = 1;
        }


        if ($nomtipocif != '') {
            $mensaje = $mensaje . $nomtipocif . '.<br/>';
            $band = 1;
        }

        if ($grado != '') {
            $mensaje = $mensaje . $grado . '.';
            $band = 1;
        }


        if ($band == 0)
            echo(json_encode(array('text' => array())));
        else
            echo("{'codMsg':4,'mensaje':'Debe llenar el(los) nomenclador(es): $mensaje'}");
    }


    /**
     * Mostrar en el combox de categoria civil;
     *
     */
    function mostrarcatgriacvilAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 100;
        $start = ($start != 0) ? $start : 0;

        $modelNomciv = new NomcategcivilModel();

        $total = $modelNomciv->cantNomcategcivil();
        $tablas = $modelNomciv->buscarNomcategcivil($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tablas);


        echo(json_encode($mostrar));

    }


    /**
     * Musetra en el combo de cargo civil
     */
    function buscarsalarioporgrupoescalaAction()
    {


        $grupocomple = $this->_request->getPost('idgrupocomplejidad');
        $escalasalarial = $this->_request->getPost('idescalasalarial');
        $m = new NomsalarioModel();
        $tabla = $m->buscarNomsalarioPorgrupyescala($grupocomple, $escalasalarial);
        $total = count($tabla);

        echo(json_encode(array('cant' => $total, 'datos' => $tabla)));
    }

    /** --------------------------------------------------------
     * Muestra los cargos en el grid
     *
     */
    function mostrarcargosAction()
    {


        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $idop = $this->_request->getPost('idop');
        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;
        $modelCargo = new DatcargoModel();
        $arreglo = $modelCargo->buscarCargo($limit, $start, $idop);

        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));
    }

    /** --------------------------------------------------------
     * Muestra los cargos en el grid
     *
     */
    function mostrardatoscargoAction()
    {


        $idcargo = $this->_request->getPost('idcargo');

        $militar = $this->_request->getPost('tipocargo');
        if ($militar == 'militar')
            $militar = true;
        else
            $militar = false;

        $modelCargo = new DatcargoModel();

        $arreglo = $modelCargo->datosCargo($idcargo, $militar);

        //echo '<pre>';
        //print_r($arreglo);
        //die('ee');
        $obj = new ZendExt_Nomencladores_ADT();
        $idesp = $arreglo[0]['idespecialidad'];

        //$datosEsp 	=	( !$idesp ) 	?		 array() : $obj->getElement("nom_especialidad",$idesp) ;
        $datosEsp = $obj->getElement("nom_especialidad", $idesp);

        $p = array('viejo' => $arreglo, 'nuevo' => $datosEsp);
        $tablas[] = $p;
        //$tablas[]['p'] =$arreglo;
        //$tablas[]['r']=$datosEsp;

        //echo json_encode   
        //print_r(array( 'success'=>true , 'datos'=>$tablas ) );//*/
        echo json_encode(array('success' => true, 'datos' => $tablas));
    }

    /** ----------------------------------------------------------------------
     *    MUESTRA DE DATOS DE NOMENCLADORES PARA LLENAR LOS COMBOS DE LAS VISTAS
     */
    /** --------------------------------------------------------
     * Mostrar los nomencladores del valor de tipos de cifras
     *
     */
    function mostrarcifrasAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelCifra = new NomtipocifraModel();
        $arreglo = $modelCifra->buscarNomtipocifra($limit, $start);


        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }

    /** --------------------------------------------------------
     * Mostrar los nomencladores del valor de tipos de cifras
     *
     */
    function mostrarespecialidadesAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelEsp = new NomespecialidadModel();
        $arreglo = $modelEsp->buscarNomespecialidad($limit, $start);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }

    /** --------------------------------------------------------
     * Mostrar los nomencladores del valor  de categorias de cargos civiles
     *
     */
    function mostrarcategoriascivilesAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelCatgc = new NomcategcivilModel();
        $arreglo = $modelCatgc->buscarNomcategcivil($limit, $start);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));
    }

    /**
     * Mostrar el grupo de complejidad de un cargo civil;
     *
     */
    function mostrarnomgrupocompleAction()
    {

        $idcargociv = $this->_request->getPost('idcargociv');
        $idccv = ($idcargociv != '') ? $idcargociv : false;

        $m = new NomgrupocompleModel();
        $arreglo = $m->nomNomgrupoc($idccv);

        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));


    }

    /** --------------------------------------------------------
     * Mostrar los nomencladores del valor  de  cargos civiles
     *
     */
    function mostrarnomcargocivilAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;

        $modelCatgc = new NomcargocivilModel();
        $arreglo = $modelCatgc->buscarNomcargocivil($limit, $start, $denom);

        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));


    }

    /**
     * Mostrar el nomenclador escalasalarial
     *
     */
    function mostrarnomescalasalarialAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;
        $modelescala = new NomescalasalarialModel();
        $total = $modelescala->countNomescalasalarial();
        $tablas = $modelescala->buscarNomescalasalarial($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));

    }

    /**
     * Mostrar la clasificacion.
     *
     */
    function mostrarnomclasificacionAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;
        $modelc = new NomclasificacioncargoModel();
        $total = $modelc->conNomclasificacion();
        $tablas = $modelc->buscarNomclasificacion($limit, $start);

        $mostrar = array('cant' => $total, 'datos' => $tablas);


        echo(json_encode($mostrar));


    }


    /** --------------------------------------------------------
     * Insertar un cargo militar
     *
     */
    function insertarcargomilitarAction()
    {

        $idestructuraop = $this->_request->getPost('idop');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            return;
        }
        $ctp = $this->_request->getPost('ctp');
        $ctg = $this->_request->getPost('ctg');
        $idtipocifra = $this->_request->getPost('idtipocifra');
        $modelpref = new NomprefijoModel();
        $datos = $modelpref->buscarNomprefijo(10, 0);
        $idprefijo = $datos[0]['idprefijo'];

        //$idprefijo			= 1000;
        $orden = $this->_request->getPost('orden');
        $estado = $this->_request->getPost('estado');
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $idgradomtar = $this->_request->getPost('idgradomilit');
        $idnomcargomtar = $this->_request->getPost('idcargomilitar');
        $tienemando = $this->_request->getPost('tienemando');
        $salario = $this->_request->getPost('salario');
        $tienemando = ($tienemando == 1) ? 1 : 0;


        $modelCargo = new DatcargoModel();
        $modelCargoMtar = new DatcargomtarModel();
        $idcargo = false;
        if ($idcargo = $modelCargo->insertarCargo($idestructuraop, $idespecialidad, $idtipocifra, $idprefijo, $ctp, $ctg, $orden, $estado, $fechaini, $fechafin)) {
            if ($modelCargoMtar->insertarDatcargomtar($idcargo, $idnomcargomtar, $idgradomtar, $salario, $tienemando))
                echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");

        } else {
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
        }


    }

    /**  --------------------------------------------------------
     * Modificar un cargo militar
     *
     */
    function modificarcargomilitarAction()
    {
        $idcargo = $this->_request->getPost('idcargo');
        $idespecialidad = $this->_request->getPost('idespecialidad');
        if ($idespecialidad == 'idespecialidad') {
            echo("{'codMsg':3,'mensaje': 'Debe seleccionar una especilidad.'}");
            return;
        }
        $ctp = $this->_request->getPost('ctp');
        $ctg = $this->_request->getPost('ctg');
        $idtipocifra = $this->_request->getPost('idtipocifra');
        /*$modelpref		= new NomprefijoModel();
		$datos  = $modelpref->buscarNomprefijo(10,0);
		$idprefijo = $datos[0]['idprefijo'];*/
        $orden = $this->_request->getPost('orden');
        $estado = $this->_request->getPost('estado');
        $fechaini = $this->_request->getPost('fechaini');
        $fechafin = $this->_request->getPost('fechafin');
        $idgrado = $this->_request->getPost('idgradomilit');
        $idnomcargomtar = $this->_request->getPost('idcargomilitar');
        $tienemando = $this->_request->getPost('tienemando');
        $salario = $this->_request->getPost('salario');
        $tienemando = ($tienemando == 1) ? 1 : 0;


        $modelCargo = new DatcargoModel();
        $modelCargoMtar = new DatcargomtarModel();

        if ($modelCargo->modificarCargo($idcargo, $idespecialidad, $idtipocifra, $ctp, $ctg, $orden, $estado, $fechaini, $fechafin)) {


            if ($modelCargoMtar->modificarDatcargomtar($idcargo, $idnomcargomtar, $idgrado, $salario, $tienemando))
                echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");

        } else {
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
        }


    }

    /** --------------------------------------------------------
     * Mostrar los nomencladores del valor  de  cargos civiles
     *
     */
    function mostrarnomcargomtarAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $denom = $this->_request->getPost('den');

        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;

        $modelNomCarMtar = new NomcargomilitarModel();
        $arreglo = $modelNomCarMtar->buscarNomcargomtar($limit, $start, $denom);
        $cantidad = $modelNomCarMtar->cantNomcargMilitar();
        echo json_encode(array('cant' => $cantidad, 'datos' => $arreglo));

    }


    /** --------------------------------------------------------
     * Mostrar los nomencladores del grado militar
     *
     */
    function mostrarnomgradomtarAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelNomGrado = new NomgradomilitarModel();
        $arreglo = $modelNomGrado->buscarGradomtar($limit, $start);

        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }


    function eliminarcargoAction()
    {


        $idcargo = $this->_request->getPost('idcargo');
        $modelCargo = new DatcargoModel();
        if ($modelCargo->eliminarCargo($idcargo)) {
            echo("{'codMsg':1,'mensaje': 'Eliminado correctamente'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error eliminando cargo'}");
    }

    /** ---------------------------------------------------------------------------
     *
     * GESTION DE PUESTOS DE TRABAJOS ASOCIADOS A LOS CARGOS
     *  ---------------------------------------------------------------------------
     */

    /**
     * Inserta un puesto de trabajo
     *
     */


    /**
     * Elimina una estructura de la relacion con agrupacion
     *
     */
    function eliminarestructuraagrupacionAction()
    {

        $idagrupacion = $this->_request->getPost('idagrupacion');
        $idestructura = $this->_request->getPost('idestructura');
        $modelAgrup = new DatagrupacionestModel();
        if (!$modelAgrup->existeAgrupacionest($idagrupacion, $idestructura)) {
            echo("{'codMsg':3,'mensaje': 'Agrupacion no existe.'}");
            return;
        }

        if ($modelAgrup->eliminarAgrupacionet($idagrupacion, $idestructura)) {
            echo("{'codMsg':1,'mensaje': 'Estructura eliminada.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error eliminando estructura.'}");
    }

    /**
     * Inserta una relacion de agrupacion
     *
     */
    function insertarestructuraagrupacionAction()
    {

        $idagrupacion = $this->_request->getPost('idagrupacion');
        $idestructura = $this->_request->getPost('idestructura');
        $modelAgrup = new DatagrupacionestModel();
        if ($modelAgrup->existeAgrupacionest($idagrupacion, $idestructura)) {
            echo("{'codMsg':3,'mensaje': 'Estructura creada anteriormente.'}");
            return;
        }

        if ($modelAgrup->insertarAgrupaciones($idestructura, $idagrupacion)) {
            echo("{'success':true,'codMsg':1,'mensaje': 'Estructura agregada a la agrupaci&oacute;n correctamente.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error agregando estructura.'}");
    }

    /**
     * Muestra los datos del nomenclador de agrupaciones
     *
     */
    function mostraragrupacionAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelAgrup = new DatagrupacionestModel();
        $arreglo = $modelAgrup->buscarAgrupacionestNom($limit, $start);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));
    }

    /**
     * Mostrar las estructuras relacionadas con las agrupaciones n-m
     *
     */
    function mostrarestructuraagrupacionAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 10;
        $start = ($start != 0) ? $start : 0;


        $idagrup = $this->_request->getPost('idagrupacion');

        $modelAgrup = new DatagrupacionestModel();
        $arreglo = $modelAgrup->buscarAgrupacionest($idagrup, $limit, $start);
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));
    }


    /** -------------------------------------------------------------------
     *
     *
     */

    function mostrartecnicaAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;

        $idcargo = $this->_request->getPost('idestructura');

        $modelTecnica = new DattecnicaModel();
        $arreglo = ($modelTecnica->buscarDatTecnica($idcargo, $limit, $start));
        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));

    }

    function mostrarntecnicaAction()
    {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;

        $modelTecica = new NomtecnicaModel();
        $arreglo = $modelTecica->buscarNomtecnica($limit, $start);


        echo json_encode(array('cant' => count($arreglo), 'datos' => $arreglo));


    }

    function mostrardatostecnicaAction()
    {
        $idtecnica = $this->_request->getPost('iddtecnica');

        $modelTecica = new DattecnicaModel();
        $arreglo = $modelTecica->buscardatosTecnica($idtecnica);
        echo json_encode(array('success' => 'true', 'cant' => count($arreglo), 'datos' => $arreglo));

    }

    function insertartecnicaAction()
    {


        $idcargo = $this->_request->getPost('idcargo');
        $idtecnica = $this->_request->getPost('idtecnica');
        $ctp = $this->_request->getPost('cantidad');
        $ctg = $this->_request->getPost('ctg');
        $modelTecica = new DattecnicaModel();
        if ($modelTecica->existeDatTecnica($idtecnica, $idcargo)) {
            echo("{'codMsg':3,'mensaje': 'T&eacute;cnica insertada anteriormente.'}");
            return;
        }
        if ($arreglo = $modelTecica->insertarDatTecnica($idcargo, $idtecnica, $ctp, $ctg)) {
            echo("{'codMsg':1,'mensaje': 'Insertado correctamente.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error insertando.'}");
    }

    function eliminartecnicaAction()
    {


        $idcargo = $this->_request->getPost('idcargo');
        $idtecnica = $this->_request->getPost('idtecnica');

        $modelTecica = new DattecnicaModel();
        if ($arreglo = $modelTecica->eliminarTecnica($idcargo, $idtecnica)) {
            echo("{'success':true,'codMsg':1,'mensaje': 'Eliminado correctamente.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error eliminando.'}");

    }

    function modificartecnicaAction()
    {

        $idcargo = $this->_request->getPost('idcargo');
        $idtecnica = $this->_request->getPost('iddtecnica');
        $cantidad = $this->_request->getPost('cantidad');
        $ctg = $this->_request->getPost('ctg');
        $modelTecica = new DattecnicaModel();

        if ($arreglo = $modelTecica->modificartecnica($idtecnica, $cantidad, $ctg)) {
            echo("{'codMsg':1,'mensaje': 'Modificado correctamente.'}");
        } else
            echo("{'codMsg':3,'mensaje': 'Error modificando.'}");
    }


    /**
     * funcion auxiliar para validar dos fechas
     *
     * @param date $fechainicio
     * @param date $fechafin
     */
    function comprobarFecha($fechainicio, $fechafin)
    {
        $fi = str_replace("/", '', $fechainicio);
        $ff = str_replace("/", '', $fechafin);

        $fi = str_split($fi, 2);
        $ff = str_split($ff, 2);
        $fi = $fi[2] . $fi[3] . $fi[1] . $fi[0];
        $ff = $ff[2] . $ff[3] . $ff[1] . $ff[0];
        if ($fi > $ff)
            echo("{'codMsg':3,'mensaje': ' La fecha de inicio debe ser menor que la de fin.'}");


    }

    function geticonAction()
    {
        header('Content-type: image/gif');
        $module_path = Zend_Registry::getInstance()->config->modulo_path;
        $img = imagecreatefromgif($module_path . 'comun/recursos/iconos/' . $_GET['icon'] . '.gif');
        imagegif($img);
    }

    //--------------------------Nomenclador escala salarial------------


    function mostrarescalasalarialAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelescala = new NomescalasalarialModel();
        $total = $modelescala->countNomescalasalarial();
        $tablas = $modelescala->buscarNomescalasalarial($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tablas);


        echo(json_encode($mostrar));


    }

    //-------------------------Nomenclador grupo de complejidad----------------
    function mostrargrupocomplejidadAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 1000;
        $start = ($start != 0) ? $start : 0;

        $modelgrupocomple = new NomgrupocompleModel();
        $total = $modelgrupocomple->contNomgrupocomple();
        $tablas = $modelgrupocomple->buscarNomgrupocomple($limit, $start);

        $mostrar = array('cant' => $total, 'datos' => $tablas);

        echo(json_encode($mostrar));


    }

    //----------------Nomenclador salario--------------

    function mostrarsalarioAction()
    {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');

        $limit = ($limit != 0) ? $limit : 10000;
        $start = ($start != 0) ? $start : 0;

        $modelsalario = new NomsalarioModel();
        $total = $modelsalario->contNomsalario();
        $tabla = $modelsalario->buscarNomsalario($limit, $start);
        $mostrar = array('cant' => $total, 'datos' => $tabla);

        echo(json_encode($mostrar));

    }

    function hijosdpaAction()
    {


        $idpadre = $this->_request->getPost('node');
        //$idpadre		=	($idpadre =='iddpa' ) ? 3: $idpadre;	
        $obj = new ZendExt_Nomencladores_ADT();
        /*$mostrar = $obj->getTree("nom_dpa",$idpadre,1);
			$arreglo =$mostrar->Childrens(); 
        	if (count($arreglo)==0)
        	{
        		echo json_encode(array());
	        }
       	 	else
        	{
        		for($i=0 ;$i<count($arreglo);$i++)
        		{ 
		 			$hijo[] = array('id'=>$arreglo[$i]->_id,'text'=>$arreglo[$i]->_values['denominacion']);	
				}
      			echo json_encode($hijo);
			}*/
        if ($idpadre == 'iddpa') {
            $arreglo = $obj->getForest("nom_dpa"); //print_r($arreglo);die('aa');
            for ($i = 0; $i < count($arreglo); $i++) {
                //$limit 			=	( $limit != 0 ) ? $limit : 10 ;

                $leaf = ($arreglo[$i]->_values['idtipodpa'] == 3) ? 0 : 1;
                $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denominacion'], 'leaf' => 0);
            }
            echo json_encode($hijo);
        } else {
            $mostrar = $obj->getTree("nom_dpa", $idpadre, 1);
            $arreglo = $mostrar->Childrens(); // print_r($arreglo);die('aa');
            if (count($arreglo) == 0) {
                echo json_encode(array());
            } else {
                for ($i = 0; $i < count($arreglo); $i++) {
                    $leaf = ($arreglo[$i]->_values['idtipodpa'] == 3) ? 1 : 0;
                    $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denominacion'], 'leaf' => 1);
                }
                echo json_encode($hijo);
            }
        }

    }

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
                $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denespecialidad'], 'hoja' => 1);
            }
            echo json_encode($hijo);


        } else {
            $mostrar = $obj->getTree("nom_especialidad", $idesp);
            $arreglo = $mostrar->Childrens();

            if (count($arreglo) == 0)
                echo json_encode(array());
            else {
                for ($i = 0; $i < count($arreglo); $i++) {
                    $hijo[] = array('id' => $arreglo[$i]->_id, 'text' => $arreglo[$i]->_values['denespecialidad'], 'hoja' => 1);
                }
                echo json_encode($hijo);
            }


        }


    }
}


?>
