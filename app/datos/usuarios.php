<?php
namespace datos;

class usuarios extends \core\sgbd\bd
{
	/**
	 * 
	 * @param type $login
	 * @param type $contrasena
	 * @return string  Valores: ''  'existe'  'existe_autenticado' 'existe_autenticado_confirmado'
	 */
	public static function validar_usuario($login, $contrasena)
	{
		$validacion = "";
		$contrasena = md5($contrasena);
		$sql = "
			select id, login, password, fecha_confirmacion_alta
			from ".self::$prefix_.'usuarios'."
			where login = '$login'
		";
		$filas = self::recuperar_filas($sql);
		
		if (count($filas) == 1) { // Usuario y contraseña correctos
			$validacion = "existe";
			
			if ($filas[0]['password'] == $contrasena) {
				$validacion .= "_autenticado";
				if ($filas[0]['fecha_confirmacion_alta'] != '') {
					$validacion .= "_confirmado";
				}		
			}
		}
		echo __METHOD__; var_dump($validacion);
		return ($validacion);
	}
	
}
?>
