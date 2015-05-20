<?php

class DatAccionDatServicioiocModel extends ZendExt_Model
{

    public function DatAccionDatServicioiocModel()
    {
			parent::ZendExt_Model();
    }
    
    public function Eliminar($instancia){
        $instancia->delete();
    }
    public function Insertar($obj){
        $obj->save();
    }
}

