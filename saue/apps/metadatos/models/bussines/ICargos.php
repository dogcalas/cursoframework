<?php

class  ICargos extends ZendExt_Model
{
    /**
     * Enter description here...
     *
     * @var DatcargocivilModel
     */
    var $cargocivil;
    /**
     * Enter description here...
     *
     * @var DatcargoModel
     */

    var $cargo;
    /**qweqwewrewrew
     * Enter description here...
     *
     * @var DatcargomtarModel
     */
    var $cargomilitar;

    function ICargos()
    {
        $this->cargocivil = new DatcargocivilModel();
        $this->cargo = new DatcargoModel();
        $this->cargomilitar = new DatcargomtarModel();
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $idcarg
     * @param unknown_type $idcargociv
     * @param unknown_type $idcategciv
     * @param unknown_type $salar
     * @param unknown_type $idespecial
     * @param unknown_type $idtipoc
     * @param unknown_type $idpref
     * @param unknown_type $ctp
     * @param unknown_type $ctg
     * @param unknown_type $ord
     * @param unknown_type $est
     * @param unknown_type $fini
     * @param unknown_type $ffin
     * @return bool
     */
    function InsertarCargoCivil($idcarg, $idcargociv, $idcategciv, $salar, $idespecial, $idtipoc, $idpref, $ctp, $ctg, $ord, $est, $fini, $ffin)
    {

        if ($idcarg = $this->cargo->insertarCargo($idcarg, $idespecial, $idtipoc, $idpref, $ctp, $ctg, $ord, $est, $fini, $ffin)) {
            return $this->cargocivil->insertarCargocivil($idcarg, $idcargociv, $idcategciv, $salar);
        }
    }


    function buscarCargosPorClas($idop, $isdet)
    {
        return $this->cargo->buscarCargoPorTipoDet($idop, $isdet);
    }

    /**
     * devuelve true si existe el cargo civil dado un id...
     *
     * @param integer $idcc
     * @return bool
     */
    function ExisteCargoCivil($idcc)
    {
        return $this->cargocivil->existeCargocivil($idcc);
    }


    /**
     * Devuelve un arreglo de cargos civiles
     * @param int $l
     * @param int $s
     * @return array
     */
    function BuscarCargoCivil($l = 10, $s = 0)
    {
        return $this->cargocivil->buscarCargocivil($l, $s);
    }

    /**
     * Elimina un cago civil dado un di
     *
     * @param int $idcc
     * @return bool
     */

    function EliminarCargoCivil($idcc)
    {
        if ($this->cargocivil->eliminarCargocivil($idcc)) {
            return $this->cargo->eliminarCargo($idcc);
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $pidcarg
     * @param unknown_type $pidespecial
     * @param unknown_type $pidtipocif
     * @param unknown_type $pidprefo
     * @param unknown_type $mctp
     * @param unknown_type $mctg
     * @param unknown_type $pord
     * @param unknown_type $pestad
     * @param unknown_type $pfini
     * @param unknown_type $pffin
     * @param unknown_type $pidcargoc
     * @param unknown_type $pidcategc
     * @param unknown_type $psalar
     * @return bool
     */

    function ModificarCargoCivil($pidcarg, $pidespecial, $pidtipocif, $pidprefo, $mctp, $mctg, $pord, $pestad, $pfini, $pffin, $pidcargoc, $pidcategc, $psalar)
    {
        if ($this->cargo->modificarCargo($pidcarg, $pidespecial, $pidtipocif, $pidprefo, $mctp, $mctg, $pord, $pestad, $pfini, $pffin)) {
            return $this->cargocivil->modificarCargocivil($pidcarg, $pidcargoc, $pidcategc, $psalar);
        }

    }

    /**
     * Mostrar los cargos civiles asicioados a la informacion de los cargos generales
     *
     * @param int $limit
     * @param int $start
     * @return array
     */

    function GetDatosCiviles($l = 10, $s = 0)
    {
        return $this->cargocivil->getDatosCiviles($l, $s);
    }

    //---------------------------------------cargo militar------------------------------------------------
    //----------------------------------------------------------------------------------------------------

    /**
     * inserta un cargo militar..
     *
     * @param unknown_type $pidcargo
     * @param unknown_type $pidespecialidad
     * @param unknown_type $pidtipocifra
     * @param unknown_type $pidprefijo
     * @param unknown_type $pctp
     * @param unknown_type $pctg
     * @param unknown_type $porden
     * @param unknown_type $pestado
     * @param unknown_type $pfechaini
     * @param unknown_type $pfechafin
     * @param unknown_type $idcargmtar
     * @param unknown_type $idgradomtar
     * @param unknown_type $salario
     * @param unknown_type $escadmando
     * @return bool
     */

    function InsertarCargoMilitar($pidcargo, $pidespecialidad, $pidtipocifra, $pidprefijo, $pctp, $pctg, $porden, $pestado, $pfechaini, $pfechafin, $idcargmtar, $idgradomtar, $salario, $escadmando)
    {

        if ($idcarg = $this->cargo->insertarCargo($pidcargo, $pidespecialidad, $pidtipocifra, $pidprefijo, $pctp, $pctg, $porden, $pestado, $pfechaini, $pfechafin)) {
            return $this->cargomilitar->insertarDatcargomtar($pidcargo, $idcargmtar, $idgradomtar, $salario, $escadmando);
        }
    }

    /**
     * Enter description here...
     *
     * @param int $pId
     * @return bool
     */

    function ExisteDatcargomtar($pId)
    {
        return $this->cargomilitar->existeDatcargomtar($pId);
    }

    /**
     * Enter description here...
     *
     * @param int $l
     * @param int $s
     * @return array
     */

    function BuscarCargoMilitar($l = 10, $s = 0)
    {
        return $this->cargomilitar->buscarDatcargomtar($l, $s);
    }

    /**
     * Enter description here...
     *
     * @param int $idcc
     * @return bool
     */

    function EliminarCargoMilitar($idcc)
    {
        if ($this->cargomilitar->eliminarDatcargomtar($idcc)) {
            return $this->cargo->eliminarCargo($idcc);
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $pidcargo
     * @param unknown_type $pidespecialidad
     * @param unknown_type $pidtipocifra
     * @param unknown_type $pidprefijo
     * @param unknown_type $pctp
     * @param unknown_type $pctg
     * @param unknown_type $porden
     * @param unknown_type $pestado
     * @param unknown_type $pfechaini
     * @param unknown_type $pfechafin
     * @param unknown_type $pidcargmtar
     * @param unknown_type $pidgradomtar
     * @param unknown_type $psalario
     * @param unknown_type $pescadmando
     * @return bool
     */

    function ModificarCargoMilitar($pidcargo, $pidespecialidad, $pidtipocifra, $pidprefijo, $pctp, $pctg, $porden, $pestado, $pfechaini, $pfechafin, $pidcargmtar, $pidgradomtar, $psalario, $pescadmando)
    {
        if ($this->cargo->modificarCargo($pidcargo, $pidespecialidad, $pidtipocifra, $pidprefijo, $pctp, $pctg, $porden, $pestado, $pfechaini, $pfechafin)) {
            return $this->cargomilitar->modificarDatcargomtar($pidcarg, $pidcargmtar, $pidgradomtar, $psalario, $pescadmando);
        }

    }

    /**
     * Enter description here...
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @param unknown_type $idop
     * @return array
     */

    function BuscarCargosPorInterfaces($limit = 10, $start = 0, $idop)
    {
        return $this->cargo->buscarCargo($limit, $start, $idop);
    }

    /**
     * Buscar cargos por tipos;
     *
     * @param unknown_type $idop
     * @return unknown
     */

    function BuscarCargosPorTipos($idop)
    {
        return $this->cargo->buscarCargoPorTipo($idop);
    }

    /**
     * Devuelve los datos de un cardo dado un id
     */
    function getCargobyId($id)
    {
        //return $this->cargo->getCargo($id);
        return $this->cargo->getCargo(10);
    }

    function buscarcargoPuesto($id)
    {
        return $this->cargo->buscarCargoConPuestos($id);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return array
     */

    function GetDatosMilitares($limit = 10, $start = 0)
    {
        return $this->cargomilitar->getDatosMilitares($limit, $start);
    }

    function GetDatosCargo($idcargo, $militar)
    {
        return $this->cargo->datosCargo($idcargo, $militar);

    }

}

?>