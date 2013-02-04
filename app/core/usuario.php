<?php
namespace core;

class Usuario extends \core\Clase_Base {
	
	public static $login = 'anonimo';
	
	/**
	 * Reconocer el usuario que ha iniciado la sesión de trabajo o que continúan dentro de una sesión de trabajo.
	 */
	public static function iniciar() {
		
		if (isset($_SESSION['usuario']['login'])) {
			self::$login = $_SESSION['usuario']['login'];
		}
		if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
			$_SESSION['usuario']['contador_paginas_visitadas']++;
		else 
			$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		
	}
	
	
	
	public static function nuevo($login) {
		
		self::$login = $login;
		\core\SESSION::regenerar_id();
		$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		$_SESSION['usuario']['login'] = $login;
		
	}
	
	
	public static function cerrar_sesion() {
		
		//self::$login = 'anonimo';
		unset($_SESSION['usuario']);
		\core\SESSION::destruir();
		self::nuevo('anonimo');
	}
	
} // Fin de la clase