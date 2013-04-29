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
	
	
	public static function distribuidor() {
		
		self::analizar_peticion();
		
	}
	
	public static function analizar_peticion() {
		
		$controlador = \core\CGI::get('menu');
		$metodo = \core\CGI::get('submenu');
		
		if ( ! $controlador )
			$controlador = strtolower(\core\Configuracion::$controlador_por_defecto);
		if ( ! $metodo )
			$metodo = strtolower(\core\Configuracion::$metodo_por_defecto);
		
		
		// Comprobamos que el usuario tiene permisos. Si no los tiene se redirige hacia otro controlador.
		if (\core\Usuario::tiene_permiso($controlador, $metodo) === false ) {
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
		
		// Comprobamos que la sesión de un usuario distinto de anónimo cumple las condiciones de tiempo
		// Superación del tiempo de actividad
		if (\core\Usuario::$login != 'anonimo') {
			if (\core\Usuario::$sesion_segundos_inactividad >= \core\Configuracion::$sesion_minutos_inactividad * 60) {
				$controlador = 'usuarios';
				$metodo = 'desconectar';
				$datos['desconexion_razon'] = 'inactividad';
			}
			elseif (\core\Usuario::$sesion_segundos_duracion >= \core\Configuracion::$sesion_minutos_maxima_duracion * 60) {
				$controlador = 'usuarios';
				$metodo = 'desconectar';
				$datos['desconexion_razon'] = 'tiempo_sesion_agotado';
			}
		}
		
		
		/*
		// Comprobar los permisos de los usuarios desde la ACL (Acces Control List)
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
		*/
		
		self::cargar_controlador($controlador, $metodo);
		
	}
	
	
	
	public static function cargar_controlador($controlador, $metodo, array $datos = array()) {
		
		
		
		$fichero_controlador = strtolower(PATH_APP."controladores/$controlador.php");
		$controlador_clase = strtolower("\\controladores\\$controlador");
		if (file_exists($fichero_controlador)) {
			
			\core\Aplicacion::$controlador = new $controlador_clase();
			\core\Aplicacion::$controlador->datos['controlador_clase'] = strtolower($controlador);	
			if (method_exists(\core\Aplicacion::$controlador, $metodo))
			{
				\core\Aplicacion::$controlador->datos['controlador_metodo'] = strtolower($metodo);
				\core\Aplicacion::$controlador->$metodo($datos);
				
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
	public function contenido($indice, array $array)
	{
		if ( ! is_string($indice) && ! is_integer($indice))
			throw new \Exception(__METHOD__." Error: parámetro \$indice=$indice debe ser entero o string");
		
		return (array_key_exists($indice, $array) ? $array[$indice] : null);
	}
} // Fin de la clase

