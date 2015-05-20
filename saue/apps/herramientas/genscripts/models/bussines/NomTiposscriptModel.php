<?php

class NomTiposscriptModel extends ZendExt_Model
{
public function NomTiposscriptModel() {
        parent::ZendExt_Model();
    }

    public function insertar($tiposcript) {
        $tiposcript->save();
    }

    public function modificar($tiposcript) {

        $tiposcript->save();
    }

    public function eliminar($tiposcript) {
        $tiposcript->delete();
    }

}

