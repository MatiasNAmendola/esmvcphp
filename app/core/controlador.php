<?php
namespace core;

class Controlador extends \core\Clase_Base
{	
	public function index(array $datos = array()) {
		
		echo __METHOD__." => Respuesta por defecto. Método index sin redefinir en el controlador {$this->datos['nombre']}";
		//print_r($datos);
		
	}
	
	
	
	
	
	
	public function js_script_tag($nombre_fichero) {
		
		if ( ! preg_match('/\.js$/i', $nombre_fichero))
			$nombre_fichero .= '.js';
		$url_js_fichero = \core\URL::http('')."app/vistas/".\core\Aplicacion::$controlador->datos['nombre']."/$nombre_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		return $js_script_tag;
		
	}
	
	public function js_script_lib_tag($nombre_fichero) {
		
		if ( ! preg_match('/\.js$/i', $nombre_fichero))
			$nombre_fichero .= '.js';
		$url_js_fichero = \core\URL::http('')."app/lib/js/$nombre_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		return $js_script_tag;
		
	}
	
	
	
	public function css_link_tag($nombre_fichero) {
		
		if ( ! preg_match('/\.css$/i', $nombre_fichero))
			$nombre_fichero .= '.css';
		$url_css_fichero = \core\URL::http('')."app/vistas/".\core\Aplicacion::$controlador->datos['nombre']."/$nombre_fichero";
		$css_link_tag = "<link rel='stylesheet' type='text/css' href='$url_css_fichero' />\n";
		return $css_link_tag;
		
	}
	
	
} // Fin de la clase