<?php
namespace core;

class Configuracion
{
	public static $controlador_por_defecto = 'Inicio';
	
	public static $metodo_por_defecto = 'index';
	
	public static $plantilla_por_defecto = 'plantilla_principal';
	
	public static $mysql = array(
		'server'   => 'localhost',
		'user'     => 'daw2_user',
		'password' => 'daw2_user',
		'dbname'   => 'daw2',
		'prefix_'  => 'foro_'
	);
	
} // Fin de la clase 
