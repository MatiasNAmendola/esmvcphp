<?php
namespace core;

/**
 * Esta clase la han de heredar todos los controladores.
 */
class Controlador extends \core\Clase_Base {	
	
	public function index(array $datos = array()) {
		
		echo __METHOD__." => Respuesta por defecto. Método index sin redefinir en el controlador {$this->datos['controlador_clase']}";
		//print_r($datos);
		
	}
	
	
	/**
	 * Genera un script para incorporar js desde ficheros externos.
	 * <br />Hay que aportar el path y el nombre del fichero.
	 * <br />Ejemplo de invocación: this->js_script_tag('recursos/js/fichero.js');
	 * @param type $path_y_fichero
	 * @return type
	 */
	public function js_script_tag($path_y_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.js$/i', $path_y_fichero))
			$nombre_fichero .= '.js';
		$url_js_fichero = \core\URL::http('')."$path_y_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		if (is_array($datos)) 
			$datos['js'][$js_script_tag] = true;
		else
		return $js_script_tag;
		
	}
	
	
	
	public function js_script_vistas_tag($nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.js$/i', $nombre_fichero))
			$nombre_fichero .= '.js';
		$url_js_fichero = \core\URL::http('')."app/vistas/".\core\Aplicacion::$controlador->datos['controlador_clase']."/$nombre_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		if (is_array($datos)) 
			$datos['js'][$js_script_tag] = true;
		else
		return $js_script_tag;
		
	}
	
	public function js_script_lib_tag($nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.js$/i', $nombre_fichero))
			$nombre_fichero .= '.js';
		$url_js_fichero = \core\URL::http('')."app/lib/js/$nombre_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		if (is_array($datos)) 
			$datos['js'][$js_script_tag] = true;
		else
		return $js_script_tag;
		
	}
	
	
	
	
	
	
	public function css_link_tag($path_y_nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.css$/i', $path_y_nombre_fichero))
			$nombre_fichero .= '.css';
		$url_css_fichero = \core\URL::http('')."$path_y_nombre_fichero";
		$css_link_tag = "<link rel='stylesheet' type='text/css' href='$url_css_fichero' />\n";
		if (is_array($datos)) 
			$datos['css'][$css_link_tag] = true;
		else
			return $css_link_tag;
		
	}
	
	
	
	
	
	
	
	public function css_link_vistas_tag($nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.css$/i', $nombre_fichero))
			$nombre_fichero .= '.css';
		$url_css_fichero = \core\URL::http('')."app/vistas/".\core\Aplicacion::$controlador->datos['controlador_clase']."/$nombre_fichero";
		$css_link_tag = "<link rel='stylesheet' type='text/css' href='$url_css_fichero' />\n";
		if (is_array($datos)) 
			$datos['css'][$css_link_tag] = true;
		else
			return $css_link_tag;
		
	}
	
	
	
	
	
	
	
	
} // Fin de la clase