<?php

class NomAutenticacionModel extends ZendExt_Model {

    function insertartipoAutenticacion($tautenticacion) {
        $tautenticacion->save();
    }

    function modificartipoAutenticacion($tautenticacion) {
        $tautenticacion->save();
    }

    function eliminartipoAutenticacion($tautenticacion) {
        $tautenticacion->delete();
    }

    function verificartAutenticacion($denominacion, $abreviatura) {
        $autenticacion = NomAutenticacion::comprobarautenticacion($denominacion, $abreviatura);

        if ($autenticacion)
            return 1;
        else
            return 0;
    }
    
    function estaenuso($idautenticacion) {
        $aut = NomAutenticacion::buscarautenticacionporid($idautenticacion);
        return $aut->activo;
    }
    
     function ComprobarLibreria() {
        if (!extension_loaded('reconocimiento')) {

            return 0;
        }
        else
            return 1;
    }

}

