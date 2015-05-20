<?php

/**
 * Componente para gestinar los sistemas.
 *
 * @package SAUXE_v2.3
 * @Copyright UCI
 * @Author Rafael
 * @Version 3.0-0
 */
class DefconceptoController extends ZendExt_Controller_Secure
{

    public function init()
    {
        parent::init();
    }

    public function defconceptoAction()
    {
        $this->render();
    }

    public function cargarimagenAction()
    {
        $arr = array();
        $directorio = "./views/images/";
        $dir = opendir($directorio);
        while ($elemento = readdir($dir)) {
            if (eregi("gif", $elemento) || eregi("jpg", $elemento) || eregi("png", $elemento) || eregi("jpeg", $elemento)) {
                $name = explode(".", $elemento);
                $temp['name'] = $name[0];
                $temp['url'] = "../../views/images/" . $elemento;
                $temp['size'] = 2323;
                $temp['lastmod'] = 1303810973000;
                $arr[] = $temp;
                $temp = null;

            }
        }
        closedir($dir);


        echo json_encode($arr);
    }

    public function loadPreviewAction()
    {
        $node = $_GET['node'];
        //	print_r($node);die;

        $name = explode(".", $node);
        $temp['name'] = $name[0];
        $temp['url'] = "../../views/images/" . $node;
        $temp['size'] = 2323;
        $temp['lastmod'] = 1303810973000;
        $arr[] = $temp;
        $temp = null;


        echo json_encode($arr);
    }

    public function crearConceptoAction()
    {
        $i = 0;
        //$atr=array();
        // $ccc=array();
        $atr = json_decode($this->_request->getPost('atributo'));
        //print_r($atr);die;
        $ccc = json_decode($this->_request->getPost('componentes'));
        $nombconc = $this->getRequest()->getPost('concepto');
        $pl = $this->getRequest()->getPost('plantilla');
        $xml = new DomDocument("1.0", "UTF-8");
        $raiz = $xml->createElement("conceptos");
        $raiz = $xml->appendChild($raiz);
        $concepto = $xml->createElement("concepto");
        $concepto = $raiz->appendChild($concepto);
        $nc = $xml->createElement("denominacion", $nombconc);
        $nc = $concepto->appendChild($nc);
        $plantilla = $xml->createElement("plantilla", $pl);
        $plantilla = $concepto->appendChild($plantilla);
        if ($pl == "CRUD-Simple") {
            $urlg = $this->getRequest()->getPost('urlG');
            $ug = $xml->createElement("urlG", $urlg);
            $ug = $concepto->appendChild($ug);
        } else {
            $urlg = $this->getRequest()->getPost('urlG');
            $urla = $this->getRequest()->getPost('urlA');
            $ug = $xml->createElement("urlG", $urlg);
            $ug = $concepto->appendChild($ug);
            $ua = $xml->createElement("urlA", $urla);
            $ua = $concepto->appendChild($ua);
        }
        $temp = $xml->createElement("atributos");
        $temp = $concepto->appendChild($temp);
        foreach ($atr as $a) {
            $at = $xml->createElement("atributo");
            $at = $temp->appendChild($at);
            $atributo = $xml->createElement("denominacion", $a);
            $atributo = $at->appendChild($atributo);
            $c = $xml->createElement("componente", $ccc[$i]);
            $c = $at->appendChild($c);
            $i = $i + 1;
        }
        $xml->formatOut = true;
        $strings_xml = $xml->saveXML();
        if ($xml->save($_SERVER['DOCUMENT_ROOT'] . "/../apps/herramientas/gi/comun/recursos/xml/conceptos/" . $nombconc . ".xml")) {
            echo "Terminó de crear el concepto.";
        } else {
            echo "No pudimos guardar el concepto.";
        }

    }

}

?>
