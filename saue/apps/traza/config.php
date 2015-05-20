<?php
/**
 * Fichero de configuracion del modulo de gestion del Portal
 * @author Equipo Aquitectura ERP-CUBA, Grupo I+D UCID
 * @version 1.0-0
 */
	//Direccion de la servidora
	$dir_index = $_SERVER['SCRIPT_FILENAME'];
	//Direccion del fichero de configuracion
	$dir_config = substr($dir_index, 0, strrpos($dir_index, 'aplicaciones')).'config.php';	
	//Inclusion del Fichero de Configuracion del Marco de Trabajo
	include($dir_config);
	//Configuracion de los xml
	$config ['xml']['modules'] = substr($dir_index, 0, strrpos($dir_index, 'erp')) . 'erp/comun/recursos/xml/';