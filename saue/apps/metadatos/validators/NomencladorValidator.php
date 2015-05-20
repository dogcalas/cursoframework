<?php
class NomencladorValidator
{


    //----------------funcion global-------------
    public function ValidarCamposMostrar()
    {
        if ($_POST['limit'] == '' || $_POST['start'] == '') {
            $_POST['limit'] = 100000;
            $_POST['start'] = 0;

            return true;
        }
        return true;

    }

    //-----------Nomenclador Grado Militar ------------------------

    public function ValidarCamposGradoMilitarinsec()
    {
        if ($_POST['idgsubcateg'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            //$_POST['homologoterr'] =='' ||
            $_POST['esmarina'] == '' ||
            //$_POST['valanterior'] =='' ||
            // $_POST['valsucesor'] =='' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        if ($_POST['valanterior'] == '')
            $_POST['valanterior'] = null;
        if ($_POST['valsucesor'] == '')
            $_POST['valsucesor'] = null;
        if ($_POST['homologoterr'] == '')
            $_POST['homologoterr'] = 0;
        return true;
    }

    public function ValidarCamposGradoMilitarmod()
    {
        if ($_POST['idgradomilit'] == '' ||
            $_POST['idgsubcateg'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            //$_POST['homologoterr'] =='' ||
            $_POST['esmarina'] == '' ||
            // $_POST['valanterior'] =='' ||
            //$_POST['valsucesor'] =='' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['fini'] == ''
        )
            return false;


        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        if ($_POST['valanterior'] == '')
            $_POST['valanterior'] = null;
        if ($_POST['valsucesor'] == '')
            $_POST['valsucesor'] = null;

        return true;
    }

    public function ValidarCamposGradoMilitarelim()
    {
        if ($_POST['idgradomilit'] == '')
            return false;
        return true;
    }

    //----Nomenclador de Agrupacion------------------------------------------------------

    public function ValidarCamposAgrupacioninserc()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposAgrupacionmod()
    {
        if ($_POST['idagrupacion'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''
        )
            return false;

        return true;
    }

    public function ValidarCamposAgrupacionelim()
    {
        if ($_POST['idagrupacion'] == '')
            return false;
        $obj = new NomagrupacionesModel();
        $idagrupacion = $_POST['idagrupacion'];

        if ($obj->usandoNomagrupaciones($idagrupacion))
            return false;

        return true;
    }

    //-------------------Nomenclador Preparacion militar-----------------

    public function ValidarCamposPrepmilitarinserc()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''
        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;

    }

    public function ValidarCamposPrepmilitarmod()
    {
        if ($_POST['idprepmilitar'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''
        )
            return false;

        return true;
    }

    public function ValidarCamposPrepmilitarelim()
    {
        if ($_POST['idprepmilitar'] == '')
            return false;

        $obj = new NomprepmilitarModel();
        $id = $_POST['idprepmilitar'];

        if ($obj->usandoPrepraMtar($id))
            return false;
        return true;
    }

    //-----------Nomenclador de Nivel utilizacion-----------------------
    public function ValidarCamposNivelutilizacioninsec()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposNivelutilizacionmod()
    {
        if ($_POST['idnivelutilizacion'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''

        )
            return false;
        return true;
    }

    public function ValidarCamposNivelutilizacionelim()
    {
        if ($_POST['idnivelutilizacion'] == '')
            return false;
        $id = $_POST['idnivelutilizacion'];
        $obj = new NomnivelutilModel();
        if ($obj->usandoNomnivelutilizacion($id))
            return false;
        return true;
    }

    //----------------------Nomenclador de Organos-------------------------------------------
    public function ValidarCamposOrganosmos()
    {
        if ($_POST['limit'] == '' || $_POST['start'] == '') {
            $_POST['limit'] = 10;
            $_POST['limit'] = 0;
            return true;
        }
        return true;
    }

    public function ValidarCamposOrganosmod()
    {
        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;
        if ($_POST['idorgano'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||

            $_POST['idnivelestr'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposOrganosinsert()
    {
        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;

        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['idnivelestr'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposOrganoselim()
    {
        if ($_POST['idorgano'] == '')
            return false;
        $obj = new NomorganoModel();
        $idorga = $_POST['idorgano'];

        if ($obj->verificarUsado($idorga))
            return false;

        return true;
    }


    //---------------------Nomenclador de subcategoria----------------------------------------

    public function ValidarCamposSubcategoriamos()
    {
        if ($_POST['limit'] == '' || $_POST['start'] == '') {
            $_POST['limit'] = 10;
            $_POST['limit'] = 0;
            return true;
        }
        return true;
    }

    public function ValidarCamposSubcategoriainsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposSubcategoriamod()
    {
        if ($_POST['idsbcategorias'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''
        )
            return false;

        return true;
    }

    public function ValidarCamposSubcategoriaelim()
    {
        if ($_POST['idsbcategorias'] == '')
            return false;
        return true;
    }


    //---------------------Nomenclador de Nivel Estructural----------------------------------------

    public function ValidarCamposNivelestrmos()
    {
        if ($_POST['limit'] == '' || $_POST['start'] == '') {
            $_POST['limit'] = 10;
            $_POST['limit'] = 0;
            return true;
        }
        return true;
    }

    public function ValidarCamposNivelestrinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposNivelestrmod()
    {
        if ($_POST['idnvelestr'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['ffin'] == ''
        )
            return false;

        return true;
    }

    public function ValidarCamposNivelestrelim()
    {
        if ($_POST['idnvelestr'] == '')
            return false;
        $obj = new NomnivelestrModel();
        $idnvelestr = $_POST['idnvelestr'];

        if ($obj->usandoNivelsestr($idnvelestr))
            return false;

        return true;
    }

    //---------------------Nomenclador Tipo Calificador------------

    public function  ValidarCamposTipocalifcadorinserc()
    {

        if ($_POST['denom'] == '' || $_POST['abrev'] == '' || $_POST['orden'] == '' || $_POST['fini'] == '')
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "05/05/3000";
        }

        return true;
    }

    public function ValidarCamposTipocalifcadormod()
    {
        if ($_POST['idtipocalificador'] == '' || $_POST['denom'] == '' || $_POST['abrev'] == '' || $_POST['orden'] == '' || $_POST['fini'] == '')
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposTipocalifcadorelim()
    {
        if ($_POST['idtipocalificador'] == '')
            return false;
        else {
            $obj = new NomtipocalificadorModel();
            $id = $_POST['idtipocalificador'];
            if ($obj->usandoNomTipocalificador($id))
                return false;
        }

        return true;

    }

    // ------------------------------ Nomenclador de prefijos ------------


    public function ValidarCamposPrefijoinsert()
    {
        if ($_POST['prefijo'] == '' ||
            $_POST['lugar'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''

        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposPrefijomod()
    {
        if ($_POST['idpref'] == '' ||
            $_POST['prefijo'] == '' ||
            $_POST['lugar'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposPrefijoelim()
    {
        if ($_POST['idpref'] == '')
            return false;
        $obj = new NomprefijoModel();
        $id = $_POST['idpref'];

        if ($obj->usadoNomprefijo($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Grupo Complejidad--> ------------


    public function ValidarCamposGrupoComplejidadinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposGrupoComplejidadmod()
    {
        if ($_POST['idgrupocomplejidad'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||

            $_POST['orden'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposGrupoComplejidadelim()
    {
        if ($_POST['idgrupocomplejidad'] == '')
            return false;
        $obj = new NomgrupocompleModel();
        $id = $_POST['idgrupocomplejidad'];

        if ($obj->usandoNomgrupocomplejidad($id))
            return false;

        return true;
    }


    // ------------------------------ <!--Nomenclador Escala Salarial--> ------------


    public function ValidarCamposEscalaSalarialinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposEscalaSalarialmod()
    {
        if ($_POST['idescalasalarial'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposEscalaSalarialelim()
    {
        if ($_POST['idescalasalarial'] == '')
            return false;
        $obj = new NomescalasalarialModel();
        $id = $_POST['idescalasalarial'];

        if ($obj->usandoNomescalasalarial($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador categoria ocupacional--> ------------


    public function ValidarCamposCatgriaOcpnalinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposCatgriaOcpnalmod()
    {
        if ($_POST['idcatgriaocpnal'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposCatgriaOcpnalelim()
    {
        if ($_POST['idcatgriaocpnal'] == '')
            return false;
        $obj = new NomcategoriaocupacionalModel();
        $id = $_POST['idcatgriaocpnal'];

        if ($obj->usadoNomCategoriaOcupId($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador categoria Civil--> ------------


    public function ValidarCamposCatgriaCvilinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['idessueldo'] == ''
        )
            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposCatgriaCvilmod()
    {
        if ($_POST['idcatgriacvil'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['idessueldo'] == ''
        )
            return false;
        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }
        return true;
    }

    public function ValidarCamposCatgriaCvilelim()
    {
        if ($_POST['idcatgriacvil'] == '')
            return false;
        $obj = new NomcategcivilModel();
        $id = $_POST['idcatgriacvil'];

        if ($obj->usandoCategoriaCvil($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Calificador--> ------------


    public function ValidarCamposCalificadorinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['idtipocalificador'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['idcategocup'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposCalificadormod()
    {
        if ($_POST['idcalificador'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['idtipocalificador'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['idcategocup'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposCalificadorelim()
    {
        if ($_POST['idcalificador'] == '')
            return false;
        $obj = new NomcalificadorModel();
        $id = $_POST['idcalificador'];

        if ($obj->usandoNomcalificador($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Salario--> ------------


    public function ValidarCamposSalarioinsert()
    {
        if ($_POST['idgrupocomplejidad'] == '' ||
            $_POST['idescalasalarial'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['salario'] == '' ||
            $_POST['tarifa'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposSalariomod()
    {
        if ($_POST['idsalario'] == '' ||
            $_POST['idgrupocomplejidad'] == '' ||
            $_POST['idescalasalarial'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['salario'] == '' ||
            $_POST['tarifa'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposSalarioelim()
    {
        if ($_POST['idsalario'] == '')
            return false;
        $obj = new NomsalarioModel();
        $id = $_POST['idsalario'];

        if ($obj->usandoNonsalario($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Tecnica--> ------------


    public function ValidarCamposTecnicainsert()
    {
        if ($_POST['codigo'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['idvalor'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposTecnicamod()
    {
        if ($_POST['idtecnica'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['abrev'] == '' ||
            $_POST['idvalor'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposTecnicaelim()
    {
        if ($_POST['idtecnica'] == '')
            return false;
        $obj = new NomtecnicaModel();
        $id = $_POST['idtecnica'];

        if ($obj->usandoTecnica($id))
            return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Modulo--> ------------


    public function ValidarCamposModuloinsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposModulomod()
    {
        if ($_POST['idmodulo'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposModuloelim()
    {
        if ($_POST['idmodulo'] == '')
            return false;
        // $obj = new NomsalarioModel();
        //$id = $_POST['idsalario'];

        //if($obj->usandoNonsalario($id))
        //   return false;

        return true;
    }

    // ------------------------------ <!--Nomenclador Clasificacion--> ------------


    public function ValidarCamposClasificacioninsert()
    {
        if ($_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposClasificacionmod()
    {
        if ($_POST['idclasificacion'] == '' ||
            $_POST['denom'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['orden'] == ''
        )

            return false;

        if ($_POST['ffin'] == '') {
            $_POST['ffin'] = "30/12/2300";
        }

        return true;
    }

    public function ValidarCamposClasificacionelim()
    {
        if ($_POST['idclasificacion'] == '')
            return false;
        $obj = new NomclasificacioncargoModel();
        $id = $_POST['idclasificacion'];

        if ($obj->usandoClasificacion($id))
            return false;
        return true;
    }


    // ------------------------------ <!--Nomenclador Cargo Civil--> ------------
    public function ValidarCamposCargoCivilinserc()
    {
        if ($_POST['denom'] == '' ||
            //$_POST['idespecialidad']==''		||
            $_POST['idcategocup'] == '' ||
            //$_POST['orden']==''					||
            $_POST['fini'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['descrip'] == '' ||
            $_POST['requisito'] == '' ||
            $_POST['idgrupocomplejidad'] == '' ||
            $_POST['idnivelutilizacion'] == '' ||
            $_POST['idcalificador'] == ''
        )
            return false;

        if ($_POST['ffin'] == '')
            $_POST['ffin'] = '09/11/2020';

        /*if($_POST['idespecialidad']=='')
          $_POST['idespecialidad']=1;*/

        $idesp = $_POST['idespecialidad'];
        if ($idesp == 'idespecialidad')
            $idesp = 1;
        /*$obj  = new ZendExt_Nomencladores_ADT();
         $especial			= $obj->getElement("nom_especialidad",$idesp);
        $cont				= count($especial);
        if ($cont==0)
              throw new Zend_Exception ('EC06'); */
        return true;
    }

    public function ValidarCamposCargoCivilimod()
    {

        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;

        if ($_POST['idcargociv'] == '' ||
            $_POST['denom'] == '' ||

            $_POST['idcategocup'] == '' ||
            //$_POST['orden']==''					||
            $_POST['fini'] == '' ||
            $_POST['codigo'] == '' ||
            $_POST['descrip'] == '' ||
            $_POST['requisito'] == '' ||
            $_POST['idgrupocomplejidad'] == '' ||
            $_POST['idnivelutilizacion'] == '' ||
            $_POST['idcalificador'] == ''
        )
            return false;

        if ($_POST['ffin'] == '')
            $_POST['ffin'] = "30/12/2300";

        $idesp = $_POST['idespecialidad'];
        /*if( $idesp	=='idespecialidad'){
            return false;
        }*/

        /* $idesp			=	$_POST['idespecialidad'];
        $obj  = new ZendExt_Nomencladores_ADT();
         $especial			= $obj->getElement("nom_especialidad",$idesp);
        $cont				= count($especial);
        if ($cont==0)
              throw new Zend_Exception ('EC06');*/
        return true;
    }

    public function ValidarCamposCargoCivilelim()
    {
        if ($_POST['idcargociv'] == '')
            return false;
        $obj = new NomcargocivilModel();
        $id = $_POST['idcargociv'];

        if ($obj->usadoNomcargocivil($id))
            return false;
        return true;
    }

    // ------------------------------ <!--Nomenclador CargoMilitar--> ------------


    public function ValidarCamposCargoMtarinserc()
    {
        if ($_POST['denom'] == '' ||
            //$_POST['idespecialidad']==''		||
            $_POST['idprepmilitar'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['abrev'] == ''
        )
            return false;

        if ($_POST['ffin'] == '')
            $_POST['ffin'] = "30/12/2300";

        $idesp = $_POST['idespecialidad'];
        if ($idesp == 'idespecialidad' || $idesp == '')
            $idesp = 1;

        return true;
    }

    public function ValidarCamposCargoMilitarmod()
    {
        if ($_POST['idcargomilitar'] == '' ||
            $_POST['denom'] == '' ||
            //$_POST['idespecialidad']==''		||
            $_POST['idprepmilitar'] == '' ||
            $_POST['orden'] == '' ||
            $_POST['fini'] == '' ||
            $_POST['abrev'] == ''
        )

            return false;

        if ($_POST['idespecialidad'] == '')
            $_POST['idespecialidad'] = 1;

        $idesp = $_POST['idespecialidad'];
        if ($idesp == 'idespecialidad') {
            $idesp = 1;
        }
        if ($_POST['ffin'] == '')
            $_POST['ffin'] = "30/12/2300";


        return true;
    }

    public function ValidarCamposCargoMilitarelim()
    {
        if ($_POST['idcargomilitar'] == '')
            return false;
        $obj = new NomcargomilitarModel();
        $id = $_POST['idcargomilitar'];

        if ($obj->usandoNomcargoMtar($id))
            return false;
        return true;
    }
    /*--------------------------Tipo cifra-----------*/

}

?>
