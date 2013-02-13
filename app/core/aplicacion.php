<?php
namespace core;

/**
 * Aplicación principal
 *
 *
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Aplicacion extends \core\Clase_Base 
{
	
	public static $controlador;
	
	public function __construct() {
		
		\core\sgbd\bd::conectar();
		
		\core\SESSION::iniciar();
		
		// Reconocer el usuario que ha iniciado la sesión de trabajo o que continúan dentro de una sesión de trabajo.
		\core\Usuario::iniciar();
				
		// Los usamos si trabajamos con la ACL (Acces Control List) para definir los permisos de los usuarios
		// \core\Permisos::iniciar();
		
		// Distribuidor
		self::$controlador = $this->cargar_controlador(\core\CGI::get('menu'),\core\CGI::get('submenu'));

		\core\sgbd\bd::desconectar();
	}
	
} // Fin de la clase