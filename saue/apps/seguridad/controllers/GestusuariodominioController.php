<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */

class GestusuariodominioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestusuariodominioAction() {
        $this->render();
    }

    function cargarusuariodominioAction() { 
        $model=new SegCompartimentacionusuarioModel;
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $nombreusuario = $this->_request->getPost('denrolbuscado');
        $filtroDominio = $this->global->Perfil->iddominio;
        if ($nombreusuario) {
            $datosusuario = $model->cargarusuario($filtroDominio, $nombreusuario, $limit, $start);
            $cantf = $model->obtenercantusuarios($filtroDominio, $nombreusuario);
            if ($cantf) {
                $arrayusuario = $this->datosGridUsuarios($datosusuario);
                $result = array('cantidad_filas' => $cantf, 'datos' => $arrayusuario);
                echo json_encode($result);
                return;
            } else {
                $datos = $datosusuario->toArray(true);
                $result = array('cantidad_filas' => $cantf, 'datos' => $datos);
                echo json_encode($result);
                return;
            }
        } else {
            $datosusuario = $model->cargarusuario($filtroDominio, '', $limit, $start);                    
            $cantf = $model->obtenercantusuarios($filtroDominio, '');                    
            if ($cantf) {
                $arrayusuario = $this->datosGridUsuarios($datosusuario);
                $result = array('cantidad_filas' => $cantf, 'datos' => $arrayusuario);
                echo json_encode($result);
                return;
            } else {
                $datos = $datosusuario->toArray(true);
                $result = array('cantidad_filas' => $cantf, 'datos' => $datos);
                echo json_encode($result);
                return;
            }
        }
    }

    function datosGridUsuarios($datosusuario) {
        foreach ($datosusuario as $key => $usuario) {
            $arrayusuario[$key]['idusuario'] = $usuario->idusuario;
            $arrayusuario[$key]['nombreusuario'] = $usuario->nombreusuario;
        }
        return $arrayusuario;
    }

    function cargarArbolDominiosAction() {
        $model=new SegCompartimentacionusuarioModel;
        $iddominio = $this->_request->getPost('node');
        $idusuario = $this->_request->getPost('idusuario');
        $dominiosUser =$model->cargardominioUsuario($idusuario);                
        $arrayDominio = $this->integrator->metadatos->cargarArbolDominios($iddominio);
        $result1 = $this->armarDominio($arrayDominio, $dominiosUser);
        $result = $this->eliminarpos($result1);
        echo json_encode($result);
        return;
    }

    function armarDominio($arrayDominio, $dominiosUser) {
        $arrayresult = array();
        foreach ($arrayDominio as $key => $dominio) {
            $arrayresult[] = $dominio;
            foreach ($dominiosUser as $userdominio) {
                if ($dominio['id'] == $userdominio['iddominio']) {
                    $arrayresult[$key]['checked'] = true;
                    break;
                }
            }
        }
        return $arrayresult;
    }

    function eliminarpos($array) {
        $result = array();
        foreach ($array as $valores) {
            if ($valores['id'] == $this->global->Perfil->iddominio) {
                unset($valores['checked']);
                $result[] = $valores;
            }
            if (!$this->existe($valores['id'], $result))
                $result[] = $valores;
        }
        return $result;
    }

    function existe($id, $array) {
        foreach ($array as $valor)
            if ($valor['id'] == $id)
                return true;
        return false;
    }

    function insertarUsuariosDominiosAction() {
        $model=new SegCompartimentacionusuarioModel;
        $idusuario = $this->_request->getPost('idusuario');
        $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));
        $arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar'))); //array de nodos desmarcados sin desplegar
        $arrayDominios = json_decode(stripslashes($this->_request->getPost('arrayDominios'))); //estructura chekeadas
        $arrayDominiosEliminar = json_decode(stripslashes($this->_request->getPost('arrayDominiosEliminar'))); //estructuras a eliminar que son las que se deschekearon 
        $dominiosUser = $model->cargardominioUsuario($idusuario);                
        $arrayUsuarioDominio = $this->arregloBidimensionalToUnidimensional($dominiosUser);        
        $arrayHijos = array();
        if (count($arrayPadres)) {
            $arrayHijos = $this->integrator->metadatos->buscarArbolHijosDominio($arrayPadres);
            $arrayHijos = $this->arregloBidimensionalToUnidimensional($arrayHijos);
        }
        $arrayHijosEliminar = array();
        if (count($arrayPadresEliminar)) {
            $arrayHijosEliminar = $this->integrator->metadatos->buscarArbolHijosDominio($arrayPadresEliminar);
            $arrayHijosEliminar = $this->arregloBidimensionalToUnidimensional($arrayHijosEliminar);
        }
        $arrayDominiosEliminar = array_merge($arrayDominiosEliminar, $arrayHijosEliminar);
        $arrayDominioIns = array_merge($arrayDominios, $arrayHijos);
        if (count($arrayUsuarioDominio))
            $arrayDominioIns = array_diff($arrayDominioIns, $arrayUsuarioDominio);
        if (count($arrayDominioIns)) {
            foreach ($arrayDominioIns as $dominiosIns) {
                $objUsuarioDominio = new SegCompartimentacionusuario();
                $objUsuarioDominio->idusuario = $idusuario;
                $objUsuarioDominio->iddominio = $dominiosIns;
                $arrayIns[] = $objUsuarioDominio;
            }
        }
        if (count($arrayDominiosEliminar)) {
            foreach ($arrayDominiosEliminar as $dominiosElim) {
                $objUsuarioDominioE = new stdClass();
                $objUsuarioDominioE->idusuario = $idusuario;
                $objUsuarioDominioE->iddominio = $dominiosElim;
                $arrayElim[] = $objUsuarioDominioE;
            }
        }
        if (!count($arrayIns) && !count($arrayElim))
            echo "{'bien':3}"; //No se hace nada
        else {            
            if ($model->insertarUsuarioDominio($arrayIns, $arrayElim))
                echo "{'bien':1}";
        }
    }

    function arregloBidimensionalToUnidimensional($arrayDominios) {
        $array = array();
        foreach ($arrayDominios as $dominios)
            $array[] = $dominios['iddominio'];
        return $array;
    }

}

?>
