<?php

class TomarimagenesController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function tomarimagenesAction() {
        $this->render();
    }

    /* ---------Funcion que se encarga obtener las imagenes---------- */

    function imagenwebcamAction() {
        if ($this->ComprobarLibreria()) {
            $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
            $fecha = date('dmYHis');
            $dir = $path . 'imagenes';
            $file = new ZendExt_File($dir);
            $file->mkdir();
            $file->mkfile(file_get_contents('php://input'), $path . $fecha . '.jpg');
            $datos = SegDatosImagenes::cargardatosimg(0, 1)->toArray();
            if ($datos[0]['forma'] == "Rectangulo")
                $forma = 0;
            else
                $forma = 1;
            $formato = $datos[0]['formato'];
            $formato = explode('.', $formato);
            $dirweb = $_SERVER[DOCUMENT_ROOT];
            $dirapps = str_replace('web', 'apps', $dirweb);
            $dir_xml = $dirapps . DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'reconocimientoFacial' . DIRECTORY_SEPARATOR;
            $file_name = $fecha . '.' . $formato[1];
            $rec = new Rec();
            $rec->extraeCaras($dir_xml . "frontalface_alt2.xml", $path . $fecha . '.jpg', $dir . DIRECTORY_SEPARATOR . $file_name, $forma, $datos[0]['brillo'], $datos[0]['contraste'], $datos[0]['ancho'], $datos[0]['alto']);
            $file->rm($path . $fecha . '.jpg');
            echo"success";
        }
        else
            echo"error";
    }

    function descargarAction() {

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'imagenes';
        $file = new ZendExt_File ();
        $file->rm($path . '.zip');
        $recursive = $file->tree($path);
        $zip = new ZipArchive ();
        $zip->open($path . '.zip', ZIPARCHIVE::CREATE);
        foreach ($recursive as $item) {
            $just_local = substr($item, strpos($item, 'imagenes'));
            if (!is_dir($item))
                $zip->addFile($item, $just_local);
        }

        $zip->close();

        $filepath = $path . '.zip';

        $file->rm($path);

        ZendExt_Download :: force_download('imagenes.zip', file_get_contents($filepath));
    }

    function ComprobarLibreria() {
        if (!extension_loaded('reconocimiento')) {

            return 0;
        }
        else
            return 1;
    }

}

?>
