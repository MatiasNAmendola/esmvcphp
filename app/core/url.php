<?php
namespace core;


class URL {
	
	/**
	 * Retorna una URL partiendo de la que recibió la petición para ejecutar el index.php.
	 * <br />La url no contiene el nombre del fichero index.php
	 * <br />Ejemplo de URL generada: http://www.servidor.com/?query_string
	 * @param string $query_string
	 * @return string
	 */
	public static function http($query_string = '') {
		
		$carpeta = str_replace('index.php', '',$_SERVER['SCRIPT_NAME']);
		return "http://{$_SERVER['HTTP_HOST']}$carpeta$query_string";
		
	}
	
	
	/**
	 * Retorna una URL que requiere protocolo https partiendo de la que recibió la petición para ejecutar el index.php.
	 * <br />La url no contiene el nombre del fichero index.php
	 * <br />Ejemplo de URL generada: https://www.servidor.com/?query_string
	 * @param string $query_string
	 * @return string
	 */
	public static function https($query_string = '') {
		
		$carpeta = str_replace('index.php', '',$_SERVER['SCRIPT_NAME']);
		return "https://{$_SERVER['HTTP_HOST']}$carpeta$query_string";
		
	}
	
	
}