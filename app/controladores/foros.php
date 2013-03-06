<?php
namespace controladores;

class foros extends \core\Controlador {

	
	
	
	public function index(array $datos=array()) {
		
		$clausulas['order_by'] = 'foro';
		$datos["filas"] = \datos\Datos_SQL::select('foros', $clausulas); // Recupera todas las filas ordenadas
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}
	
	
	public function form_insertar(array $datos=array()) {
		
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		\core\Respuesta::enviar($datos);
		
	}

	
	
	public function validar_form_insertar(array $datos=array())
	{	
		$validaciones = array(
			'foro' => 'errores_requerido && errores_texto'
		);
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);

		if ( ! $validacion) {
		/* Hay errores de validación */
			$datos['errores']['validacion'] = 'Corrige los errores ';
			$this->cargar_controlador('foros', 'form_insertar', $datos);
		}
		else {
			/* Superadas todas las validaciones */
			/* Escribir la inserción en la bd en la tabla foros(foro_id auto_numeric, foro, login_creador, fecha_creacion default) */
			$sql = "
				insert into foros 
					set foro = '{$datos['values']['foro']}'
					, login_creador= '".\core\Usuario::$login."'
			;";
			$resultado = mysql_query($sql);
			if ($resultado) {
				$datos['alerta'] = ' Inseción exitosa ';
			}
			else {
				$datos['alerta'] = ' Inseción fallida ';
			}
			/* Devolver el control a \controladores\foros::listado($datos) */
			$this->cargar_controlador('foros', 'index', $datos);
		}
	}
	
}