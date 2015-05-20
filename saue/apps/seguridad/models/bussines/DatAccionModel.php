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

class DatAccionModel extends ZendExt_Model {

    public function DatAccionModel() {
        parent::ZendExt_Model();
    }

    public function insertaraccion($accion) {
        $accion->save();
        return $accion->idaccion;
    }

    public function modificaraccion($instance) {
        $instance->save();
        return true;
    }

    public function eliminaraccion($instance) {
        $instance->delete();
        return true;
    }

    public function obtenerAccionesFuncionalidad($idfuncionalidad) {
        $array = DatAccion::obtenerAccionesFuncionalidad($idfuncionalidad);
        return $array;
    }

    public function buscaraccion($idfuncionalidad, $denominacion, $limit, $start) {
        $array = DatAccion::buscaraccion($idfuncionalidad, $denominacion, $limit, $start);
        return $array;
    }

    public function obtenercantaccionbuscadas($idfuncionalidad, $denominacion) {
        $array = DatAccion::obtenercantaccionbuscadas($idfuncionalidad, $denominacion);
        return $array;
    }

    public function cargaraccion($idfuncionalidad, $limit, $start) {
        $array = DatAccion::cargaraccion($idfuncionalidad, $limit, $start);
        return $array;
    }

    public function obtenercantaccion($idfuncionalidad) {
        $array = DatAccion::obtenercantaccion($idfuncionalidad);
        return $array;
    }

    public function accesoAccion($rol, $denominacion) {
        $array = DatAccion::accesoAccion($rol, $denominacion);
        return $array;
    }

    public function activasPorFecha($idrol){
        $inactivas = $this->integrator->calendar->cargarActivasPorFecha($idrol);
        $arr= array();
        foreach($inactivas as $inac){
            $arr[]=$inac['idaccion'];
        }

        return $arr;
    }

}

?>