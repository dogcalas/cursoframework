<?php

class CArbol
{

    var $hijos;

    function CArbol($hijos)
    {
        $this->hijos = $hijos;
    }

    function buscarRaices()
    {
        return $this->buscarHijos(false);
    }

    function buscarHijos($idPadre)
    {
        $arreglo = array();
        foreach ($this->hijos as $hijo) {
            if ($hijo->idPadre == $idPadre)
                $arreglo[] = $hijo;
        }
        return $arreglo;
    }

    function hijosDeprofundidad($profundidad)
    {
        // buscar las raices
        $arreglo = array();

        foreach ($this->hijos as $hijo) {
            if ($this->profundidadNodo($hijo->id) == $profundidad)
                $arreglo[] = $hijo;
        }
        return $arreglo;

    }

    function hijosDeprofundidadOrdenado($profundidad)
    {
        // buscar las raices
        $arreglo = array();

        foreach ($this->hijos as $hijo) {
            if ($this->profundidadNodo($hijo->id) == $profundidad) {
                $idp = $hijo->idPadre ? $hijo->idPadre : $hijo->id;
                $arreglo[$idp][$hijo->id] = $hijo;
                ksort($arreglo[$idp]);
            }
        }
        ksort($arreglo);
        return $arreglo;

    }

    function GetObject($idNodo)
    {
        foreach ($this->hijos as $hijo)
            if ($hijo->id == $idNodo)
                return $hijo;
        return false;
    }


    function profundidadNodo($idNodo)
    {
        $hijo = $this->GetObject($idNodo);
        if ($hijo == false)
            return false;

        $i = 1;
        while ($hijo->idPadre != false) {
            $hijo = $this->GetObject($hijo->idPadre);
            $i++;
        }
        return $i;
    }

    function maximoNodosNivel()
    {
        $prof = 1;
        $profMasNodos = 1;
        $cantNodosProf = 0;
        do {
            $nodos = $this->hijosDeprofundidad($prof);
            $cantNodos = count($nodos);
            if ($cantNodos > $cantNodosProf) {
                $cantNodosProf = $cantNodos;
                $profMasNodos = $prof;
            }
            $prof++;

        } while ($cantNodos != 0);
        return array('prof' => $profMasNodos, 'nodos' => $cantNodosProf, 'altura' => $prof);
    }
}


?>