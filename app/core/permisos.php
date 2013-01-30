<?php
namespace core;

class Permisos {
	
	private static $recursos;
	
	public static function iniciar() {
		// * todos los usuarios incluido anonimo
		// ** todos los usuarios logueados, excluye a anonimo
		self::$recursos['*']['*'] = ' admin ';
		self::$recursos['inicio']['*'] = ' ** ';
		self::$recursos['inicio']['index'] = ' * ';
		self::$recursos['mensajes']['*'] = ' * ';
		self::$recursos['usuarios']['*'] = ' juan ';
		self::$recursos['usuarios']['index'] = ' anais ';
		self::$recursos['usuarios']['desconectar'] = ' ** ';
		self::$recursos['usuarios']['form_login'] = ' anonimo ';
		self::$recursos['usuarios']['validar_form_login'] = ' anonimo ';
		//print_r(self::$recursos);
	}
	
	
	public static function comprobar($login, $controlador, $metodo = 'index') {
		$autorizado = false;
		
		// Control de error de definición de la lista de permisos
		/*
		if ( ! isset(self::$recursos['*']['*']))
			throw new \Exception(__METHOD__." Error debe definirse el permiso ['*']['*']");
		elseif ( ! isset(self::$recursos[$controlador]['*']))
			throw new \Exception(__METHOD__." Error debe definirse el permiso [$controlador]['*']");
		elseif ( ! isset(self::$recursos[$controlador][$metodo]))
			throw new \Exception(__METHOD__." Error debe definirse el permiso [$controlador][$metodo]");
		*/
		
		// El usuario tiene todos los permisos
		$usuario = "/\s$login\s/i";
		$todos = "/\s\*\s/i";
		$logueados = "/\s\*\*\s/i";
		
		if (isset(self::$recursos['*']['*']) && preg_match($usuario, self::$recursos['*']['*']))
			$autorizado = true;
		elseif (isset(self::$recursos[$controlador]['*']) && (preg_match($usuario, self::$recursos[$controlador]['*']) or preg_match($todos, self::$recursos[$controlador]['*'])))
			$autorizado = true;
		elseif (isset(self::$recursos[$controlador][$metodo]) && (preg_match($usuario, self::$recursos[$controlador][$metodo]) or preg_match($todos, self::$recursos[$controlador][$metodo])))
			$autorizado = true;	
		elseif (isset(self::$recursos[$controlador]['*']) && $login != 'anonimo' && preg_match($logueados, self::$recursos[$controlador]['*']) )
			$autorizado = true;	
		elseif (isset(self::$recursos[$controlador][$metodo]) && $login != 'anonimo' &&  preg_match($logueados, self::$recursos[$controlador][$metodo]))
			$autorizado = true;	
		elseif ( ! isset(self::$recursos[$controlador][$metodo]) && ! isset(self::$recursos[$controlador]['*']))		{	
			$autorizado = null;
		}
		
		return $autorizado;
	}
}