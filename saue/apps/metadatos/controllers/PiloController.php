<?php


class PiloController extends ZendExt_Controller_Secure
{
    function init()
    {
        parent::init();
    }

    function probarAction()
    {
        $probar = new EstructuraopModel();
        print_r($probar->buscarDatestructuraop(10, 0));
    }

    function probarcargosAction()
    {
        $probar = new DatcargoModel();

        echo '<pre>';
        print_r($probar->getCargo(8));
    }

    function insertarAction()
    {
        $probar = new DominioModel();
        $probar->insertar('dominio 1');
    }

    function eliminarAction()
    {
        $probar = new DominioModel();
        $probar->eliminarDominio(1);
    }

    function buscarAction()
    {

        $probar = new DominioModel();
        $a = $probar->buscarDominios(10, 0);
        echo '<pre>';
        print_r($a);

    }

    function anadirAction()
    {
        $probar = new DominioModel();
        $probar->anadirEstructura(3, $_GET['e']);

        $a = $probar->buscarDominios(10, 0);
        echo '<pre>';
        print_r($a);

    }


    function extraerAction()
    {
        $probar = new DominioModel();
        $probar->extraerEstructura(2, $_GET['e']);

        $a = $probar->buscarDominios(10, 0);
        echo '<pre>';
        print_r($a);

    }

    function estaAction()
    {
        $probar = new DominioModel();
        if ($probar->perteneceEstructura(2, $_GET['e']))
            echo 'esta';
        else
            echo 'no esta';

        $a = $probar->buscarDominios(10, 0);
        echo '<pre>';
        print_r($a);


    }

    function orAction()
    {
        ;
        $a = 9; //001
        $b = 2;
        $_GET['b']; //110
        echo $a;
        echo '<br>$a= ' . decbin($a) . ' $b=' . decbin($b) . ' <br>';
        echo "^ - " . ($a ^ $b) . " XOR se activan los bits que estan en uno o en otro pero no en los dos en las dos " . decbin(($a ^ $b)) . " <br>";

        echo "| - " . ($a | $b) . "   O se activan los bits que estan activos en una o la otra " . decbin(($a | $b)) . " <br>";
        echo "& - " . ($a & $b) . " Y se activan los bits que estan activos en las dos " . decbin(($a & $b)) . "<br>";


    }

    function aaaAction()
    {
        $modelTabla = new TablaModel();
        $campos = $modelTabla->buscarTablasCamposValores(7, $idfila);
        echo '<pre>';
        print_r($campos);
    }

    function listarAction()
    {
        $probar = new DominioModel();

        $a = $probar->BuscarEstructuras(2);

        echo '<pre>';
        print_r($a);


    }

    function dominiosAction()
    {
        $probar = new DominioModel();

        $a = $probar->dominiosEstructura($_GET['e']);

        echo '<pre>';
        print_r($a);


    }


    function datoscAction()
    {
        $probar = new ICargos();
        $p = new DatcargoModel();
        echo '<pre>';
        print_r($p->getCargo(55));


    }

    function probercargoAction()
    {
        $probar = new DatcargoModel();
        echo '<pre>';
        print_r($probar->buscarCargoConPuestos(55));
    }


    function probercargo1Action()
    {
        $probar = new ICargos();
        echo '<pre>';
        print_r($probar->buscarCargosPorClas(38, 1));
    }

    function probarestAction()
    {
        $p = new IEstructura();
        $arr = $p->mostrarCamposEstructura(144);
        echo '<pre>';
        print_r($arr);
    }

    function comAction()
    {
        $dir_rel_app = Zend_Registry::get('config')->uri_aplication;
        $dir_controller = $dir_rel_app . '/metadatos/index.php/estructura/interfazestructura';
        header("Location: $dir_controller");
    }

    function hijAction()
    {
        $obj = new EstructuraModel();
        if ($obj->tieneHijos(8030099))
            echo("Tiene hijos");
        else
            echo("No tiene hijos");


    }
}

?>