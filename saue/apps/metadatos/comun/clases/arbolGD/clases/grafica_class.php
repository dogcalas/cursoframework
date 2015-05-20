<?php


class CDibujaArbol
{
    var $anchoNodo;
    var $alturaNodo;
    var $separacion;

    function CDibujaArbol()
    {
        $this->anchoNodo = 150;
        $this->alturaNodo = 50;
        $this->separacion = 60;
    }

    /**
     * Enter description here...
     *
     * @param CArbol $arbol
     */

    function dibujarArbol($arbol)
    {
        $pintados = array();
        $arbolPorNivel = array();
        $datosGenerales = $arbol->maximoNodosNivel();
        $pAltura = $datosGenerales['altura'];
        $pAncho = $datosGenerales['nodos'];
        $pnivelAncho = $datosGenerales['prof'];

        header('Content-type: image/jpeg');
        $cantidadPuntos = count($valores);
        $x_imagen = ($pAncho) * ($this->anchoNodo + $this->separacion);
        $y_imagen = $pAltura * ($this->alturaNodo + $this->separacion);


        $imagen = imagecreate($x_imagen, $y_imagen);

        //fondo de la imagen
        imagefill($imagen, 0, 0, imagecolorallocate($imagen, 240, 240, 240));

        $negro = imagecolorallocate($imagen, 0, 0, 0); //
        $gris = imagecolorallocate($imagen, 120, 20, 20); //
        $blanco = imagecolorallocate($imagen, 255, 255, 255); //

        $offsetDibujoY = 20;
        $numeroNodo = 1;
        for ($i = 1; $i <= $pAltura; $i++) {

            $hijosNivel = $arbol->hijosDeprofundidadOrdenado($i);
            //$inicioDibujoX	= ($pAncho - count($hijosNivel)  )*($this->anchoNodo+$this->separacion);
            $cantHijosNivel = 0;
            foreach ($hijosNivel as $grupoHijoPadre) {
                $cantHijosNivel += count($grupoHijoPadre);
            }
            $porcientoHijo = ($x_imagen) / ($cantHijosNivel + 1);
            $inicioDibujoX = $porcientoHijo - 70;
            foreach ($hijosNivel as $grupoHijoPadre) {
                foreach ($grupoHijoPadre as $hijo) {
                    $arbolPorNivel[$i][] = $hijo;
                    $coloNivel = imagecolorallocate($imagen, rand(0, 255), rand(0, 255), rand(0, 255));

                    $pintados[$hijo->id] = array('x' => $inicioDibujoX + ($this->anchoNodo / 2), 'y' => $offsetDibujoY + $this->alturaNodo, 'color' => $coloNivel);
                    if ($i != 1) {

                        imageline($imagen, $inicioDibujoX + ($this->anchoNodo / 2), $offsetDibujoY - 10, $inicioDibujoX + ($this->anchoNodo / 2), $offsetDibujoY - 30, $pintados[$hijo->idPadre]['color']);
                        imageline($imagen, $inicioDibujoX + ($this->anchoNodo / 2), $offsetDibujoY, $inicioDibujoX + ($this->anchoNodo / 2) + 7, $offsetDibujoY - 10, $pintados[$hijo->idPadre]['color']);
                        imageline($imagen, $inicioDibujoX + ($this->anchoNodo / 2), $offsetDibujoY, $inicioDibujoX + ($this->anchoNodo / 2) - 7, $offsetDibujoY - 10, $pintados[$hijo->idPadre]['color']);
                        imageline($imagen, $inicioDibujoX + ($this->anchoNodo / 2) - 7, $offsetDibujoY - 10, $inicioDibujoX + ($this->anchoNodo / 2) + 7, $offsetDibujoY - 10, $pintados[$hijo->idPadre]['color']);


                        imageline($imagen, $inicioDibujoX + ($this->anchoNodo / 2), $offsetDibujoY - 30, $pintados[$hijo->idPadre]['x'], $offsetDibujoY - 30, $pintados[$hijo->idPadre]['color']);
                        imageline($imagen, $pintados[$hijo->idPadre]['x'], $offsetDibujoY - 30, $pintados[$hijo->idPadre]['x'], $pintados[$hijo->idPadre]['y'], $pintados[$hijo->idPadre]['color']);
                        //imageline($imagen,$inicioDibujoX+($this->anchoNodo/2),$offsetDibujoY,$pintados[$hijo->idPadre]['x'],$pintados[$hijo->idPadre]['y'],$negro);
                    }


                    imagestring($imagen, 6, $inicioDibujoX + ($this->anchoNodo / 2) - (strlen($hijo->infomacion) * 4), $offsetDibujoY + ($this->alturaNodo / 2) - 8, trim($hijo->infomacion), $coloNivel);

                    imagestring($imagen, 6, $inicioDibujoX + $this->anchoNodo - 10 - (strlen($numeroNodo) * 4), $offsetDibujoY + $this->alturaNodo - 15, $numeroNodo, $negro);

                    $colorNopdo = isset($pintados[$hijo->idPadre]['color']) ? $pintados[$hijo->idPadre]['color'] : $negro;

                    imagerectangle($imagen, $inicioDibujoX, $offsetDibujoY, $inicioDibujoX + $this->anchoNodo, $offsetDibujoY + $this->alturaNodo, $colorNopdo);
                    $inicioDibujoX += $porcientoHijo;

                    $numeroNodo++;

                }
            }
            $offsetDibujoY += $this->alturaNodo + $this->separacion + 20;
            //	echo '<pre>';
            //	print_r($hijosNivel);
        }

        imagejpeg($imagen);
        imagedestroy($imagen);
    }
}


?>