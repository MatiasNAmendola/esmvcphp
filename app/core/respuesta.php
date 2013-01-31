<?php
namespace core;


class Respuesta extends \core\Clase_Base
{
	
	public static function enviar(array $datos=array(), $plantilla = null)
	{
		if ( ! $plantilla)
			$plantilla = \core\Configuracion::$plantilla_por_defecto;
		$fichero_plantilla = strtolower(PATH_APP."/vistas/$plantilla.php");
		if ( ! file_exists($fichero_plantilla))
			throw new \Exception(__METHOD__." Error: no existe el fichero $fichero_plantilla .");
		include $fichero_plantilla;
	}
	
	
	
	
}