<?php

class CNodo
{
    var $id;
    var $idPadre;
    var $infomacion;

    function CNodo($id, $idPadre, $infomacion)
    {
        $this->id = $id;
        $this->idPadre = $idPadre == "padre" ? false : $idPadre;

        $this->infomacion = $infomacion;
    }

    function esRaiz()
    {
        return $this->$idPadre === false;
    }

}


?>