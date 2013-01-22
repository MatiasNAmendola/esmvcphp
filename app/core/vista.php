<?php
namespace core;

class Vista extends \core\Clase_Base
{
	public static function generar($nombre , array $datos = array(), $buffer = true)
	{
		$fichero_vista = strtolower(PATH_APP."vistas/".\core\Aplicacion::$controlador->datos['nombre']."/$nombre.php");
		if ( ! file_exists($fichero_vista))
			throw new \Exception(__METHOD__." Error: no existe el fichero $fichero_vista .");
		if ($buffer)
			ob_start ();
		include $fichero_vista;
		if ($buffer)
		{
			$buffer_contenido = ob_get_contents();
			ob_end_clean();
			return $buffer_contenido;
		}
	}
			
}