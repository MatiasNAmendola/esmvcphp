<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Clase_Base
{
	/**
	 * Contenedor de datos para cualquier clase, en especial para los controladores.
	 * @var array 
	 */
	public $datos = array(); 
	
	public function cargar_controlador($controlador, $metodo, array $datos = array())
	{
		if ( ! $controlador )
			$controlador = strtolower (\core\Configuracion::$controlador_por_defecto);
		if ( ! $metodo )
			$metodo = \core\Configuracion::$metodo_por_defecto;
		$metodo = strtolower($metodo);
		
		// Comprobamos que el usuario tiene permisos. Si no los tiene se redirige hacia otro controlador.
		
		if (\core\Permisos::comprobar(\core\Usuario::$login, $controlador, $metodo) === false ) {
			if (\core\Usuario::$login == 'anonimo') {
				$controlador = 'usuarios';
				$metodo = 'form_login';
			}
			else {
				$datos['mensaje'] = "No tienes permisos para esta opción [$controlador][$metodo].";
				$controlador = 'mensajes';
				$metodo = 'index';
			}
		}
		elseif (\core\Permisos::comprobar(\core\Usuario::$login, $controlador, $metodo) === null ) {
				$datos['mensaje'] = "Permiso no definido el la lista ACL en \core\Permisos. No está [$controlador][*] ni [$controlador][$metodo].";
				$controlador = 'mensajes';
				$metodo = 'index';
		}
		
		
		
		$fichero_controlador = strtolower(PATH_APP."controladores/$controlador.php");
		$controlador_clase = strtolower("\\controladores\\$controlador");
		if (file_exists($fichero_controlador)) {
			
			\core\Aplicacion::$controlador = new $controlador_clase();
			\core\Aplicacion::$controlador->datos['nombre'] = strtolower($controlador);	
			if (method_exists(\core\Aplicacion::$controlador, $metodo))
			{
				\core\Aplicacion::$controlador->$metodo($datos);
				\core\Aplicacion::$controlador->datos['metodo'] = strtolower($metodo);
			}
			else
			{
				$datos['mensaje'] = "El método <b>$metodo</b> no está definido en <b>$controlador_clase</b>.";
				\core\Respuesta::enviar($datos, "plantilla_404");
			}
		}
		else
		{
			$datos['mensaje'] = "La clase <b>$controlador_clase</b> no existe.";
			\core\Respuesta::enviar($datos, "plantilla_404");
		}
	}
	
	/**
	 * Devuelve el contenido de una entrada del array que se pasa por parámetro.
	 * Si la entrada no existe devuelve null.
	 * 
	 * @param string|integer $indice
	 * @param array $array
	 * @return mixed
	 */
	public function contenido($indice, $array)
	{
		if ( ! is_string($indice) && ! is_integer($indice))
			throw new \Exception(__METHOD__." Error: parámetro \$indice=$indice debe ser entero o string");
		elseif ( !is_array($array))
			throw new \Exception(__METHOD__." Error: parámetro \$array debe ser un array");
		
		return (array_key_exists($indice, $array) ? $array[$indice] : null);
	}
} // Fin de la clase

