<?php
namespace core;

class Configuracion
{
	public static $controlador_por_defecto = 'Inicio';
	
	public static $metodo_por_defecto = 'index';
	
	public static $plantilla_por_defecto = 'plantilla_principal';
	
	public static $sesion_minutos_inactividad = 20; // Minutos
	
	public static $sesion_minutos_maxima_duracion = 120;
	
	
	/**
	 *
	 * @var string Tipo MIME utilizado por defecto.
	 */
	public static $tipo_mime_por_defecto = 'text/html';
	
	/**
	 *
	 * @var array = Colección de tipos MIME soportados por esta aplicación. 
	 */
	public static $tipos_mime_reconocidos = array(
		'text/html', 'text/xml', 'text/json', 'application/excel', 
	);
	
	

	public static $mysql = array(
		'server'   => 'localhost',
		'user'     => 'daw2_user',
		'password' => 'daw2_user',
		'dbname'   => 'daw2',
		'prefix_'  => 'foro_'
	);
	
	/*
	public static $mysql = array(
		'server'   => 'sql210.byethost8.com',
		'user'     => 'b8_12128753',
		'password' => '**************',
		'dbname'   => 'b8_12128753_esmvcphp',
		'prefix_'  => 'foro_'
	);
	*/
	/**
	 * Define array llamado recursos_y_suariosla con la definición de todos los permisos de acceso a los recursos de la aplicación.
	 * * Recursos:
	 *  [*][*] define todos los recursos
	 *  [controlador][*] define todos los métodos de un controlador
	 * Usuarios:
	 *  * define todos los usuarios (anonimo más logueados)
	 *  ** define todos los usuarios logueados (anonimo no está incluido)
	 * 
	 * @var array =('controlador' => array('metodo' => ' nombres usuarios rodeados por espacios
	 */
	public static $recursos_y_usuarios = array(
		'*' =>	array(
					'*' => ' admin '
				),
		'inicio' => array (
						'*' => ' ** ',
						'index' => ' * ',
					),
	
		'mensajes' => array(
							'*' => ' * ',
							),
		'usuarios' => array(
							'*' => ' juan pedro ',
							'index' => ' anais ana olga ',
							'desconectar' => ' ** ',
							'form_login_email' => ' anonimo ',
							'validar_form_login_email' => ' anonimo ',
							)
	
	);
} // Fin de la clase 
