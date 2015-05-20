<?php

/*
 * Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */

class ReconocimientoController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function reconocimientoAction() {
        $this->render();
    }

    function imagenwebcamAction() {
        $result = file_put_contents('extraida.jpg', file_get_contents('php://input'));
        Extrae_caras("frontalface_alt2.xml", "extraida.jpg", "extraida.png", 1, 100, 100);
        unlink('extraida.jpg');
    }

    function reconocerAction() {
        $nombreusuario = $this->_request->getPost('usuario');
        If ($nombreusuario == "")
         echo"{'codMsg':3,'mensaje': 'Debe especificar el usuario'.}";
        else {
            $idusuario = SegUsuario::obtenerIdUsuario($nombreusuario);
            if ($idusuario == "")
            echo"{'codMsg':3,'mensaje': 'El usuario no existe'.}";
            else {
                $dir = '.reconocimiento';
                $arregloimg = DatImagenesUsuario::cargarimagenes();
                mkdir($dir);
                foreach ($arregloimg as $value) {
                    $imagen = base64_decode(stream_get_contents($value['imagen']));
                    file_put_contents($dir . '/' . $value['idusuario'] . "_" . $value['nombreimage'], $imagen);
                }
                $files = array();
                $arrayid = array();
                $aux = array();
                $directory = opendir($dir);
                while ($item = readdir($directory)) {
                    if (($item != ".") && ($item != "..")) {
                        $files[] = $dir . "/" . $item;
                        $aux = explode("_", $item);
                        $arrayid[] = $aux[0];
                    }
                }
                $numFiles = count($files);
                $grupos = array();
                $arreglo = pca("extraida.png", "facedata.xml");
                unlink('extraida.png');
                $key1 = '';
                foreach ($arreglo as $key => $value) {
                    if ($value != $key1)
                        $key1 = $value;
                    $grupos[$key1] = 0;
                    foreach ($arreglo as $key2 => $value1) {
                        if ($key1 == $value1) {
                            $grupos[$key1]+=1;
                        }
                    }
                }
                $cant = 0;
                $mayor = 0;
                foreach ($grupos as $key => $value) {
                    if ($cant < $value) {
                        $mayor = $key;
                        $cant = $value;
                    }
                }
                $d = dir($dir . "/");
                while ($name = $d->read()) {
                    if (($name != ".") && ($name != "..")) {
                        unlink($dir . "/" . $name);
                    }
                }
                $d->close();
                rmdir($dir);
                if ($mayor == $idusuario)
                    $this->showMessage('Reconocido');
                else
                    $this->showMessage('No Reconocido');
            }
        }
    }

}

?>
