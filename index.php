<?php

// Definiciones constantes
define("DS", DIRECTORY_SEPARATOR);
define("PATH_APP", __DIR__.DS."app".DS ); // Finaliza en DS

define('TITULO', 'my MVC bd');

// Preparar el autocargador de clases.
// Este y el contenido en \core\Autoloader() serán los únicos require/include de toda la aplicación
require PATH_APP.'core/autoloader.php'; 
$autoloader = new \core\Autoloader();

// Cargamos la aplicación
$aplicacion = new \core\Aplicacion();

?>