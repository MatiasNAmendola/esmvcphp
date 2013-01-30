<?php
namespace core;

/**
 * Esta clase genera etiquetas html
*
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class HTML_Tag extends \core\Clase_Base {

	protected static $depuracion=false;

	public function __construct() {
		parent::__construct();
	}

	public static function span_error($input_id, array $datos) {
		
		if (isset($datos['errores'][$input_id]))
			return "<span class='input_error' style='color: red;'>{$datos['errores'][$input_id]}</span><br />"; 
			
	}



} // Fin de la clase