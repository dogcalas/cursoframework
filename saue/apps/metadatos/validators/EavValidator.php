<?php
class EavValidator
{


    public function Validarinsertatablas()
    {
        if ($_POST['nombre'] == '')
            return false;

        if ($_POST['fechaini'] == '')
            $_POST['fechaini'] = '2005/05/05';
        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = '2050/05/08';
        return true;
    }

    public function ValidarmodificarTabla()
    {
        if ($_POST['idtabla'] == '' ||
            $_POST['nombre'] == ''
        )
            return false;

        if ($_POST['fechaini'] == '')
            $_POST['fechaini'] = '2005/05/05';
        if ($_POST['fechafin'] == '')
            $_POST['fechafin'] = '3000/05/08';

        return true;
    }

    public function ValidareliminarTabla()
    {
        if ($_POST['idtabla'] == '') {
            return false;
        }

        $model = new TablaModel();
        if ($model->usandoEav($_POST['idtabla']))
            return false;
        return true;
    }


    public function  Validarbuscarconexion()
    {
        if ($_POST['idtabla'] == '') {
            return false;
        }
        return true;
    }

    public function Validarinsertarcampos()
    {
        if ($_POST['idtabla'] == '' ||
            $_POST['nombre'] == '' ||
            $_POST['tipo'] == '' ||
            $_POST['longitud'] == '' ||
            $_POST['nombre_mostrar'] == '' ||
            $_POST['tipocampo'] == '' /*||
			$_POST['descripcion']==''*/
        )
            return false;

        return true;
    }

    public function Validareliminarcampos()
    {
        if ($_POST['idcampo'] == '')
            return false;

        return true;
    }

    public function Validarmostrarcampos()
    {
        if ($_POST['idtabla'] == '')
            return false;

        return true;
    }

    public function Validarmodificarcampos()
    {
        if ($_POST['idcampo'] == '' ||
            $_POST['idtabla'] == '' ||
            $_POST['nombre'] == '' ||
            $_POST['tipo'] == '' ||
            $_POST['longitud'] == '' ||
            $_POST['nombre_mostrar'] == '' ||
            $_POST['tipocampo'] == '' ||
            $_POST['descripcion'] == ''
        )
            return false;

        return true;
    }

    public function Validarinsertarcombo()
    {
        if ($_POST['idcampo'] == '' ||
            $_POST['idtabla'] == ''
        )
            return false;
        return true;

    }

    public function Validarinsertarconexiones()
    {
        if ($_POST['idtabla'] == '' ||
            $_POST['idrelacion'] == ''
        )
            return false;
        return true;
    }
}

?>
