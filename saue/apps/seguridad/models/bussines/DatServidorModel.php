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

class DatServidorModel extends ZendExt_Model {

    public function DatServidorModel() {
        parent::ZendExt_Model();
    }

    function insertar($tiposerv) {
        $tiposerv->save();
    }

    function modificarservidor($servidor) {
        $servidor->save();
    }

    function modificarservidorsabd($servidor) {
        $servidor->save();
    }

    function eliminarservidor($instance) {
        $instance->delete();
    }

    function verificadatosservidor($denominacion) {
        $datosservidor = DatServidor::comprobardatosservidor($denominacion);
        if ($datosservidor)
            return 1;
        else
            return 0;
    }

    function verificarExisteIpConTipo($tipoServidor, $ip) {
        $datosservidor = DatServidor::comprobarExtisteIpTiposervidor($tipoServidor, $ip);
        if ($datosservidor)
            return 1;
        else
            return 0;
    }

    function createAccountDomainName($string) {
        $string = strtolower($string);
        $stringResult = '';
        $string = substr($string, strpos($string, 'dc'), strlen($string));
        $array = explode(',', $string);
        $cant = count($array);
        foreach ($array as $dc) {
            $result = explode('dc=', $dc);
            if ($cant == 1)
                $stringResult .= $result[1];
            else {
                $stringResult .= $result[1] . '.';
                $cant--;
            }
        }
        return $stringResult;
    }

    function accountDomainNameShort($acountDomainName) {
        $acountDomainName = explode('.', $acountDomainName);
        return $acountDomainName[0];
    }
    
    function obtenercantidad($servidor){
        $cantserv=DatSerautenticacion::obtenercantidad($servidor);
        return $cantserv;
    }
    
    function obtenercantservsistema($servidor){
        $cantservsist=DatSistemaDatServidores::obtenercantservsistema($servidor);
        return $cantservsist;
    }
    function elimirarServidores($arrayServ){
        DatServidor::elimirarServidores($arrayServ);
    }
            

}

?>