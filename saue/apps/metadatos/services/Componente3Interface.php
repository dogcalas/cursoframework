<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author pwilson
 */
interface componente33Interface
{

    /**
     * Devuelve un arreglo con los datos de la estructura
     * si en $idEstructura tiene el valor de "Estructuras"
     * devuelve los que son raices
     *
     * @param int $idEstructura
     * @return array
     */
    function DameEstructura($idEstructura);

    /**
     * Buscar todos los hijos de una estructura
     * el parametro es false entonce devuelve las raices
     *
     * @param int $idPadre
     * @return array
     */
    function DameChildEstructura($idPadre);
}

?>
