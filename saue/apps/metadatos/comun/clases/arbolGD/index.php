<?php

require("./clases/arbol_class.php");
require("./clases/nodo_class.php");
require("./clases/grafica_class.php");
$hijos = array();
$hijos[] = new CNodo(1, false, "nodo1");
$hijos[] = new CNodo(15, false, "nodo15");
$hijos[] = new CNodo(16, 15, "nodo16");
$hijos[] = new CNodo(2, 1, "nodo2");
$hijos[] = new CNodo(3, 1, "nodo3");
$hijos[] = new CNodo(4, 2, "nodo4");
$hijos[] = new CNodo(5, 8, "nodo5");
$hijos[] = new CNodo(6, 8, "nodo6");
$hijos[] = new CNodo(7, 8, "nodo7");
$hijos[] = new CNodo(8, 1, "nodo8");
$hijos[] = new CNodo(9, 1, "nodo9");
$hijos[] = new CNodo(10, 1, "nodo10");
$hijos[] = new CNodo(11, 6, "nodo11");
$hijos[] = new CNodo(12, 2, "nodo12");
$hijos[] = new CNodo(13, 2, "nodo13");
$hijos[] = new CNodo(14, 11, "nodo14");
//echo serialize($hijos)."<br><br>";
//print_r($hijos);
$cadena = '$pp=Array (
0 => new CNodo(  1 ,false, "nodo1" ), 
1 => new CNodo (  15, 1, "nodo15" ), 
2 => new CNodo (  16,15, "nodo16" ) 
);';
//echo $cadena;
$ob = eval($cadena);

//print_r($pp)
$arbol = new CArbol($hijos);
$dib = new CDibujaArbol();
$dib->dibujarArbol($arbol);
/*
print_r( $arbol->maximoNodosNivel() );
*/
//print_r($arbol->buscarHijos(2));
?>