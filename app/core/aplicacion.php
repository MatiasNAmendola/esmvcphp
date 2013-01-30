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
		
		\core\SESSION::iniciar();
		
		\core\Usuario::iniciar();
		
		\core\Permisos::iniciar();
			
		\core\sgbd\bd::conectar();
		
		// Distribuidor
		self::$controlador = $this->cargar_controlador(\core\CGI::get('menu'),\core\CGI::get('submenu'));

		\core\sgbd\bd::desconectar();
	}
	
} // Fin de la clase