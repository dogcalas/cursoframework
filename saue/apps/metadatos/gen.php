<?php
$include = '../../../comun/frameworks/Doctrine/Doctrine.php';
include($include);
spl_autoload_register(array('Doctrine', 'autoload'));

$conn = Doctrine_Manager::connection('pgsql://postgres:postgres@localhost/metadatos');
Doctrine::generateModelsFromDb('./models/domain/');
?>