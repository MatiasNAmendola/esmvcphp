<?php
namespace controladores;


if ( ! defined('PATH_APP')) 
	exit('FORBIDEN');

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
		$validaciones = array(
			'login' => 'errores_requerido && errores_login',
			'contrasena => errores_requerido && errores_contrasena'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if ($validacion)
		{		
			$respuesta =  \datos\usuarios::validar_usuario($datos['values']['login'], $datos['values']['contrasena']);
			if  ($respuesta == 'existe') {
					$datos['errores']['validacion'] = 'Error en usuario o contraseña';
					$this->form_login($datos);
			}
			elseif ($respuesta == 'existe_autenticado') {
					$datos['login'] = $datos['values']['login'];
					$this->cargar_controlador('inicio', 'falta_confirmar', $datos);
			}
			elseif ($respuesta == 'existe_autenticado_confirmado') {
					$datos['login'] = $datos['values']['login'];
					\core\Usuario::nuevo($datos['values']['login']);
					$this->cargar_controlador('inicio', 'logueado', $datos);
			}
		}
		else {
			$datos['errores']['validacion'] = 'Error de usuario o contraseña';
			$this->form_login($datos);
		}
	}
	
	
	
	
	
	
	public function form_login_email(array $datos = array())
	{
		$datos['js'][self::js_script_tag(__FUNCTION__)] = true;
		$datos['js'][self::js_script_lib_tag('jquery/jquery-1.6.4.min.js')] = true;
		$datos['css'][self::css_link_tag(__FUNCTION__)] = true;
		print_r($datos);
		
		
		
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
	}
	
	
	
	public function validar_form_login_email(array $datos = array())
	{
		$validaciones = array(
			'login' => 'errores_texto',
			'email' => 'errores_email',
			'contrasena' => 'errores_requerido'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		if ($validacion)
		{		
			if ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce el login o el dni';
				$validacion = false;
			}
			elseif ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce solo uno de los dos: login o el dni';
				$validacion = false;
			}
		}
		if ($validacion)
		{		
			$respuesta =  \datos\usuarios::validar_usuario_login_email($datos['values']);
			if  ($respuesta == 'existe') {
					$datos['error_validacion'] = 'Error en usuario o contraseña';
					$this->form_login($datos);
			}
			elseif ($respuesta == 'existe_autenticado') {
					$datos['login'] = $datos['values']['login'];
					$this->cargar_controlador('inicio', 'falta_confirmar', $datos);
			}
			elseif ($respuesta == 'existe_autenticado_confirmado') {
					$datos['login'] = $datos['values']['login'];
					\core\Usuario::nuevo($datos['values']['login']);
					$this->cargar_controlador('inicio', 'logueado', $datos);
			}
			else
					echo __METHOD__." REspuesta de valicacion: '$respuesta'";
		}
		else {
			//print_r($datos);
			$datos['errores']['validacion'] = 'Corrige los errores.';
			$this->form_login_email($datos);
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
	
	
	
	
	public function desconectar(array $datos = array()) {
		
		\core\Usuario::cerrar_sesion();
		if ( ! isset($datos['desconexion_razon']))
			$datos['desconexion_razon'] = null;
		if ($datos['desconexion_razon'] === null) {
			$datos['mensaje'] = 'Adios';
			$datos['url_continuar'] = \core\URL::http('?menu=inicio&submenu=');
		}
		elseif ($datos['desconexion_razon'] == 'inactividad') {
			$datos['mensaje'] = 'Has superado el tiempo de inactividad que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.';
			$datos['url_continuar'] = \core\URL::http('?menu=inicio&submenu=');
		}
		elseif ($datos['desconexion_razon'] == 'tiempo_sesion_agotado') {
			$datos['mensaje'] = 'Has agotado el tiempo de tu sesión que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.<br />Vuelve a conectarte para seguir trabajando.';
			$datos['url_continuar'] = \core\URL::http('?menu=inicio&submenu=');
		}
		
		$this->cargar_controlador('mensajes', '', $datos);
		
	}
	
	
} // Fin de la clase