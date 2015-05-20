<?php

$s = ($_POST['SUFIX']) ? $_POST['SUFIX'] : (($_GET['SUFIX']) ? $_GET['SUFIX'] : '');
define('SUFIX', $s);
define('DEBUG_ERP', true);


class EstructurasbController extends ZendExt_Controller_Secure
{

    var $consecutivo;

    function init()
    {
        parent::init();
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

        $modelEst = new EstructurasbModel();
        $estructuras = $modelEst->getEstructurasInternasServicio($idop, false);


        //$mo	= new CampoModel();
        echo '<pre>';
        print_r($estructuras);
        return;


    }

    function buscarhijosAction()
    {
        $idestructura = $this->_request->getPost('node');
        $idest = ($idestructura != '') ? ($idestructura == 'Estructuras') ? false : $idestructura : die("{'codMsg':3,'mensaje': 'Id tabla no enviado'}");
        $modelEstructura = new EstructurasbModel();

        echo(json_encode($modelEstructura->getHijos($idest)));
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

        /*if( $idop == $idestructura)
        {
            $estructuras		= array();
            if( $idestructura != 'Estructuras' )
            {
                $modelEst		= new EstructurasbModel();
                $estructuras	= $modelEst->getEstructurasInternas( $idestructura , true);
            }
            echo json_encode($estructuras);
        }
        else */
        {
            //-- si el id op es diferentede estructuras
            if ($idop != 'Estructuras' && $idop != 'Composicion') {
                $modelCargos = new DatcargoModel();
                $cargos = $modelCargos->buscarCargoLineal(1000, 0, $idop);
                $modelEst = new EstructuraopsbModel();
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


    function geticonAction()
    {
        header('Content-type: image/gif');
        $module_path = Zend_Registry::getInstance()->config->modulo_path;
        $img = imagecreatefromgif($module_path . 'comun/recursos/iconos/' . $_GET['icon'] . '.gif');
        imagegif($img);
    }

    //--------------------------Nomenclador escala salarial------------

    function reordenarAction()
    {
        $modelEst = new EstructurasbModel();

        $modelEst->consecutivo = 1;
        echo '<pre>';
        $modelEst->reordenarHijos(false);
    }

    function cambiarsubordinacionAction()
    {
        $idestructura = $this->_request->getPost('idDesde');
        $idnuevo = $this->_request->getPost('idHasta');
        $tipo = $this->_request->getPost('idnomsubordinacion');

        if ($idestructura == $idnuevo) {
            return;
        }
        $modelSubordinacion = new DatSubordinacionModel();
        if ($this->_request->getPost('excluir') == 1) // si se excluye
        {
            $modelSubordinacion->eliminarsubordinacion($idestructura);
        }


        $modelSubordinacion->insertarSubordinacion($idestructura, $idnuevo, $tipo);
    }

    function mostarnivelsubordinacionAction()
    {
        $modelSubordinacion = new NomSubordinacionModel();
        $datos = $modelSubordinacion->buscarNomSubordinacionSB(1000);
        echo json_encode(array('cant' => count($datos), 'datos' => $datos));

    }

    function pruebasAction()
    {
        print_r(EstructurasbModel::getPadre(900000094, 2));

    }

}


?>
