<?php

// Definiciones constantes
define("DS", DIRECTORY_SEPARATOR);

define("PATH_ROOT", __DIR__.DS ); // Finaliza en DS

define("PATH_APP", __DIR__.DS."app".DS ); // Finaliza en DS

define('TITULO', 'my first MVC');

// Preparar el autocargador de clases.
// Este y el contenido en \core\Autoloader() serán los únicos require/include de toda la aplicación
require PATH_APP.'core/autoloader.php'; 
$autoloader = new \core\Autoloader();
//spl_autoload_register(array('\core\Autoloader', 'autoload'));

// Cargamos la aplicación
$aplicacion = new \core\Aplicacion();

// Fin de index.php