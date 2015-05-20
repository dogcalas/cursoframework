<?php

require_once 'jsmin-1.1.1.php';

$files = array("iconcombo.js","gestionarcampos.js","eav2.js");

// comprobar ficheros
foreach($files as $file){
		if(stristr($file, "..")){
			$errors[] = "Hay error en  ".$file;
			continue;
		}	
		if(!file_exists($file)){
			$errors[] = "Fichero ".$file." no existe.";
			continue;
		}
	
		if(!is_readable($file)){
			$errors[] = "Fichero ".$file." no es accesible.";
			continue;
		}
	}
	reset($files);

	if($errors){
			foreach($errors as $error)
				echo $error."<br>\n";
			return false;
		}

// Load files
foreach($files as $file){
	$file_content = file_get_contents($file);
	if(!$file_content){
		$errors[] = "Error leyendo fichero ".$file;
	}
	else{
		$retr .= $file_content;
	}
}

if($errors){
	foreach($errors as $error)
		echo $error."<br>\n";
	return false;
}
echo JSMin::minify($retr);
?>