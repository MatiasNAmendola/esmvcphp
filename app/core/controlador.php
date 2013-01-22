<?php
namespace core;

class Controlador extends \core\Clase_Base
{	
	public function index(array $datos = array())
	{
		echo __METHOD__." => Respuesta por defecto. Método index sin redefinir en el controlador {$this->datos['nombre']}";
		print_r($datos);
	}
	
} // Fin de la clase