<?php
namespace core;

/**
 * Se encarga de enviar la respuesta que se genera en la aplicación.
 * La respuesta será por defecto del tipo MIME 'text/html';
 */
class Respuesta extends \core\Clase_Base {
	/**
	 *
	 * @var string Tipo MIME utilizado por defecto.
	 */
	private static $tipo_mime = null;
		
	/**
	 * Cambia el tipo MIME de la respuesta HTTP que define el contenido de la línea Content-Type del HEADER.
	 * Por defeto las respuestas se envía con el tipo 'text/plain'.
	 * 
	 * @param type $tipo_mime
	 * @throws \Exception
	 */
	public static function cambiar_tipo_mime($tipo_mime) {
		
		if (\core\Array_Datos::contiene($tipo_mime, \core\Configuracion::$tipos_mime_reconocidos))
			self::$tipo_mime = $tipo_mime;
		else {
			throw new \Exception(__METHOD__." Error: tipo mime <b>$tipo_mime</b> no válido, solo se admite uno de los siguientes:".  implode(' , ', \core\Configuracion::$tipos_mime_reconocidos));
		}
	}
	
	
	
	/**
	 * Envia la respuesta HTTP, compuesta de HEADER y BODY
	 * Si el HEADER ya se ha enviado lanza un warning.
	 * 
	 * @param array $datos
	 * @param type $plantilla
	 * @throws \Exception
	 */
	public static function enviar(array $datos=array(), $plantilla = null) {
		// Enviar HEAD
		$fichero = '';
		$linea = '';
		if ( ! headers_sent($fichero, $linea)) { // Enviamos en encabezado HTTP
			if ( ! self::$tipo_mime)
				self::$tipo_mime = \core\Configuracion::$tipo_mime_por_defecto;
			header("HTTP/1.1 200 OK");
			header("Content-Type: ".self::$tipo_mime);
			if (self::$tipo_mime == 'application/excel') {
				header("Content-Disposition: attachment;filename=libro.xls");
			}
		}
		else { // El encabezado HTTP ya se ha enviado
			echo __METHOD__." Warning: El encabezado php se originó en el fichero <b>$fichero</b> , en la línea <b>$linea</b>.<br />";
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