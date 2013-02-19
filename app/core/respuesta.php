<?php
namespace core;


class Respuesta extends \core\Clase_Base
{
	private static $tipo_mime = 'text/html';
	
	
	public static function cambiar_tipo_mime($tipo_mime) {
		
		self::$tipo_mime = $tipo_mime;
		
	}
	
	public static function enviar(array $datos=array(), $plantilla = null)
	{
		// Enviar HEAD
		if ( ! headers_sent()) { // Enviamos en encabezado HTTP
			header("HTTP/1.1 200 OK");
			header("Content-Type: ".self::$tipo_mime);
			if (self::$tipo_mime == 'text/xls');
				header("Content-Disposition: attachment;filename=libro.xls");
		}
		
		// Enviar BODY
		if ( ! $plantilla)
			$plantilla = \core\Configuracion::$plantilla_por_defecto;
		$fichero_plantilla = strtolower(PATH_APP."/vistas/$plantilla.php");
		if ( ! file_exists($fichero_plantilla))
			throw new \Exception(__METHOD__." Error: no existe el fichero $fichero_plantilla .");
		include $fichero_plantilla;
	}
	
	
	
	
}