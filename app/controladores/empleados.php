<?php
namespace controladores;

class empleados extends \core\Clase_Base {
	
	
	
	public function validar_form_insertar(array $datos = array()) {
		
		/*
		$datos['values'] = array();
		$datos['errores'] = array();
		
		// CGI
		
		// login
		$input_name = 'login';
		$datos['values'][$input_name] = (isset($_POST[$input_name]) ? $_POST[$input_name] : null); // null    ''   'cadena'
		// comprobar Requerido
		if (! is_string($datos['values'][$input_name]) or ! strlen($datos['values'][$input_name]))
			$datos['errores'][$input_name] = "Debes introducir un valor";
		// comprobar patrón de login
		if (is_string($datos['values'][$input_name]) && strlen($datos['values'][$input_name])) {
			// Tengo una cadena de longitud 1 o más y deb cumplir el patrón
			$patro_login = "/^[a-z]{1}[a-z\d]{5,19}$/i";
			if ( ! preg_match($patro_login, $datos['values'][$input_name]))
				$datos['errores'][$input_name] = "El login no cumple el patrón.";	
		}
		
		// nombre
		$input_name = 'nombre';
		$datos['values'][$input_name] = (isset($_POST[$input_name]) ? $_POST[$input_name] : ''); // null    ''   'cadena'
		// comprobar Requerido
		if (! is_string($datos['values'][$input_name]) or ! strlen($datos['values'][$input_name]))
			$datos['errores'][$input_name] = "Debes introducir un valor";
		// comprobar patrón de nombre
		if (is_string($datos['values'][$input_name]) && strlen($datos['values'][$input_name])) {
			// Tengo una cadena de longitud 1 o más Cumple el patrón
			$patro_login = "/^[a-z]\s{1,}$/i";
			if ( ! preg_match($patro_login, $datos['values'][$input_name]))
				$datos['errores'][$input_name] = "El nombre no cumplen el patrón.";
		}
		
		// Apellidos
		$input_name = 'apellidos';
		$datos['values'][$input_name] = (isset($_POST[$input_name]) ? $_POST[$input_name] : ''); // null    ''   'cadena'
		// comprobar patrón de apellidos
		if (is_string($datos['values'][$input_name]) && strlen($datos['values'][$input_name])) {
			// TEngo una cadena de longitud 1 o más Cumple el patrón
			$patro_login = "/^[a-z]\s{1,}$/i";
			if ( ! preg_match($patro_login, $datos['values'][$input_name]))
				$datos['errores'][$input_name] = "Los apellidos no cumplen el patrón.";	
		}
		
		// En este punto tenemos cargados			
		// $datos['values']['login']
		// $datos['values']['nombre']
		// $datos['values']['apellidos']
		// $datos['errores']['input_name'] Si hay error
		 
		if (count($datos['errores']) { // Si hay errores se reenvía el formulario para que lo corrija el cliente
			$this->cargar_controlador('empleados', 'form_login', $datos);
		}
		else {
			if ($resultado = \datos\empleados::insert($datos['values'], 'empleados'))
				$datos['alerta'] = "Los se han insertado correctamente.";
			else
				$datos['alerta'] = "Los se han no se han insertado: errer bd";
			$this->cargar_controlador('empleados', 'index', $datos);
		}
		 
		// Todo lo incluido en los comentarios es equivalente a los siguiente:
		*/
		
		$validaciones = array(
			"login" => "errores_requerido && errores_login"
			,"nombre" => "errores_requerido && errores_nombre"
			,"apellidos" => "errores_nombre"
		);

		if (\core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$this->cargar_controlador('empleados', 'form_login', $datos);
		}
		else {
			if ($resultado = \datos\empleados::insert($datos['values'], 'empleados'))
				$datos['alerta'] = "Los se han insertado correctamente.";
			else
				$datos['alerta'] = "Los se han no se han insertado: errer bd";
			$this->cargar_controlador('empleados', 'index', $datos);
		}
		
	}
	
}
		