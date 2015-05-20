<?php

class IFormato
{
    public static function getEstructuraInterna()
    {
        return new EstructuraopModel();
    }

    public static function getCargos()
    {
        return new DatcargoModel();
    }

    public static function getPuestos()
    {
        return new DatpuestoModel();
    }

    public function getShema()
    {
        return "mod_estructuracomp";
    }
}

?>