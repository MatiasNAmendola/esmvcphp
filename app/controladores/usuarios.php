<?php
namespace controladores;

class usuarios extends \core\Controlador
{
	public function index(array $datos = array())
	{
		$clausulas['order_by'] = 'login';
		$datos['filas'] = \datos\usuarios::select('usuarios', $clausulas);
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}
	
	public function form_login(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	public function validar_form_login(array $datos = array())
	{
		if (\datos\usuarios::validar_usuario( \core\CGI::post('login') , \core\CGI::post('contrasena')) )
		{
			$datos['login'] = \core\CGI::post('login');
			$this->cargar_controlador('inicio', 'logueado', $datos);
		}
		else
		{
			$datos['error_validacion'] = 'Error en usuario o contraseña';
			$this->form_login($datos);
		}
	}
	
	
	
	public function validar_form_modificar(array $datos = array())
	{
	
		
	}
	
	
	public function validar_form_borrar(array $datos = array())
	{
		
		
	}
	
	
	public function form_insertar(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	public function validar_form_insertar(array $datos = array())
	{
		$validaciones = array(
			'login' => 'errores_requerido && errores_login && errores_unicidad_insertar:login/usuarios/login',
			'email' => 'errores_requerido && errores_email ',
			'password' => 'errores_requerido && errores_contrasena'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if ($validacion)
		{
		
			$datos['values']['password'] = md5($datos['values']['password']);
	
			\datos\usuarios::insert($datos['values'], 'usuarios');
			$datos['alerta'] = 'Se ha grabado correctamente.';
			$this->index($datos);
		}
		else
		{
			$this->form_insertar($datos);
		}
			
		
		
	}
	
} // Fin de la clase