<?php
namespace core\sgbd;

/**
 * Esta clase debe extender la clase en la que se implementa la conexión
 * con el SGBD elegido para la aplicación.
 * 
 * En este caso es mysqli. Si hubiese sido db2 pues se hubira extendido la clase que contuviera la implementación para db2.
 * 
 * Después, esta clase \core\sgbd\bd se extenderá cuando se creen las
 * clases específicas para implementar la manipulación y recuperación de datos en cada tabla, clases que estarán en la carpeta app\datos\nombre_tabla.php
 */
class bd extends \core\sgbd\mysqli {
	
}