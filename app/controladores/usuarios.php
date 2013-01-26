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
		$respuesta =  \datos\usuarios::validar_usuario( \core\CGI::post('login') , \core\CGI::post('contrasena'));
		if  ($respuesta == 'existe') {
				$datos['error_validacion'] = 'Error en usuario o contraseña';
				$this->form_login($datos);
		}
		elseif ($respuesta == 'existe_autenticado') {
				$datos['login'] = \core\CGI::post('login');
				$this->cargar_controlador('inicio', 'falta_confirmar', $datos);
		}
		elseif ($respuesta == 'existe_autenticado_confirmado') {
				$datos['login'] = \core\CGI::post('login');
				$this->cargar_controlador('inicio', 'logueado', $datos);
		}
		else
				echo __METHOD__." '$respuesta'";
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
	
	public function form_insertar_externo(array $datos = array())
	{
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	public function validar_form_insertar_externo(array $datos = array()) {
		$validaciones = array(
			'login' => 'errores_requerido && errores_login && errores_unicidad_insertar:login/usuarios/login',
			'email' => 'errores_requerido && errores_email ',
			'password' => 'errores_requerido && errores_contrasena'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if ($validacion)
		{
		
			$datos['values']['password'] = md5($datos['values']['password']);
			$datos['values']['clave_confirmacion'] = \core\Random_String::generar(30);
	
			\datos\usuarios::insert($datos['values'], 'usuarios');
			
			$datos['values']['id'] = \datos\usuarios::last_insert_id();
			
			$datos['mensaje'] = 'Se ha grabado correctamente el usuario. Haz la confirmación por correo electronico.';
			
			$datos['url'] = \core\URL::http("?menu=usuarios&submenu=confirmar_alta&id={$datos['values']['id']}&key={$datos['values']['clave_confirmacion']}");
			$this->cargar_controlador('mensajes', 'ok_alta_usuario_falta_confirmacion', $datos);		
		}
		else
		{
			$this->form_insertar_externo($datos);
		}
	}
	
	
	
	public function confirmar_alta(array $datos = array()) {
		
		$validaciones = array(
			'id' => 'errores_requerido && errores_referencia:id/usuarios/id'
			,'key' => 'errores_requerido '
		);
		
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Petición incorrecta.';
			$this->cargar_controlador('mensajes', '', $datos);
			return;
		}
		else {
			
			$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is not null " ;
			$filas = \datos\usuarios::select('usuarios', $clausulas);
			
			if (count($filas)) {
				// El usuario esta confirmado previamente
				$datos['mensaje'] = "Este proceso de confirmación lo realizaste en una fecha anterior: {$filas[0]['fecha_confirmacion_alta']}.";
				$this->cargar_controlador('mensajes', '', $datos);
				return;
			}
			else {
				$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is null " ;
				$filas = \datos\usuarios::select('usuarios', $clausulas);
				if (count($filas) == 1) {
					// El usuario es correcto y está sin confirmar
					unset($datos['values']['key']);
					$datos['values']['fecha_confirmacion_alta'] = gmdate("Y-m-d h:i:s");
					$resultado = \datos\usuarios::update($datos['values'], 'usuarios');
					$datos['mensaje'] = "proceso de confirmación completado fecha: {$datos['values']['fecha_confirmacion_alta']}. Ya puedes loquearte";
					
					$datos['url_continuar'] = \core\URL::http("?menu=usuarios&submenu=form_login");
					$this->cargar_controlador('mensajes', '', $datos);
					return;
					
					
					
				}		
			}
			
			
		}
				
		
		
	} // Fin de método
	
	
	
} // Fin de la clase