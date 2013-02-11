<?php
namespace controladores;

class inicio extends \core\Controlador
{
	/**
	 * 
	 * @param array $datos
	 */
	public function index(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	public function logueado(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	public function falta_confirmar(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	
} // Fin de la clase