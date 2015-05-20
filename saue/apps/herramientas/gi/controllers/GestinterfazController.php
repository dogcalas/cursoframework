<?php

/**
 * Componente para gestinar los sistemas.
 *
 * @package SAUXE_v2.3
 * @Copyright UCI
 * @Author Rafael
 * @Version 3.0-0
 */
class GestinterfazController extends ZendExt_Controller_Secure
{

    public function init()
    {
        parent::init();
    }

    public function gestinterfazAction()
    {
        $this->render();
    }

    public function cargarConceptosAction()
    {
        $arr = array();
        $directorio = $_SERVER['DOCUMENT_ROOT'] . "/../apps/herramientas/gi/comun/recursos/xml/conceptos/";
        $dir = opendir($directorio);

        while ($elemento = readdir($dir)) {
            if (eregi("xml", $elemento)) {
                //print_r($elemento);die;
                $conceptos = new SimpleXMLElement($_SERVER['DOCUMENT_ROOT'] . "/../apps/herramientas/gi/comun/recursos/xml/conceptos/" . $elemento, NULL, TRUE);
                $temp['concepto'] = (String)$conceptos->concepto->denominacion;
                $temp['plantilla'] = (String)$conceptos->concepto->plantilla;
                $arr[] = $temp;

            }
        }

        closedir($dir);

        //print_r(json_encode($arr));die;

        echo json_encode($arr);
    }

    public function loadPreviewAction()
    {
        $node = $_GET['node'];
        //	print_r($node);die;
        $temp['name'] = $node;
        $temp['url'] = "../../views/images/" . $node . ".png";
        $temp['size'] = 2323;
        $temp['lastmod'] = 1303810973000;
        $arr[] = $temp;
        $temp = null;


        echo json_encode($arr);
    }

    public function genInterfazAction()
    {
        $con = $this->getRequest()->getPost('concepto');
        $libr = $this->getRequest()->getPost('libreria');
        switch ($libr) {
            case 'ExtJS4':
            {
                $a = new ExtJS4Model();
                echo $a->generarInterfaz($con);
            }
                break;
            case 'ExtJS2':
            {
                $b = new ExtJS2Model();
                echo $b->generarInterfaz($con);
            }
                break;
            case 'Dojo':
            {
                $c = new DojoModel();
                echo $c->generarInterfaz($con);
            }
                break;
            case 'JQuery':
            {
                $d = new JQueryModel();
                echo $d->generarInterfaz($con);
            }
                break;
            default:
                {
                echo "Espera 10 o 20 años";
                }
        }
        /*$nomb=$this->getRequest()->getPost ('nombre');
        $archivo=$nomb.".js";
        $item=$this->getRequest()->getPost ('elem');
        //$nombre=explode(".",$archivo);
        $codigo=$this->getRequest()->getPost ('comp');
        $fp = fopen($archivo, "w");
        fwrite($fp,"var perfil = window.parent.UCID.portal.perfil;". PHP_EOL."UCID.portal.cargarEtiquetas('".$nomb."', function(){cargarInterfaz();});".PHP_EOL."Ext.QuickTips.init();".PHP_EOL."function cargarInterfaz(){".PHP_EOL.$codigo.PHP_EOL."var general= Ext.create('Ext.panel.Panel', { title: '".$nomb."', layout:'border',items:[".$item."]});".PHP_EOL."var vpGestSistema =Ext.create('Ext.Viewport',{layout:'fit',items:general});".PHP_EOL."}");
        fclose($fp);*/

    }


}

?>
