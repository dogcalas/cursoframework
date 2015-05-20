<?php
/**
 * Fichero de configuracion del modulo de gestion del Portal
 * @author Equipo Aquitectura ERP-CUBA, Grupo I+D UCID
 * @version 1.0-0
 */

//Direccion de la carpeta de publicacion
$dir_www = $_SERVER['DOCUMENT_ROOT'];

//Direccion del fichero de configuracion
$dir_config = $dir_www . '/repo/VDesarrollo/config.php';

//Inclusion del Fichero de Configuracion del Marco de Trabajo
include($dir_config);

//Inicializando el include path de php a partir de la variable de configuracion
set_include_path($config['include_path']);

//Personalizacion de la variable $config con las configuraciones especificas del modulo
/*VAN AQUI LAS CONFIGURACIONES ESPECIFICAS DEL MODULO*/ //otraprueba
//Configuracion de la Base de Datos (temporal debe cambiar cuando se reimplemente el sistema de seguridad).
/*$config['bd'] = array(
        'bd' 	   => 'metadatos',
        'gestor'   => 'pgsql',
        'host' 	   => '10.12.163.159',
        'usuario'  => 'postgres',
         'password' => 'postgres',
        'esquema'  => 'public'
    );*/
/*$config['bd'] = array(
        'bd' 	   => 'metadatos',
        'gestor'   => 'pgsql',
        'host' 	   => '10.12.163.159',
        'usuario'  => 'postgres',
         'password' => 'postgres',
        'esquema'  => 'estructura'
    );*/
/*	$config['bd'] = array(
			'bd' 	   => 'erp',
			'gestor'   => 'pgsql',
			'host' 	   => '10.12.171.4',
			'usuario'  => 'erp',	
 			'password' => 'erp2008',
			'esquema'  => 'mod_estructuracomp'
		);

*/
$config['bd'] = array(
    'bd' => 'estructurarafael',
    'gestor' => 'pgsql',
    'host' => 'localhost',
    'usuario' => 'postgres',
    'password' => 'postgres',
    'esquema' => 'mod_estructuracomp'
);
/*	$config['bd'] = array(
			'bd' 	   => 'erpn',
			'gestor'   => 'pgsql',
			'host' 	   => 'localhost',
			'usuario'  => 'postgres',	
 			'password' => 'postgres',
			'esquema'  => 'mod_estructuracomp'
		);
		
		*/

$config['name_module'] = 'estructura';

?>
