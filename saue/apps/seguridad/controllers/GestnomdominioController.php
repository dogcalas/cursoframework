<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class GestnomdominioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomdominioAction() {
        $this->render();
    }

    public function cargardominiosAction() {
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $dendominio = $this->_request->getPost('dendominio');
        if ($dendominio)
            $arraydominios = $this->integrator->metadatos->BuscarDomioDadoNombre($limit, $start, $dendominio);
        else
            $arraydominios = $this->integrator->metadatos->BuscarDomio($limit, $start);

        echo json_encode($arraydominios);
    }

    function insertarnomdominioAction() {
        $denominacion = $this->_request->getPost('denominacion');
        $descripcion = $this->_request->getPost('descripcion');
        $arrayEstructuras = json_decode(stripslashes($this->_request->getPost('arrayEntidades')));
        $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));
        $arrayEstructuras = array('estructuras' => $arrayEstructuras, 'padres' => $arrayPadres);
        $estaDenominacion = $this->integrator->metadatos->verificarNombreDominio($denominacion);

        if ($estaDenominacion) {
            echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgAddDomError}";
        } else {
            if ($idDominio = $this->integrator->metadatos->InsertarDominio($denominacion, $descripcion, $arrayEstructuras)) {

                echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddDom}";
            }
            else
                echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgAddDomError2}";
        }
    }

    function arregloBidimensionalToUnidimensional($arrayEstructuras) {
        $array = array();
        foreach ($arrayEstructuras as $est)
            $array[] = $est['idestructura'];
        return $array;
    }

    function obtenerPos($array, $id) {
        $cant = count($array);
        for ($i = 0; $i < $cant; $i++)
            if ($this->obtenerUltimoIdCadena($array[$i]) == $id)
                return $i;
        return -1;
    }

    function obtenerUltimoIdCadena($cadena) {
        $array = explode('-', $cadena);
        return substr($array[count($array) - 1], 0, strlen($array[count($array) - 1]) - 2);
    }

    function agregarChekeados($arreglo, $arreglo1, $id, $cadenaPadre = '') {
        if ($cadenaPadre == '') {
            $pos = $this->obtenerPos($arreglo, $id);
            $cadenaPadre = $arreglo[$pos];
        }
        if (substr($cadenaPadre, -1) == 'e') {
            $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
            $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id, true);
            $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);
        }
        else
            $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);
        foreach ($arrayEstructuras as $valor) {
            $cad1 = ($valor['tipo'] == 'externa') ? $cadenaPadre . '-' . $valor['id'] . '_e' : $cadenaPadre . '-' . $valor['id'] . '_i';
            $arreglo[] = $cad1;
            $arreglo1[] = $valor['id'];
            $this->agregarChekeados($arreglo, $arreglo1, $valor['id'], $cad1);
        }
    }

    function agregaraArrayEliminar($arreglo, $id, $tipo = '') {
        if ($tipo == 'externa') {
            $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
            $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id, true);
            $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);
        } else {
            $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);
        }
        foreach ($arrayEstructuras as $valor) {
            $arreglo[] = $valor['id'];
            $this->agregaraArrayEliminar($arreglo, $valor['id'], $valor['tipo']);
        }
    }

    function modificarnomdominioAction() {
        $denominacion = $this->_request->getPost('denominacion');
        $descripcion = $this->_request->getPost('descripcion');
        $iddominio = $this->_request->getPost('iddominio');
        $arrayEstructuras = json_decode(stripslashes($this->_request->getPost('arrayEntidades')));
        $arrayEstructurasEliminar = json_decode(stripslashes($this->_request->getPost('arrayEntidadesEliminar')));
        $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));
        $arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar')));
        
        $estedominio = $this->integrator->metadatos->DatosDominioDadoID($iddominio);        

        if ($estedominio[0]['denominacion'] !== $denominacion) {
            $estaDenominacion = $this->integrator->metadatos->verificarNombreDominio($denominacion);            
            if ($estaDenominacion && ($iddominio == $estedominio[0]['iddominio'])) {
                echo"{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgModDomError}";
            } else {
                $returnedValue = $this->integrator->metadatos->ModificarDominio($iddominio, $denominacion, $descripcion, array('estructuras' => $arrayEstructuras, 'padres' => $arrayPadres, 'arrayEntidadesEliminar' => $arrayEstructurasEliminar, 'arrayPadresEliminar' => $arrayPadresEliminar));
                if ($returnedValue) {
                    echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgModDom}";
                }
            }
        } else {
            if (count($arrayEstructuras)) {
                $this->integrator->metadatos->ModificarDominio($iddominio, $denominacion, $descripcion, array('estructuras' => $arrayEstructuras, 'padres' => $arrayPadres, 'arrayEntidadesEliminar' => $arrayEstructurasEliminar, 'arrayPadresEliminar' => $arrayPadresEliminar));
                echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModDom}";
                
            } else {
                echo "{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgSelecEntidad}";
            }
        }
    }

    function eliminarnomdominioAction() {
        $model = new SegDominioModel();
        $iddominio = $this->_request->getPost('iddominio');
        $cantusuariodominio = $model->cantusuariodadodominio($iddominio);
        if ($cantusuariodominio > 0) {
            echo "{'codMsg':3,'mensaje':perfil.etiquetas.lbMsgDeleDomError2}";
            return false;
        } else {
            $this->integrator->metadatos->EliminarDominio($this->_request->getPost('iddominio'));
            echo "{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDeleSatis}";
        }
    }

    function cargarestructurasAction() {
        $idEstructura = $this->_request->getPost('node');
        $accion = $this->_request->getPost('accion');
        $iddominiomod = $this->_request->getPost('iddominio');
        if ($accion == 'insertar') {
            $estructuras = $this->integrator->metadatos->buscarHijosEstructuras(0, $idEstructura, 1, 0, 0);
        } else {
            $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructuras($iddominiomod, $idEstructura, 1, 1, 0);
            foreach ($arrayEstructuras as $key => $est) {
                $estructuras[$key] = $est;
                if ($est['unchecked'])
                    unset($estructuras[$key]['checked']);
            }
        }
        echo json_encode($estructuras);
    }

    function obtenerTodasEntidadesCadena($id) {
        $model = new SegDominioModel();
        $resultado = array();
        $arrayEntidades = $model->obtenerCadenaEntidades($id);
        foreach ($arrayEntidades as $valor) {
            $temp = explode("-", $valor);
            foreach ($temp as $valor1) {
                if (!$this->existeValorArray($resultado, $valor1))
                    $resultado[] = $valor1;
            }
        }
        return $resultado;
    }

    function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad) {
        return $this->buscarIdCargar($arrayEntidades, $idEntidad);
    }

    function obterEntidadesPonerChecBox($array) {
        $resultado = array();
        foreach ($array as $valor) {
            $temp = explode("-", $valor);
            $resultado[] = substr($temp[count($temp) - 1], 0, strlen($temp[count($temp) - 1]) - 2);
        }
        return $resultado;
    }

    function ponerCheck($arrayEntidades, $arrayIdEntidadesUltimas) {
        $cant = count($arrayEntidades);

        for ($i = 0; $i < $cant; $i++) {
            foreach ($arrayIdEntidadesUltimas as $valor) {
                if ($arrayEntidades[$i]['idestructura'] == $valor) {
                    $arrayEntidades[$i]['checked'] = false;
                    break;
                }
            }
        }
        return $arrayEntidades;
    }

    function buscarIdCargar($array, $idEntidad = 0) {
        $resultado = array();
        if (!$idEntidad) {
            foreach ($array as $valor) {
                $temp = explode("-", $valor);
                if (!$this->existeValorArray($resultado, substr($temp[0], 0, strlen($temp[0]) - 2)))
                    $resultado[] = substr($temp[0], 0, strlen($temp[0]) - 2);
            }
        }
        else {
            foreach ($array as $valor) {
                $temp = explode("-", $valor);
                $res = $this->existeValorArrayNoUltimo($temp, $idEntidad);
                if ($res != -1) {
                    if (!$this->existeValorArray($resultado, substr($res, 0, strlen($res) - 2)))
                        $resultado[] = substr($res, 0, strlen($res) - 2);
                }
            }
        }
        return $resultado;
    }

    function existeValorArray($array, $valor) {
        foreach ($array as $valor1)
            if ($valor1 == $valor)
                return true;
        return false;
    }

    function existeValorArrayConTipo($array, $valor) {
        foreach ($array as $valor1)
            if (substr($valor1, 0, strlen($valor1) - 2) == $valor)
                return true;
        return false;
    }

    function existeValorArrayNoUltimo($array, $valor) {
        for ($i = 0; $i < count($array) - 1; $i++)
            if (substr($array[$i], 0, strlen($array[$i]) - 2) == $valor)
                return $array[$i + 1];
        return -1;
    }

    function arrayUltimasEntidades($array) {
        $cantidad = count($array);
        $externa = explode('_', $array[$cantidad - 1]);
        if ($externa[1] == 'e')
            return $externa[0];
        else
            return 0;
    }

    function estaCheckEnBd($arrayEst, $idEstructura) {
        $cant = count($arrayEst);
        for ($i = 0; $i < $cant; $i++) {
            if ($arrayEst[$i] == $idEstructura)
                return true;
        }
        return false;
    }

    function marcarEntidades($arrayEstructuras, $arrayEst) {
        $cant = count($arrayEstructuras);
        $cant1 = count($arrayEst);
        for ($i = 0; $i < $cant; $i++) {
            for ($j = 0; $j < $cant1; $j++) {
                if ($arrayEstructuras[$i]['id'] == $arrayEst[$j]) {
                    $arrayEstructuras[$i]['checked'] = true;
                    break;
                }
            }
        }
        return $arrayEstructuras;
    }

    function gestionarMarcadoEntidades($arrayEstructuras, $arrayEst, $idEstructura, $tcheck) {
        $cant = count($arrayEstructuras);

        if ($tcheck == 'marcado') {
            if (!$this->estaCheckEnBd($arrayEst, $idEstructura)) {
                for ($i = 0; $i < $cant; $i++) {
                    if ($arrayEstructuras[$i]['checked'])
                        $arrayEstructuras[$i]['checked'] = true;
                }
            }
            else
                $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        }
        else if ($tcheck == 'desmarcado') {
            if ($this->estaCheckEnBd($arrayEst, $idEstructura)) {
                for ($i = 0; $i < $cant; $i++)
                    if ($arrayEstructuras[$i]['checked'])
                        $arrayEstructuras[$i]['checked'] = false;
            }
            else
                $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        }
        else
            $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
        return $arrayEstructuras;
    }

    function ponerHojasEntidades($arrayEstructuras) {
        $cant = count($arrayEstructuras);
        for ($i = 0; $i < $cant; $i++)
            $arrayEstructuras[$i]['leaf'] = true;
        return $arrayEstructuras;
    }

    function ponerComoHoja($array) {
        $n = count($array);
        for ($i = 0; $i < $n; $i++) {
            if ($array[$i]['rgt'] - $array[$i]['lft'] == 1) {
                $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'], true);
                if (!count($arrayEstructuras1))
                    $array[$i]['leaf'] = true;
            }
        }
        return $array;
    }

}

?>
