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
		
		// Reconocer el usuario que ha iniciado la sesión de trabajo o que continúa dentro de una sesión de trabajo.
		\core\Usuario::iniciar();
				
		// Los usamos si trabajamos con la ACL (Acces Control List) para definir los permisos de los usuarios
		// \core\Permisos::iniciar();
		
		// Distribuidor
		$this->distribuidor();

		\core\sgbd\bd::desconectar();
	}
	
} // Fin de la clase