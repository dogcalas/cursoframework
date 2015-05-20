<?php

class EstructuraValidator
{

    public function ValidarIdTabla()
    {
        if (!isset($_POST['idtabla']))
            $_POST['idtabla'] = false;
        return true;
    }

    public function ValidarIdTablas()
    {
        if (!isset($_POST['idtabla']))
            $_POST['idtabla'] = 2;
        return true;
    }

    public function ValidarIdTablasinsertarvalores()
    {
        if ($_POST['idtabla'] == '' ||
            $_POST['denominacion'] == '' ||
            $_POST['abreviatura'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['fechai'] == '' ||
            $_POST['iddpa'] == ''
        ) return false;

        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;

        if ($_POST['fechaf'] == '')
            $_POST['fechaf'] = "05/05/3000";
        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];

        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);

        if ($cont == 0)
            throw new Zend_Exception('EC06');

        $nivel = new NomnivelestrModel();
        if (!$nivel->existeNomNivelEstr($_POST['idnivelestr']))
            throw new Zend_Exception('EC07');
        $iddpa = $_POST['iddpa'];
        $obj = new ZendExt_Nomencladores_ADT();

        $existe = $obj->getElement("nom_dpa", $iddpa);
        $cont = count($existe);
        if ($cont == 0)
            throw new Zend_Exception('EC08');

        $fi = $_POST['fechai'];
        $ff = $_POST['fechaf'];
        if ($this->comprobarFecha($fi, $ff))
            throw new Zend_Exception('EC09', "error");


        return true;
    }

    public function ValidarValorFila()
    {
        if ($_POST['idfila'] == '')
            return false;
        return true;
    }

    public function ValidarValorTabla()
    {
        if ($_POST['idtabla'] == '')
            return false;

        return true;
    }

    public function ValidarValorTablaGet()
    {
        if ($_POST['idtabla'] == '')
            return false;
        die($_POST['idtabla']);
        return true;
    }

    public function Construircamposgridop()
    {
        if ($_POST['idtabla'] == '' || $_POST['idestructura'] == '')
            return false;
        return true;
    }

    public function Buscarhijosagrup()
    {
        if ($_POST['node'] == '')
            return false;
        if ($_POST['node'] == 'Estructuras1') {
            $_POST['node'] = false;
        }
        return true;
    }

    public function ValidarModificarvalores()
    {

        if ($_POST['idfila'] == '' ||
            $_POST['denominacion'] == '' ||
            $_POST['abreviatura'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['fechai'] == '' ||
            $_POST['iddpa'] == '' ||
            $_POST['nom_organo'] == '' ||
            $_POST['idnivelestr'] == ''
        )
            return false;

        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;
        if ($_POST['fechaf'] == '')
            $_POST['fechaf'] = "05/05/3000";

        $fi = $_POST['fechai'];
        $ff = $_POST['fechaf'];
        if ($this->comprobarFecha($fi, $ff))
            throw new ZendExt_Exception('EC09');


        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];
        if ($idesp == 'idespecialidad') {
            return false;
        }
        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);
        if ($cont == 0)
            throw new Zend_Exception('EC06');


        $nivel = new NomnivelestrModel();
        if (!$nivel->existeNomNivelEstr($_POST['idnivelestr']))
            throw new Zend_Exception('EC07');
        $iddpa = $_POST['iddpa'];
        $obj = new ZendExt_Nomencladores_ADT();
        if ($iddpa == 'iddpa') {
            return false;
        }
        $existe = $obj->getElement("nom_dpa", $iddpa);
        $cont = count($existe);
        if ($cont == 0)
            throw new Zend_Exception('EC08');
        return true;
    }

    public function ValidaBuscarcomposicion()
    {
        if ($_POST['node'] == '')
            return false;

        return true;
    }

    public function ValidaInsertarCargocivil()
    {

        if ($_POST['idop'] == '' ||
            $_POST['ctp'] == '' ||
            $_POST['ctg'] == '' ||
            $_POST['idtipocifra'] == '' ||
            $_POST['idcargociv'] == '' ||
            $_POST['idsalario'] == '' ||
            $_POST['idgrupocomplejidad'] == '' ||
            $_POST['idescalasalarial'] == '' ||
            $_POST['idclasificacion'] == '' ||
            $_POST['fechaini'] == ''
        )
            return false;

        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = "05/05/3000";


        $fi = $_POST['fechaini'];
        $ff = $_POST['fechafin'];

        if ($this->comprobarFecha($fi, $ff))
            throw new ZendExt_Exception('EC09');


        $modelEstrop = new EstructuraopModel();
        $idestructuraop = $_POST['idop'];
        //-----Nomencladores---------
        $modelTipo = new NomtipocifraModel();
        $idtipocifra = $_POST['idtipocifra'];

        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];
        if ($idesp == 'idespecialidad')
            $idesp = 1;

        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);

        $salariomodel = new NomsalarioModel();
        $salario = $_POST['idsalario'];
        $modelgrupo = new NomgrupocompleModel();
        $idgrupocomple = $_POST['idgrupocomplejidad'];
        $modelescala = new NomescalasalarialModel();
        $idescalasalarial = $_POST['idescalasalarial'];
        $modelclasif = new NomclasificacioncargoModel();
        $idclasificacion = $_POST['idclasificacion'];
        $modelCatCivil = new NomcategcivilModel();
        $idcategcivil = $_POST['idcategcivil'];
        $modelNomCargoC = new NomcargocivilModel();
        $idnomcargicivil = $_POST['idcargociv'];
        if (!$modelEstrop->exiteDatestructuraop($idestructuraop))
            throw new ZendExt_Exception('EC11');

        if ($cont == 0)
            throw new ZendExt_Exception('EC12');

        if (!$modelTipo->existeNomtipocifra($idtipocifra))
            throw new ZendExt_Exception('EC13');

        if ($_POST['idcategcivil'] == '')
            $_POST['idcategcivil'] = null;
        else {
            if (!$modelCatCivil->existeNomcategcivil($idcategcivil))
                throw new ZendExt_Exception('EC14');
        }

        if (!$modelNomCargoC->existeNomcargocivil($idnomcargicivil))
            throw new ZendExt_Exception('EC15');
        if (!$salariomodel->existeNomsalario($salario))
            throw new ZendExt_Exception('EC16');
        if (!$modelgrupo->existeNomgrupocomple($idgrupocomple))
            throw new ZendExt_Exception('EC17');
        if (!$modelescala->existeNomEscalasalarial($idescalasalarial))
            throw new ZendExt_Exception('EC18');
        if (!$modelclasif->exitNomclasificacion($idclasificacion))
            throw new ZendExt_Exception('EC19');


        return true;
    }

    public function ValidaModificarCargocivil()
    {
        if ($_POST['idcargo'] == '' ||
            $_POST['ctp'] == '' ||
            $_POST['ctg'] == '' ||
            $_POST['idtipocifra'] == '' ||
            $_POST['idcargociv'] == '' ||
            $_POST['idsalario'] == '' ||
            $_POST['idgrupocomplejidad'] == '' ||
            $_POST['idescalasalarial'] == '' ||
            $_POST['idclasificacion'] == '' ||
            $_POST['fechaini'] == ''
        )
            return false;
        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;
        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = "05/05/3000";


        $fi = $_POST['fechaini'];
        $ff = $_POST['fechafin'];

        if ($this->comprobarFecha($fi, $ff))
            throw new ZendExt_Exception('EC09');


        //-----Nomencladores---------
        $modelTipo = new NomtipocifraModel();
        $idtipocifra = $_POST['idtipocifra'];

        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];

        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);

        $salariomodel = new NomsalarioModel();
        $salario = $_POST['idsalario'];
        $modelgrupo = new NomgrupocompleModel();
        $idgrupocomple = $_POST['idgrupocomplejidad'];
        $modelescala = new NomescalasalarialModel();
        $idescalasalarial = $_POST['idescalasalarial'];
        $modelclasif = new NomclasificacioncargoModel();
        $idclasificacion = $_POST['idclasificacion'];
        $modelCatCivil = new NomcategcivilModel();
        $idcategcivil = $_POST['idcategcivil'];
        $modelNomCargoC = new NomcargocivilModel();
        $idnomcargicivil = $_POST['idcargociv'];


        if ($cont == 0)
            throw new ZendExt_Exception('EC12');

        if (!$modelTipo->existeNomtipocifra($idtipocifra))
            throw new ZendExt_Exception('EC13');

        if ($_POST['idcategcivil'] == '')
            $_POST['idcategcivil'] = null;
        else {
            if (!$modelCatCivil->existeNomcategcivil($idcategcivil))
                throw new ZendExt_Exception('EC14');
        }

        if (!$modelNomCargoC->existeNomcargocivil($idnomcargicivil))
            throw new ZendExt_Exception('EC15');
        if (!$salariomodel->existeNomsalario($salario))
            throw new ZendExt_Exception('EC16');
        if (!$modelgrupo->existeNomgrupocomple($idgrupocomple))
            throw new ZendExt_Exception('EC17');
        if (!$modelescala->existeNomEscalasalarial($idescalasalarial))
            throw new ZendExt_Exception('EC18');
        if (!$modelclasif->exitNomclasificacion($idclasificacion))
            throw new ZendExt_Exception('EC19');


        return true;
    }

    public function Validabuscarsalarioporgrupoescala()
    {
        if ($_POST['idgrupocomplejidad'] == '' ||
            $_POST['idescalasalarial'] == ''
        )
            return false;
        return true;
    }

    public function Validarmostrarcargos()
    {
        if ($_POST['idop'] == '')
            return false;
        return true;
    }

    public function Validarmostrardatoscargo()
    {
        if ($_POST['idcargo'] == '')
            return false;
        return true;
    }

    public function ValidarInsertarCargomilitar()
    {
        if ($_POST['idop'] == '' ||
            //$_POST['idespecialidad']==''	||
            $_POST['ctp'] == '' ||
            $_POST['ctg'] == '' ||
            $_POST['idtipocifra'] == '' ||
            $_POST['idgradomilit'] == '' ||
            $_POST['idcargomilitar'] == '' ||
            $_POST['salario'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['estado'] == '' ||
            $_POST['tienemando'] == '' ||
            $_POST['fechaini'] == ''
        )
            return false;
        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;
        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = "05/05/3000";


        $fi = $_POST['fechaini'];
        $ff = $_POST['fechafin'];


        if ($this->comprobarFecha($fi, $ff))
            throw new ZendExt_Exception('EC09');

        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];

        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);
        if ($cont == 0)
            throw new ZendExt_Exception('EC12');

        $modelEstrop = new EstructuraopModel();
        $idestructuraop = $_POST['idop'];
        $modelTipo = new NomtipocifraModel();
        $idtipocifra = $_POST['idtipocifra'];
        $modelNomGrado = new NomgradomilitarModel();
        $idgradomtar = $_POST['idgradomilit'];
        $modelNomCargoM = new NomcargomilitarModel();
        $idnomcargomtar = $_POST['idcargomilitar'];
        if (!$modelNomCargoM->existeNomcargo($idnomcargomtar))
            throw new ZendExt_Exception('EC21');

        if (!$modelNomGrado->existeGrado($idgradomtar))
            throw new ZendExt_Exception('EC20');

        if (!$modelEstrop->exiteDatestructuraop($idestructuraop))
            throw new ZendExt_Exception('EC11');

        if (!$modelTipo->existeNomtipocifra($idtipocifra))
            throw new ZendExt_Exception('EC13');

        return true;
    }

    public function ValidarModificarCargomilitar()
    {
        if ($_POST['idcargo'] == '' ||
            //$_POST['idespecialidad']==''	||
            $_POST['ctp'] == '' ||
            $_POST['ctg'] == '' ||
            $_POST['idtipocifra'] == '' ||
            $_POST['idgradomilit'] == '' ||
            $_POST['idcargomilitar'] == '' ||
            $_POST['salario'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['estado'] == '' ||
            $_POST['tienemando'] == '' ||
            $_POST['fechaini'] == ''
        )
            return false;
        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;
        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = "05/05/3000";


        $fi = $_POST['fechaini'];
        $ff = $_POST['fechafin'];

        if ($this->comprobarFecha($fi, $ff))
            throw new ZendExt_Exception('EC09');

        $nomEspeci = new ZendExt_Nomencladores_ADT();
        $idesp = $_POST['idespecialidad'];

        $especial = $nomEspeci->getElement("nom_especialidad", $idesp);
        $cont = count($especial);
        if ($cont == 0)
            throw new ZendExt_Exception('EC12');


        $modelTipo = new NomtipocifraModel();
        $idtipocifra = $_POST['idtipocifra'];
        $modelNomGrado = new NomgradomilitarModel();
        $idgradomtar = $_POST['idgradomilit'];
        $modelNomCargoM = new NomcargomilitarModel();
        $idnomcargomtar = $_POST['idcargomilitar'];
        if (!$modelNomCargoM->existeNomcargo($idnomcargomtar))
            throw new ZendExt_Exception('EC21');
        if (!$modelNomGrado->existeGrado($idgradomtar))
            throw new ZendExt_Exception('EC20');
        if (!$modelTipo->existeNomtipocifra($idtipocifra))
            throw new ZendExt_Exception('EC13');

        return true;
    }

    public function ValidarEliminarCargomilitar()
    {
        if ($_POST['idcargo'] == '')
            return false;
        return true;
    }

    public function ValidarAgrupacionLogica()
    {
        if ($_POST['idestructura'] == '' || $_POST['idagrupacion'] == '')
            return false;
        return true;
    }

    public function ValidarInsertarTecnica()
    {
        if ($_POST['idcargo'] == '' ||
            $_POST['idtecnica'] == '' ||
            $_POST['cantidad'] == '' ||
            $_POST['ctg'] == ''
        )
            return false;
        return true;
    }

    public function ValidarEliminarTecnica()
    {
        if ($_POST['idcargo'] == '' ||
            $_POST['idtecnica'] == ''
        )
            return false;
        return true;
    }

    public function ValidarModificarTecnica()
    {
        if ($_POST['idcargo'] == '' ||
            $_POST['iddtecnica'] == '' ||
            $_POST['cantidad'] == ''
        )
            return false;
        return true;
    }

    public function ValidarMostraragrupacion()
    {
        if ($_POST['idagrupacion'] == '')
            return false;
        return true;
    }

    public function ValidarMostratecnica()
    {
        if ($_POST['idestructura'] == '')
            return false;
        return true;
    }

    function comprobarFecha($fechainicio, $fechafin)
    {
        $fi = str_replace("/", '', $fechainicio);
        $ff = str_replace("/", '', $fechafin);

        $fi = str_split($fi, 2);
        $ff = str_split($ff, 2);
        $fi = $fi[2] . $fi[3] . $fi[1] . $fi[0];
        $ff = $ff[2] . $ff[3] . $ff[1] . $ff[0];

        if ($fi > $ff)
            return true;
        else
            return false;
    }

}

?>
