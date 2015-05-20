<?php
	$php_ext = array ( );
	$php_ext ['pdo'] 		= 'Debe instalar la extensi&oacute;n PDO de php para el acceso a bases de datos [pdo].';
	$php_ext ['pgsql'] 		= 'Debe instalar la extensi&oacute;n de php para el acceso a bases de datos PostgreSQL [pgsql].';
	$php_ext ['pdo_pgsql'] 	= 'Debe instalar la extensi&oacute;n PDO para PostgreSQL de php para el acceso a bases de datos PostgreSQL [pdo_pgsql].';
	$php_ext ['soap'] 		= 'Debe instalar la extensi&oacute;n SOAP de php para el uso de servicios web [soap].';
	$php_ext ['gd'] 		= 'Debe instalar la extensi&oacute;n GD2 de php para el trabajo con imagenes [gd2 ó gd].';
	$php_ext ['bcmath'] 	= 'Debe instalar la extensi&oacute;n BCMATH de php para el trabajo con números de gran tamaño [bcmath].';
	$php_ext ['simplexml'] 	= 'Debe instalar la extensi&oacute;n SimpleXML de php para el trabajo con xml [simplexml].';
	$php_ext ['json'] 		= 'Debe instalar la extensi&oacute;n JSON de php para el trabajo con json [json].';
	$php_ext ['xsl'] 		= 'Debe instalar la extensi&oacute;n XSL de php para el trabajo con xslt [xsl].';
	$php_ext ['ldap'] 		= 'Debe instalar la extensi&oacute;n LDAP de php para el trabajo con el protocolo ldap [LDAP].';
	$php_ext ['openssl'] 		= 'Debe instalar la extensi&oacute;n OPENSSL de php para el trabajo con m&eacute;todos cifrados [OPENSSL].';
	//$php_ext ['mycrypt'] 	= 'Debe instalar la extensi&oacute;n MYCRYPT de php para el trabajo con m&eacute;todos cifrados [MYCRYPT].';

	function showMsg ($msg_error) {
		echo '<pre><b>Fatal error</b>: ' . $msg_error;
	}
	
	$configured = true;
	foreach ( $php_ext as $ext => $msg_error ) {
		if ( !extension_loaded ( $ext ) ) {
			showMsg($msg_error);
			$configured = false;
		}
	}
	
	if (!$configured) {
		exit();
	}
	
	//$dir_rel_mt = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], 'index.php'));          
	//$direccion = $_SERVER['DOCUMENT_ROOT'].$dir_rel_mt.'web/instalador';
	if(is_dir($direccion)) {
		//eliminarDir($direccion);
		header('Location: portal/');
	}
	else header('Location: portal/');
	
	function eliminarDir($carpeta) {
		foreach(glob($carpeta."/*") as $archivos_carpeta) {
			if(is_dir($archivos_carpeta))
				eliminarDir($archivos_carpeta);
			else 
				unlink($archivos_carpeta);
		}
		rmdir($carpeta);
	}
