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

class GestroldominioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestroldominioAction() {
        $this->render();
    }

    function cargarRolDominioAction() {
        $model = new SegCompartimentacionrolesModel;
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $denominacion = $this->_request->getPost("denrolbuscado");
        $filtroDominio = $this->global->Perfil->iddominio;
        $arrayroles = array();
        if ($denominacion) {
            $result = $model->obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start);
            $cantrol = $model->cantrolBuscados($filtroDominio, $denominacion);
        } else {
            $result = $model->obtenerrol($filtroDominio, $limit, $start);
            $cantrol = $model->cantrol($filtroDominio);
        }
        if (count($result)) {
            foreach ($result as $key => $rol) {
                $arrayroles[$key]['idrol'] = $rol->idrol;
                $arrayroles[$key]['denominacion'] = $rol->denominacion;
                $arrayroles[$key]['abreviatura'] = $rol->abreviatura;
                $arrayroles[$key]['descripcion'] = $rol->descripcion;
            }
            $result = array('cantidad_filas' => $cantrol, 'datos' => $arrayroles);
            echo json_encode($result);
            return;
        }
        else
            $result = array('cantidad_filas' => $cantrol, 'datos' => $arrayroles);
        echo json_encode($result);
        return;
    }

    function cargarArbolDominiosAction() {
        $model = new SegCompartimentacionrolesModel;
        $iddominio = $this->_request->getPost('node');
        $idrol = $this->_request->getPost('idrol');
        $dominiosRoles = $model->cargardominioRoles($idrol);                
        $arrayDominio = $this->integrator->metadatos->cargarArbolDominios($iddominio);
        $result1 = $this->armarDominio($arrayDominio, $dominiosRoles);
        $result = $this->eliminarpos($result1);
        echo json_encode($result);
        return;
    }

    function armarDominio($arrayDominio, $dominiosRoles) {
        $arrayresult = array();
        foreach ($arrayDominio as $key => $dominio) {
            $arrayresult[] = $dominio;
            foreach ($dominiosRoles as $roldominio) {
                if ($dominio['id'] == $roldominio['iddominio']) {
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

    function insertarRolesDominiosAction() {
        $model = new SegCompartimentacionrolesModel();
        $idrol = $this->_request->getPost('idrol');        
        $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));
        $arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar'))); //array de nodos desmarcados sin desplegar
        $arrayDominios = json_decode(stripslashes($this->_request->getPost('arrayDominios'))); //estructura chekeadas
        $arrayDominiosEliminar = json_decode(stripslashes($this->_request->getPost('arrayDominiosEliminar'))); //estructuras a eliminar que son las que se deschekearon 
        $dominiosRol = $model->cargardominioRoles($idrol);        
        $arrayRolesDominio = $this->arregloBidimensionalToUnidimensional($dominiosRol);

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
        if (count($arrayRolesDominio))
            $arrayDominioIns = array_diff($arrayDominioIns, $arrayRolesDominio);
        if (count($arrayDominioIns)) {
            foreach ($arrayDominioIns as $dominiosIns) {
                $objRolesDominio = new SegCompartimentacionroles();
                $objRolesDominio->idrol = $idrol;
                $objRolesDominio->iddominio = $dominiosIns;
                $arrayIns[] = $objRolesDominio;
            }
        }
        if (count($arrayDominiosEliminar)) {
            foreach ($arrayDominiosEliminar as $dominiosElim) {
                $objRolesDominioE = new stdClass();
                $objRolesDominioE->idrol = $idrol;
                $objRolesDominioE->iddominio = $dominiosElim;
                $arrayElim[] = $objRolesDominioE;
            }
        }
        if (!count($arrayIns) && !count($arrayElim))
            echo "{'bien':3}"; //No se hace nada
        else {
            
            if ($model->insertarRolesDominio($arrayIns, $arrayElim))
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
