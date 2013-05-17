<?php
namespace controladores;

class conexiones extends \core\Controlador {
	
	public function listado(array $datos = array()) {
	
		if (isset($_REQUEST['login']) && strlen($_REQUEST['login']) ) {
			$datos['login'] = $_REQUEST['login'];
		}
		
		$datos['filas'] = \datos\conexiones::recuperar($datos);
		
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}
	
}

