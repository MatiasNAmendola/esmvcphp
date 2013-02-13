<?php
namespace core;

class Usuario extends \core\Clase_Base {
	
	public static $login = 'anonimo';
	public static $permisos = array();
	
	/**
	 * Reconocer el usuario que ha iniciado la sesión de trabajo o que continúan dentro de una sesión de trabajo.
	 */
	public static function iniciar() {
		
		if (isset($_SESSION['usuario']['login'])) {
			self::$login = $_SESSION['usuario']['login'];
		}
		
		self::recuperar_permisos_bd(self::$login);
		/*if (isset($_SESSION['usuario']['permisos'])) {
			self::$permisos = $_SESSION['usuario']['permisos'];
		}
		else {
			self::recuperar_permisos_bd(self::$login);
		}
		
		 */
		
		
		
		if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
			$_SESSION['usuario']['contador_paginas_visitadas']++;
		else 
			$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		
		echo "<pre>"; print_r(self::$permisos); echo "</pre>";  //exit(__METHOD__);
		
	}
	
	
	
	public static function nuevo($login) {
		
		self::$login = $login;
		\core\SESSION::regenerar_id();
		$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		$_SESSION['usuario']['login'] = $login;
		
		self::recuperar_permisos_bd(self::$login);		
	}
	
	
	public static function cerrar_sesion() {
		
		//self::$login = 'anonimo';
		unset($_SESSION['usuario']);
		\core\SESSION::destruir();
		self::nuevo('anonimo');
	}
	
	
	public static function recuperar_permisos_bd($login) {
		self::$permisos = \datos\usuarios::permisos_usuario($login);
		$_SESSION['usuario']['permisos'] = self::$permisos;
		//echo "<pre>"; print_r(self::$permisos); echo "</pre>";  exit(__METHOD__);
		/*
		self::$permisos['*']['*'] = ' ';
		self::$permisos['inicio']['*'] = '';
		self::$recursos['inicio']['index'] = ' * ';
		self::$recursos['mensajes']['*'] = ' * ';
		self::$recursos['usuarios']['*'] = ' juan pedro ';
		self::$recursos['usuarios']['index'] = ' anais ana olga ';
		self::$recursos['usuarios']['desconectar'] = ' ** ';
		self::$recursos['usuarios']['form_login_email'] = ' anonimo ';
		self::$recursos['usuarios']['validar_form_login_email'] = ' anonimo ';
		//print_r(self::$recursos);
		*/
		
		
		
		
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
	
	
	
} // Fin de la clase