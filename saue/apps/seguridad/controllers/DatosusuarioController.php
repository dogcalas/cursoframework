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

class DatosusuarioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function datosusuarioAction() {
        $this->render();
    }

    function insertarcampoAction() {
        $campo = new NomCampo();
        $campo->tipo = $this->_request->getPost('tipo');
        $campo->nombre = $this->_request->getPost('nombre');
        $campo->nombreamostrar = $this->_request->getPost('alias');
        $campo->longitud = $this->_request->getPost('longitud');
        $campo->visible = $this->_request->getPost('visible');
        $campo->idexpresiones = $this->_request->getPost('idexpresiones');
        $campo->descripcion = $this->_request->getPost('descripcion');
        $campo->tipocampo = $this->_request->getPost('tipocampo');
        if ($this->verificadatosusuario($campo->nombre, ''))
            throw new ZendExt_Exception('SEG041');
        if ($this->verificadatosusuario('', $campo->nombreamostrar))
            throw new ZendExt_Exception('SEG042');
        $model = new NomCampoModel();
        $model->insertarcampo($campo);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInformacionAdd}");        
    }

    function modificarcampoAction() {
        $idcampo = $this->_request->getPost('idcampo');
        $tipo = $this->_request->getPost('tipo');
        $nombre = $this->_request->getPost('nombre');
        $nombreamostrar = $this->_request->getPost('alias');
        $longitud = $this->_request->getPost('longitud');
        $visible = $this->_request->getPost('visible');
        $idexpresiones = $this->_request->getPost('idexpresiones');
        $descripcion = $this->_request->getPost('descripcion');
        $tipocampo = $this->_request->getPost('tipocampo');
        $datos_mod = Doctrine::getTable('NomCampo')->find($idcampo);
        if ($datos_mod->nombre != $nombre) {
            if ($this->verificadatosusuario($nombre, ''))
                throw new ZendExt_Exception('SEG041');
        }
        if ($datos_mod->nombreamostrar != $nombreamostrar) {
            if ($this->verificadatosusuario('', $nombreamostrar))
                throw new ZendExt_Exception('SEG042');
        }
        $datos_mod->tipo = $tipo;
        $datos_mod->nombre = $nombre;
        $datos_mod->nombreamostrar = $nombreamostrar;
        $datos_mod->longitud = $longitud;
        $datos_mod->visible = $visible;
        $datos_mod->idexpresiones = $idexpresiones;
        $datos_mod->descripcion = $descripcion;
        $datos_mod->tipocampo = $tipocampo;
        $model = new NomCampoModel();
        $model->modificarcampo($datos_mod);
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInformacionMod}");
        
    }

    function verificadatosusuario($nombre, $nombreamostrar) {
        $datosusuario = NomCampo::comprobardatosusuario($nombre, $nombreamostrar);
        if ($datosusuario)
            return 1;
        else
            return 0;
    }

    function eliminarcampoAction() {
        $campo = new NomCampo();
        $campo = Doctrine::getTable('NomCampo')->find($this->_request->getPost('idcampo'));
        $model = new NomCampoModel();
        $filas = $this->EliminarFila($campo->idcampo);
        $model->eliminarcampo($campo);
        foreach ($filas as $f)
            $f->delete();
        echo("{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgInformacionElim}");       
    }

    function EliminarFila($campo) {
        $valoresXCampo = Doctrine::getTable('NomValor')->findByDql('idcampo=?', array($campo));

        $idfilas = array();
        foreach ($valoresXCampo as $VC) {
            $cant = NomValor::cantvaloresdadofila($VC->idfila);

            if ($cant == 1) {
                $idfilas[] = $VC->idfila;
            }
        }
        $filas = array();
        foreach ($idfilas as $idf) {
            $filas[] = Doctrine::getTable('NomFila')->find($idf);
        }
        return $filas;
    }

    function cargarcamposAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosacc = NomCampo::cargarcampo($limit, $start);
        $filas = NomCampo::obtenercantcampos();
        $campos = array();
        foreach ($datosacc as $index => $user) {
            $campos[$index]['idcampo'] = $user->idcampo;
            $campos[$index]['tipo'] = $user->tipo;
            $campos[$index]['nombre'] = $user->nombre;
            $campos[$index]['nombreamostrar'] = $user->nombreamostrar;
            $campos[$index]['longitud'] = $user->longitud;
            $campos[$index]['visible'] = $user->visible;
            $campos[$index]['idexpresiones'] = $user->idexpresiones;
            $campos[$index]['tipocampo'] = $user->tipocampo;
            $campos[$index]['descripcion'] = $user->descripcion;
            $campos[$index]['expresion'] = $user->NomExpresiones->expresion;
            $campos[$index]['denominacion'] = $user->NomExpresiones->denominacion;
        }
        $result = array('cantidad de filas' => $filas, 'datos' => $campos);
        echo json_encode($result);
        return;
    }

}
?>  


