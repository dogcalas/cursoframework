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

class GestperfilController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestperfilAction() {
        $this->render();
    }

    public function configurargridAction() {
        $campos = NomCampo::cargarcampo(0, 0);
        $columna[] = array('hidden' => true, 'hideable' => false, 'dataIndex' => 'idusuario');
        $columna[] = array('hidden' => true, 'hideable' => false, 'dataIndex' => 'idfila');
        $columna[] = array('id' => 'expandir', 'header' => 'Usuario', 'width' => 200, 'dataIndex' => 'nombreusuario');
        $arregloResult = $campos->toArray(true);
        $campoStore[] = array('name' => 'idusuario', 'mapping' => 'idusuario');
        $campoStore[] = array('name' => 'idfila', 'mapping' => 'idfila');
        $campoStore[] = array('name' => 'nombreusuario', 'mapping' => 'nombreusuario');
        foreach ($arregloResult as $campo) {
            $campoStore[] = array('name' => $campo['nombre'], 'mapping' => $campo['nombre']);
            if ($campo['visible'])
                $columna[] = array('header' => $campo['nombreamostrar'], 'width' => 100, 'dataIndex' => $campo['nombre']);
            else
                $columna[] = array('hidden' => true, 'header' => $campo['nombreamostrar'], 'width' => 100, 'dataIndex' => $campo['nombre']);
        }
        $parteStore = array('store' => $campoStore);
        $parteColumna = array('columns' => $columna);
        echo json_encode(array($parteColumna, $parteStore));
        return;
    }

    public function cargargridAction() {
        $nombreusuario = $this->_request->getPost('nombreusuario');
        $iddominio = $this->global->Perfil->iddominio;
        $idusuario = $this->global->Perfil->idusuario;
        $arrayresult = array();
        $datosusuario = array();
        $usuariosconpermisosadominios = array();
        $permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        $filtroDominio = $this->arregloToUnidimensional($permisos);
        if (count($filtroDominio))
            $usuariosconpermisosadominios = SegUsuario::cargarUsuariosconpermisosaDominios($filtroDominio);
        $usuariosconpermisosadominios = $this->arregloToUnidimensionalUsuario($usuariosconpermisosadominios);
        $usuariosdelDominio = SegUsuarioNomDominio::cargarUsuariosDominios($iddominio);
        $usuariosdelDominio = $this->arregloToUnidimensionalUsuario($usuariosdelDominio);
        $arrayresult = array_merge($usuariosconpermisosadominios, $usuariosdelDominio);
        if ($nombreusuario)
            $campos = NomCampo::usuarioDominioBuscado($nombreusuario, $arrayresult);
        else
            $campos = NomCampo::gridUsuarioDominio($arrayresult);
        $result = array();
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        foreach ($campos as $key => $valor) {
            $indice = $this->existeYa($result, $valor['idusuario']);
            if ($indice === false) {
                $result[$key]['idusuario'] = $valor['idusuario'];
                $result[$key]['nombreusuario'] = $valor['nombreusuario'];
                if (isset($valor['NomFila']) && $valor['NomFila']['idfila']) {
                    $result[$key]['idfila'] = $valor['NomFila']['idfila'];
                    $valores = $valor['NomFila']['NomValor'];
                    foreach ($valores as $campo) {
                        $arrayvalores = NomValor::cargarcamposdadovalores($campo['idvalor']);
                        $result[$key][$arrayvalores[0]['NomCampo']['nombre']] = $campo['valor'];
                    }
                }
            }
            else
                $result[$indice][$valor['nombre']] = $valor['valor'];
        }
        $cantFila = count($result);
        echo json_encode(array('cant_fila' => $cantFila, 'datos' => $this->paginarResultado($result, $limit, $start)));
        return;
    }

    function arregloToUnidimensionalUsuario($arrayvalores) {
        $array = array();
        foreach ($arrayvalores as $idusuario)
            $array[] = $idusuario['idusuario'];
        return $array;
    }

    function arregloToUnidimensional($arrayDominios) {
        $array = array();
        foreach ($arrayDominios as $dominios)
            $array[] = $dominios['iddominio'];
        return $array;
    }

    public function existeYa($result, $idusuario) {
        $k = 0;
        foreach ($result as $valor) {
            if ($valor['idusuario'] == $idusuario)
                return $k;
            $k++;
        }
        return false;
    }

    public function paginarResultado($result, $limit, $start) {
        $resultado = array();
        $cantFila = count($result);
        $k = 0;
        for ($i = $start; $i < $start + $limit; $i++) {
            if ($i >= $cantFila)
                break;
            else
                $resultado[$k] = $result[$i];
            $k++;
        }
        return $resultado;
    }

    public function cargarcamposAction() {
        $campos = NomCampo::cargarcampo(0, 0);
        $camposaux = $campos->toArray(true);
        $arrayCampos = array();       
        foreach ($camposaux as $key => $aux) {
            $arrayCampos[$key]['idcampo'] = $aux['idcampo'];
            $arrayCampos[$key]['tipo'] = $aux['tipo'];
            $arrayCampos[$key]['nombre'] = $aux['nombre'];
            $arrayCampos[$key]['nombreamostrar'] = $aux['nombreamostrar'];
            $arrayCampos[$key]['longitud'] = $aux['longitud'];
            $arrayCampos[$key]['visible'] = $aux['visible'];
            $arrayCampos[$key]['descripcion'] = $aux['descripcion'];
            $arrayCampos[$key]['tipocampo'] = $aux['tipocampo'];
            $arrayCampos[$key]['idexpresiones'] = $aux['idexpresiones'];
            $arrayCampos[$key]['NomExpresiones']['expresion'] = utf8_encode($aux['NomExpresiones']['expresion']);
            $arrayCampos[$key]['NomExpresiones']['idexpresiones'] = $aux['NomExpresiones']['idexpresiones'];
            $arrayCampos[$key]['NomExpresiones']['descripcion'] = $aux['NomExpresiones']['descripcion'];
            $arrayCampos[$key]['NomExpresiones']['denominacion'] = $aux['NomExpresiones']['denominacion'];
            $arrayCampos[$key]['NomValor']['idvalor'] = $aux['NomValor']['idvalor'];
            $arrayCampos[$key]['NomValor']['idfila'] = $aux['NomValor']['idfila'];
            $arrayCampos[$key]['NomValor']['idcampo'] = $aux['NomValor']['idcampo'];
            $arrayCampos[$key]['NomValor']['valor'] = $aux['NomValor']['valor'];
        }
        $result = array('cantidad' => count($campos), 'campos' => $arrayCampos);
        echo json_encode($result);
        return;
    }

    public function insertarperfilAction() {
        $campos = NomCampo::cargarcampo(0, 0);
        $arrayCampos = $campos->toArray(true);
        $objFila = new NomFila();
        $objFila->idusuario = $this->_request->getPost('idusuario');
        $objFila->save();
        foreach ($arrayCampos as $campo) {
            $objValor = new NomValor();
            $objValor->valor = $this->_request->getPost($campo['nombre']);
            $objValor->idcampo = $campo['idcampo'];
            $objValor->idfila = $objFila->idfila;
            $result[] = $objValor;
        }
        $model = new SegUsuarioModel();
        $model->insertarperfil($result);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfAdicionar}");        
    }

    public function modificarperfilAction() {
        $campos = NomCampo::cargarcampo(0, 0);
        $idfila = $this->_request->getPost('idfila');
        $arrayCampos = $campos->toArray(true);
        $cantvaloresfila = NomValor::cantvaloresdadofila($idfila);
        $k = 0;
        $resultAdicionar = array();
        $resultModificar = array();
        $resultEliminar = array();
        foreach ($arrayCampos as $campo) {
            $idcampo = $campo['idcampo'];
            $valor = NomValor::cargaridvalor($idfila, $idcampo);
            $objNomValor = Doctrine::getTable('NomValor')->find($valor[0]['idvalor']);
            if ($objNomValor) {
                if ($this->_request->getPost($campo['nombre'])) {
                    $objNomValor->valor = $this->_request->getPost($campo['nombre']);
                    $resultModificar[] = $objNomValor;
                } else {
                    $cantvaloresfila--;
                    $resultEliminar[] = $objNomValor;
                }
                unset($objNomValor);
            } else {
                $objValor = new NomValor();
                $objValor->idvalor = $objValor->genId() + $k;
                $objValor->valor = $this->_request->getPost($campo['nombre']);
                $objValor->idcampo = $campo['idcampo'];
                $objValor->idfila = $idfila;
                $resultAdicionar[] = $objValor;
                $k++;
            }
        }
        $model = new SegUsuarioModel();
        if ($cantvaloresfila != 0)
            $model->modificarperfil($resultModificar, $resultEliminar, $resultAdicionar);
        else {
            NomFila::eliminarfila($idfila);
        }
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInfModificar}");      
    }

}

?>
