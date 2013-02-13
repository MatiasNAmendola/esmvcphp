<?php
namespace core;

class Usuario extends \core\Clase_Base {
	
	public static $login = 'anonimo';
	public static $permisos = array();
	public static $sesion_segundos_duracion = 0;
	public static $sesion_segundos_inactividad = 0;
	
	/**
	 * Reconocer el usuario que ha iniciado la sesión de trabajo o que continúan dentro de una sesión de trabajo.
	 */
	public static function iniciar() {
		
		if (isset($_SESSION['usuario']['login'])) {
			self::$login = $_SESSION['usuario']['login'];
		}
		else
			self::nuevo ('anonimo');
		
		if (isset($_SESSION['usuario']['permisos'])) {
			self::$permisos = $_SESSION['usuario']['permisos'];
		}
		else {
			self::recuperar_permisos_bd(self::$login);
		}
		
		
		if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
			$_SESSION['usuario']['contador_paginas_visitadas']++;
		else 
			$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		
		self::sesion_control_tiempos();
		
	}
	
	
	
	public static function nuevo($login) {
		
		self::$login = $login;
		\core\SESSION::regenerar_id();
		$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		$_SESSION['usuario']['login'] = $login;
		$_SESSION['usuario']['sesion_inicio'] = $_SERVER['REQUEST_TIME'];
		
		self::recuperar_permisos_bd(self::$login);
		self::sesion_control_tiempos();
	}
	
	
	public static function cerrar_sesion() {
		
		//self::$login = 'anonimo';
		unset($_SESSION['usuario']);
		\core\SESSION::destruir();
		self::nuevo('anonimo');
		self::sesion_control_tiempos();
		
	}
	
	
	
	private static function recuperar_permisos_bd($login) {
		
		self::$permisos = \datos\usuarios::permisos_usuario($login);
		$_SESSION['usuario']['permisos'] = self::$permisos;
		
	}
	
	
	public static function tiene_permiso($controlador, $metodo = 'index') {
		
		$autorizado = false;
				
		// El usuario tiene acceo a todos los recursos
		if (isset(self::$permisos['*']['*']))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso a todos los métodos del controlador
		elseif (isset(self::$permisos[$controlador]['*']))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso al controlador y método determinado
		elseif (isset(self::$permisos[$controlador][$metodo]))
			$autorizado = true;	
		
		return $autorizado;
	}
	
	
	
	
	
	private static function sesion_control_tiempos() {
		
		// Tiempo de inactividad
		if (isset($_SESSION['usuario']['sesion_request_time']))
			self::$sesion_segundos_inactividad = $_SERVER['REQUEST_TIME'] - $_SESSION['usuario']['sesion_request_time'];
		else
			self::$sesion_segundos_inactividad = 0;
		
		// Duración de la sesión
		if (isset($_SESSION['usuario']['sesion_inicio']))
			self::$sesion_segundos_duracion = $_SERVER['REQUEST_TIME'] - $_SESSION['usuario']['sesion_inicio'];
		else
			self::$sesion_segundos_duracion = 0;
		
		// Memorizamos la hora de la petición actual para tenerlo en cuenta en la siguiente petición que realice el usuario.
		$_SESSION['usuario']['sesion_request_time'] = $_SERVER['REQUEST_TIME'];
		
	}
	
	
	
} // Fin de la clase