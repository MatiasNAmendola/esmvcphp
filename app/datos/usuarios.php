<?php
namespace datos;

class usuarios extends \core\sgbd\bd
{
	
	public static function validar_usuario($login, $contrasena)
	{
		$contrasena = md5($contrasena);
		$sql = "
			select login
			from ".self::$prefix_.'usuarios'."
			where login = '$login' and password = '$contrasena'
		";
		$filas = self::recuperar_filas($sql);
		return (count($filas) == 1);
	}
	
}
?>
