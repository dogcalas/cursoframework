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

class DatFuncionalidadModel extends ZendExt_Model {

    public function DatFuncionalidadModel() {
        parent::ZendExt_Model();
    }

    function insertarfuncionalidad($instance) {
        $instance->save();
        return $instance->idfuncionalidad;
    }

    function modificarfuncionalidad($instance) {
        $instance->save();
    }

    function eliminarfuncionalidad($instance) {
        $instance->delete();
    }

    function obtenerFuncionalidadesSistema($idsistema) {
        $array = DatFuncionalidad::obtenerFuncionalidadesSistema($idsistema);
        return $array;
    }

    function cargarfuncionalidades($idsistema, $limit, $start) {
        $array = DatFuncionalidad::cargarfuncionalidades($idsistema, $limit, $start);
        return $array;
    }

    function buscarfuncionalidades($idsistema, $denominacion, $limit, $start) {
        $datosfunc = DatFuncionalidad::buscarfuncionalidades($idsistema, $denominacion, $limit, $start);
        return $datosfunc;
    }

    function obtenercantfuncdenominacion($idsistema, $denominacion) {
        $cantf = DatFuncionalidad::obtenercantfuncdenominacion($idsistema, $denominacion);
        return $cantf;
    }

    function buscarfuncionalidadesgrid($idsistema, $limit, $start) {
        $datosfunc = DatFuncionalidad::buscarfuncionalidadesgrid($idsistema, $limit, $start);
        return $datosfunc;
    }

    function obtenercantfunc($idsistema) {
        $cantf = DatFuncionalidad::obtenercantfunc($idsistema);
        return $cantf;
    }
    
    public function ExisteRuta($referencia) {
        $rutabase = ereg_replace('web', "apps", $_SERVER['DOCUMENT_ROOT']);
        $dir_appa = explode('/index.php', $referencia);
        $name_controller = explode('/', $dir_appa[1]);
        if (count($name_controller) != 3)
            return false;       
        $ruta = $rutabase . '/' . (string) $dir_appa[0] . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . ucfirst(strtolower($name_controller[1])) . 'Controller.php';
        $valor = file_exists($ruta);
        if (!$valor)
            return false;
        return true;
    }

    public function inactivasPorFecha($idrol){
        $inactivas = $this->integrator->calendar->cargarActivasPorFecha($idrol);
        $arr= array();
        foreach($inactivas as $inac){
            $arr[]=$inac['idfuncionalidad'];
        }

        return $arr;
    }

}

?>
