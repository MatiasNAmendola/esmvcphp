<?php
namespace core;

/**
 * Clase que se utilizará cuando la gestión de permisos se vaya a realizar de manera
 * estática, es decir, uno pocos usuarios y unos pocos permisos que se definen sobre un array.
 * 
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Permisos {
	
	/**
	 * Propiedad donde se definen los recursos de la aplicación y la lista de usuarios que pueden acceder a ellos.
	 * Recursos:
	 *  [*][*] define todos los recursos
	 *  [controlador][*] define todos los métodos de un controlador
	 * Usuarios:
	 *  * define todos los usuarios (anonimo más logueados)
	 *  ** define todos los usuarios logueados (anonimo no está incluido)
	 * 
	 * @var array('controlador' => array('metodo' => ' nombres usuarios rodeados por espacios
	 */
	private static $recursos;
	
	
	
	/**
	 * Carga en un array llamado recursos la definición de todos los permisos de acceso a los recursos de la aplicación.
	 * * Recursos:
	 *  [*][*] define todos los recursos
	 *  [controlador][*] define todos los métodos de un controlador
	 * Usuarios:
	 *  * define todos los usuarios (anonimo más logueados)
	 *  ** define todos los usuarios logueados (anonimo no está incluido)
	 * Estructura del arrray $recursos = array =('controlador' => array('metodo' => ' nombres usuarios rodeados por espacios
	 */
	public static function iniciar() {
		// * todos los usuarios incluido anonimo
		// ** todos los usuarios logueados, excluye a anonimo
		/*
		self::$recursos['*']['*'] = ' admin ';
		self::$recursos['inicio']['*'] = ' ** ';
		self::$recursos['inicio']['index'] = ' * ';
		self::$recursos['mensajes']['*'] = ' * ';
		self::$recursos['usuarios']['*'] = ' juan pedro ';
		self::$recursos['usuarios']['index'] = ' anais ana olga ';
		self::$recursos['usuarios']['desconectar'] = ' ** ';
		self::$recursos['usuarios']['form_login_email'] = ' anonimo ';
		self::$recursos['usuarios']['validar_form_login_email'] = ' anonimo ';
		//print_r(self::$recursos);
		*/
		self::$recursos = \core\Configuracion::$recursos_y_usuarios;
		
	}
	
	/**
	 * Comprueba si un login tiene acceso a un recurso, ya sea por asignación directa o por
	 * estar asignado a todos los usuairos o a todos los usuarios logueados.
	 * 
	 * @param type $login
	 * @param type $controlador
	 * @param type $metodo
	 * @return null
	 */
	public static function comprobar($login, $controlador, $metodo = 'index') {
		
		$autorizado = false;
		
		// Patrones para buscar usuarios en la relacion de usuarios
		$usuario = "/\s$login\s/i";
		$todos = "/\s\*\s/i";
		$logueados = "/\s\*\*\s/i";
		
		// El usuario tiene acceo a todos los recursos
		if (isset(self::$recursos['*']['*']) && preg_match($usuario, self::$recursos['*']['*']))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso a todos los métodos del controlador
		elseif (isset(self::$recursos[$controlador]['*']) && (preg_match($usuario, self::$recursos[$controlador]['*']) or preg_match($todos, self::$recursos[$controlador]['*'])))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso al controlador y método determinado
		elseif (isset(self::$recursos[$controlador][$metodo]) && (preg_match($usuario, self::$recursos[$controlador][$metodo]) or preg_match($todos, self::$recursos[$controlador][$metodo])))
			$autorizado = true;	
		// Los usuarios logueados tienen acceso al controlador y todos sus métodos
		elseif (isset(self::$recursos[$controlador]['*']) && $login != 'anonimo' && preg_match($logueados, self::$recursos[$controlador]['*']) )
			$autorizado = true;	
		// Los usuarios logueados tienen acceso al controlador y método determinado
		elseif (isset(self::$recursos[$controlador][$metodo]) && $login != 'anonimo' &&  preg_match($logueados, self::$recursos[$controlador][$metodo]))
			$autorizado = true;	
		// El [controlador] y [metodo] determinado no están detallados en la relación de permisos ni el [controlador][*] (y todos sus métodos), por lo tanto no se sabe si tendría autorización de estar incluidos.
		elseif ( ! isset(self::$recursos[$controlador][$metodo]) && ! isset(self::$recursos[$controlador]['*'])) {	
			$autorizado = null;
		}
		
		return $autorizado;
	}
}