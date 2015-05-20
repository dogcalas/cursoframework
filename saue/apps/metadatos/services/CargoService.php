<?php

class  CargoService extends ZendExt_Model
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
    /**
     * Enter description here...
     *
     * @var DatcargomtarModel
     */
    var $cargomilitar;

    function __construct()
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

    /**
     * Enter description here...
     *
     * @param unknown_type $idop
     * @param unknown_type $isdet
     * @return array
     */
    function BuscarCargosPorClas($idop, $isdet)
    {
        $arraydata = $this->cargo->buscarCargoPorTipoDet($idop, $isdet);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
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
        $arraydata = $this->cargocivil->buscarCargocivil($l, $s);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;

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

    function DameDatosCiviles($l = 10, $s = 0)
    {
        $arraydata = $this->cargocivil->getDatosCiviles($l, $s);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;

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

    function ExisteDatCargoMtar($pId)
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
        $arraydata = $this->cargomilitar->buscarDatcargomtar($l, $s);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
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
        $arraydata = $this->cargo->buscarCargo($limit, $start, $idop);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Buscar cargos por tipos;
     *
     * @param unknown_type $idop
     * @return array
     */

    function BuscarCargosPorTipos($idop)
    {
        $arraydata = $this->cargo->buscarCargoPorTipo($idop);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    function BuscarCargosPorTiposSeguridad($idop)
    {
        return $this->cargo->BuscarCargosPorTiposSeguridad($idop);
    }

    /**
     * Devuelve los datos de un cardo dado un id
     */
    function DameCargoPorId($id)
    {
        //return $this->cargo->getCargo($id);

        $arraydata = $this->cargo->getCargo($id);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Devuelve los datos de un cardo dado un id
     */
    function CargoDadoIDSeguridad($idcargo)
    {
        return $this->cargo->CargoDadoIDSeguridad($idcargo);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $id
     * @return array
     */
    function BuscarCargoPuesto($id)
    {
        $arraydata = $this->cargo->buscarCargoConPuestos($id);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return array
     */

    function DameDatosMilitares($limit = 10, $start = 0)
    {
        $arraydata = $this->cargomilitar->getDatosMilitares($limit, $start);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    function DameDatosCargo($idcargo, $militar)
    {
        $arraydata = $this->cargo->datosCargo($idcargo, $militar);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;

    }

    function buscarCargoPorNombreArea($identidad, $nombrearea, $tipo)
    {
        /*En este sericio si se pasa 1 en el parametro $tipo devuelve los cargos militares , si pasa 2 , los civiles y si pasa 3 los civiles y militares*/
        $arraydata = $this->cargo->buscarCargoPorNombreArea($identidad, $nombrearea, $tipo);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;

    }

}

?>